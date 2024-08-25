<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewUserCredentialsMail extends Mailable{
    use Queueable, SerializesModels;

    public $email;
    public $password;

    public function __construct($email, $password){
        $this->email = $email;
        $this->password = $password;
    }

    public function build(){
        return $this->view('emails.new_user_credentials')
                    ->with([
                        'email' => $this->email,
                        'password' => $this->password,
                    ]);
    }
}

