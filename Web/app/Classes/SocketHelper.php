<?php

namespace App\Classes;

class SocketHelper{

    public $socket;
    public $retourSocket;

    public function __construct($host,$port){
        $this->socket = @socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
        $con_per_sec = 100;
        //Durée en seconde au bout de laquelle on arrête d'essayer de se connecter
        //à la socket
        $timeout = 1;
        socket_set_nonblock($this->socket);

        //Permets d'éviter un chargement trop long
        for($i=0; $i<($timeout * $con_per_sec); $i++) {
            $result = @socket_connect($this->socket,$host,$port);
            if(socket_last_error($this->socket) == SOCKET_EISCONN) {
                break;
            }
            usleep(1000000 / $con_per_sec);
        }
        socket_set_block($this->socket);

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
