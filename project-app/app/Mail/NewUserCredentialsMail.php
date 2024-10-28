<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

class NewUserCredentialsMail extends Mailable{
    use Queueable, SerializesModels;

    public $email;
    public $password;

    public function __construct($email, $password){
        $this->email = $email;
        $this->password = $password;
    }

    public function envelope(): Envelope{
        return new Envelope(
            subject: 'FAMS User Credentials',
        );
    }

    public function content(): Content{
        return new Content(
            view: 'emails.new_user_credentials',
        );
    }

    public function build(){
        return $this->view('emails.new_user_credentials')
                    ->with([
                        'email' => $this->email,
                        'password' => $this->password,
                    ]);
    }
}

