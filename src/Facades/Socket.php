<?php

namespace Zilehuda\TcpSocket\Facades;

use Illuminate\Support\Facades\Facade;

class Socket extends Facade {

    protected static function getFacadeAccessor() {
        return 'TcpSocket';
    }

}
