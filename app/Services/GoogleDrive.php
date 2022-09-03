<?php 

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

class GoogleDrive {
    protected $client;
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function upload($access_token,$zipFileName)
    {
        $this->client->setAccessToken($access_token);
        $service = new Drive($this->client);
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
    }
}