<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Google\Client;
use Google\Service\Drive;
use App\Models\WebService;
use Illuminate\Http\Request;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;

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

    public function store(Request $request,WebService $web_service,Client $client)
    {
        // get last 7 days tasks
        $tasks = Task::where('created_at','>=',  now()->subDays(7))->get();
        // create json file to these tasks
        $jsonFileName = 'tasks.json';
        Storage::put("/public/temp/$jsonFileName",$tasks->toJson());
        // zip json file
        $zip = new ZipArchive();
        $zipFileName = storage_path('/app/public/temp/' . now()->timestamp . '-tasks.zip');
        if($zip->open($zipFileName,ZipArchive::CREATE) === true){
            $filePath = storage_path('app/public/temp/' . $jsonFileName);
            $zip->addFile($filePath,$jsonFileName);
        }
        $zip->close();
        // upload this file to drive
        // dd($web_service->token['access_token']);
        $access_token = $web_service->token['access_token'];

        $client->setAccessToken($access_token);
        $service = new Drive($client);
        $file = new DriveFile();

        $file->setName('helloWorld.zip');
        $service->files->create(
            $file,
            [
                'data' => file_get_contents($zipFileName),
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'media'
            ]
        );

        return response('upload',Response::HTTP_CREATED);
    }
}
