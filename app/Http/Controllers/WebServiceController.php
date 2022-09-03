<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Google\Client;
use App\Models\WebService;
use App\Services\GoogleDrive;
use App\Services\Zipper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class WebServiceController extends Controller
{
    const SCOPES = [
        'https://www.googleapis.com/auth/drive',
        'https://www.googleapis.com/auth/drive.file'
    ];

    public function connect($web_service,Client $client)
    {
        if($web_service == 'google-drive'){
            $client->setScopes(self::SCOPES);
            $url = $client->createAuthUrl();
            return response(['url'=>$url]);
        }
    }

    public function callback(Request $request,Client $client)
    {
        $access_token = $client->fetchAccessTokenWithAuthCode($request->code);
        return WebService::create([
            'user_id' => auth()->id(),
            'token' => $access_token,
            'name' => 'google-drive'
        ]);
    }

    public function store(WebService $web_service,GoogleDrive $drive)
    {
        // get last 7 days tasks
        $tasks = Task::where('created_at','>=',  now()->subDays(7))->get();

        // create json file to these tasks
        $jsonFileName = 'tasks.json';
        Storage::put("/public/temp/$jsonFileName",TaskResource::collection($tasks)->toJson());

        $zipFileName = Zipper::toZip($jsonFileName);

        // upload this file to drive
        $access_token = $web_service->token['access_token'];
        $drive->upload($access_token,$zipFileName);

        Storage::deleteDirectory('public/temp');
        return response('upload',Response::HTTP_CREATED);
    }
}
