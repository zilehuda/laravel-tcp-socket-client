<?php

namespace Zilehuda\TcpSocket;

use RuntimeException;


class Socket {

    private $ip = null;
    private $port = null;
    private $protocol = null;
    private $timeout = null;
    private $socket = null;
    private $isConnected = false;
    private $myIp;
    private $myPort;

    public function __construct($ip, $port, $protocol, $timeout)
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->protocol = $protocol;
        $this->timeout = $timeout;
    }
    
    public function connect() {
        
        try {
            $this->socket = socket_create(AF_INET, SOCK_STREAM, $this->protocol);
        } catch (\Throwable $th) {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);
            $this->isConnected = false;
            throw new RuntimeException($errormsg, $errorcode);
        }

        try {
            socket_connect($this->socket, $this->ip, intval($this->port));
        } catch (\Throwable $th) {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);
            $this->isConnected = false;
            throw new RuntimeException($errormsg, $errorcode);
        }
        $this->isConnected = true;
        socket_getsockname($this->socket, $IP, $PORT);
        if ($this->timeout != null) {
            $this->timeout = intval($this->timeout);
            $option = ['sec' => $this->timeout, 'usec' => $this->timeout * 1000];
            socket_set_option($this->socket, SOL_SOCKET, SO_SNDTIMEO, $option);
            socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, $option);
        }
        $this->myIp = $IP;
        $this->myPort = $PORT;
        $result = new \stdClass();
        $result->ip = $IP;
        $result->port = $PORT;
        $result->socket = $this->socket;
        return $result;
    }

    public function sendMessage($message)
    {
        $result = socket_write($this->socket, $message, strlen($message));
        if (!$result) {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);
            $this->isConnected = false;
            throw new \Exception($errormsg, $errorcode);
        }
        return $result;
    }

    public function receiveMessage() {
        $out = '';
        while($out = @socket_read($this->socket, 1024)) {
            if($out = trim($out))
                break;
        }
        return $out;
    }
}