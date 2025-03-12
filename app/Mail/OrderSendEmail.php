<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\SiteSetting;

class OrderSendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data; 

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->data = $data; 
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Received Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {

        $website_logo = SiteSetting::pluck('website_logo')->first();

        $logoPath = public_path("uploads/Logos/{$website_logo}");
        
        return new Content(
            view: 'email.orders-received', 
            with: [
                'Get_website_logo' => $logoPath, 
                'Get_website_name' => $this->data['Get_website_name'],
                'orders_collection' => $this->data['orders_collection'],
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}