<?php

namespace App\Classes;

class SocketHelper{

    public $socket;
    public $retourSocket;

    public function __construct($host,$port){
        $this->socket = @socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
        //Ajout d'un timeout de 2s pour le socket read afin d'éviter qu'il attende trop longtemps
        //si jamais il n'y a pas de retour
        socket_set_option($this->socket,SOL_SOCKET, SO_RCVTIMEO, array("sec"=>7, "usec"=>0));
        $result = @socket_connect($this->socket,$host,$port);
        //On rend le retour accessible pour la fonction isOnline()
        global $retourSocket;
        $retourSocket = $result;
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

    /**
     * Regarde si le socket est ouvert ou non
     * @return boolean vrai ou faux
     */
    public function isOnline()
    {
        global $retourSocket;
        return $retourSocket;
    }
}
