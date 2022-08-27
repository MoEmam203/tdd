<?php

namespace App\Http\Controllers;

use Google\Client;
use Google\Service\Drive;
use App\Models\WebService;
use Illuminate\Http\Request;
use Google\Service\Drive\DriveFile;
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
            'token' => json_encode(['access_token' => $access_token]),
            'name' => 'google-drive'
        ]);
    }

    public function store(Request $request,WebService $web_service,Client $client)
    {
        $access_token = $web_service->token['access-token'];

        $client->setAccessToken($access_token);
        $service = new Drive($client);
        $file = new DriveFile();

        DEFINE("TESTFILE", 'testfile-small.txt');
        if (!file_exists(TESTFILE)) {
            $fh = fopen(TESTFILE, 'w');
            fseek($fh, 1024 * 1024);
            fwrite($fh, "!", 1);
            fclose($fh);
        }

        $file->setName('hello world');
        $service->files->create(
            $file,
            [
                'data' => file_get_contents(TESTFILE),
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'media'
            ]
        );

        return response('upload',Response::HTTP_CREATED);
    }
}
