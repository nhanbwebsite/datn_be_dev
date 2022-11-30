<?php

namespace App;

use GuzzleHttp\Client as GuzzleHttpClient;
use Spatie\Dropbox\TokenProvider;

class DropboxRefreshAccessToken implements TokenProvider
{
    public function getToken(): string {
        $resData = '';
        try{
            $c = new GuzzleHttpClient();
            $res = $c->request('POST', 'https://'.env('DROPBOX_APP_KEY').':'.env('DROPBOX_APP_SECRET').'@api.dropbox.com/oauth2/token', [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => env('DROPBOX_REFRESH_TOKEN'),
                ]
            ]);
            if($res->getStatusCode() == 200){
                $resData = json_decode($res->getBody(), true);
            }
        }
        catch(HttpException $e){
            return response()->json([
                'status' => 'error',
                'message' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ], $e->getStatusCode());
        }
        return !empty($resData['access_token']) ? $resData['access_token'] : null;
    }
}
?>
