<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SystemNotification extends Notification
{
    use Queueable;

    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // or ['mail', 'database'] if sending email and database notifications
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->data['title'],
            'message' => $this->data['message'],
            'authorized_by' => $this->data['authorized_by'],
            'authorized_user_name' => $this->data['authorized_user_name'], // Add this field
            'asset_name' => $this->data['asset_name'],
            'asset_code' => $this->data['asset_code'],
            'action_url' => $this->data['action_url'] ?? null,
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->data['title'])
            ->greeting("Hello, {$notifiable->firstname}!")
            ->line($this->data['message'])
            ->line("Asset: {$this->data['asset_name']} (Code: {$this->data['asset_code']})")
            ->line("Authorized by: {$this->data['authorized_user_name']}")
            ->action('View Request', url($this->data['action_url'] ?? '#'))
            ->line('Thank you for using the system!');
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable); // Use same format for array notifications
    }
}

