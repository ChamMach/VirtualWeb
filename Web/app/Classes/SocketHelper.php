<?php

namespace App\Classes;

class SocketHelper{
   
    public $socket;

    public function __construct($host,$port){
        $this->socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
        try {
            $result = socket_connect($this->socket,$host,$port);
            error_log("succes");
        } catch (ErrorException  $e) {
            error_log("Problèmesssssssss");
        }
     }

    public function send_data($content){
         socket_write($this->socket,$content,strlen($content));
    }

     public function read_data(){
     	   while($out = socket_read($this->socket,999999)){
  	       return $out;
         }
     }

     public function close_socket(){
     	   socket_close($this->socket);
     }

    private function isOnline($host, $port)
    {
        @$fp = fsockopen($host,$port,$errno,$errstr,2);
        error_log($errstr);
        error_log($errno);
        if ($errno) {
            $return = false;
        } else {
            $return = true;
        }
        return $return;
    }
}
