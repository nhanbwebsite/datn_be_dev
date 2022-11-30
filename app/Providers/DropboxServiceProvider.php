<?php

namespace App\Providers;

use App\DropboxRefreshAccessToken;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Spatie\Dropbox\Client;
use Spatie\FlysystemDropbox\DropboxAdapter;

class DropboxServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend('dropbox', function ($app, $config){
            $token = new DropboxRefreshAccessToken();
            $token->getToken();
            if(!empty($token)){
                $adapter = new DropboxAdapter(new Client($token));
                return new FilesystemAdapter(new Filesystem($adapter, $config), $adapter, $config);
            }
            else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Lỗi làm mới Dropbox access token !'
                ], 400);
            }
        });
    }
}
