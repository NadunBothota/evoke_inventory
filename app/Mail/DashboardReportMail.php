<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Collection;

class DashboardReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $attachmentData;
    public $fileName;
    public $mineType;
    public $inventoryData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($attachmentData, $fileName, $mineType, Collection $inventoryData)
    {
        $this->attachmentData = $attachmentData;
        $this->fileName = $fileName;
        $this->mineType = $mineType;
        $this->inventoryData = $inventoryData;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Evoke Inventory Summary Report',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.inventory_report',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [
            Attachment::fromData(fn () => $this->attachmentData, $this->fileName)
                ->withMime($this->mineType),
        ];
    }
}
