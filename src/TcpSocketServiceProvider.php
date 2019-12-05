<?php

namespace Zilehuda\TcpSocket;

use Illuminate\Support\ServiceProvider;

class TcpSocketServiceProvider extends ServiceProvider {
    
    public function boot() {

        if($this->app->runningInConsole()) {
            $this->registerPublishing();
        }
    }

    public function register() {

        $this->app->bind('TcpSocket', function() {
            $ip = config('tcp-socket.address');
            $port = config('tcp-socket.port');
            $protocol = config('tcp-socket.protocol');
            $timeout = config('tcp-socket.timeout');
            return new Socket($ip, $port, $protocol, $timeout);
        });
    }

    protected function registerPublishing() {
        $this->publishes([
            __DIR__ .'/../config/tcp-socket.php' => config_path('tcp-socket.php'),
        ], 'tcp-socket-config');
    }
}