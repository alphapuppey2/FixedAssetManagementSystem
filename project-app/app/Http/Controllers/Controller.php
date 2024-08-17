<?php

namespace App\Http\Controllers;

Class Controller
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
