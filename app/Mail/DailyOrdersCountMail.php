<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\SiteSetting;

class DailyOrdersCountMail extends Mailable
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
            subject: 'Daily Orders Received Count Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $website_logo = SiteSetting::pluck('website_logo')->first();

        $logoPath = public_path("uploads/logos/{$website_logo}");
        
        return new Content(
            view: 'email.daily-orders-received-count', 
            with: [
                'Get_website_logo' => $logoPath, 
                'Get_website_name' => $this->data['Get_website_name'],
                'orders_today' =>  $this->data['orders_today'],
                'orders_count' =>  $this->data['orders_count'],
                'dukkan_orders_count' =>  $this->data['dukkan_orders_count'],
                'woocommerce_orders_count' => $this->data['woocommerce_orders_count'],
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