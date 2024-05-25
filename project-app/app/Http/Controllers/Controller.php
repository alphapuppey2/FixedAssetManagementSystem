<?php

namespace App\Http\Controllers;

abstract class Controller
{
    //
    public $user;

    function ___construct(){
        $this->user = auth()->user();
    }
    public function getUser($user){
        return $this->user;
    }
}
