<?php

namespace App\Mail;

use App\Models\Contract;
use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContractSigningMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Contract $contract,
        public Tenant $representative,
        public string $signingUrl
    ) {}

    /**
     * Tiêu đề email.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mời ký hợp đồng thuê nhà - QuảnLýNhà',
        );
    }

    /**
     * Nội dung email.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contract-signing',
        );
    }

    /**
     * File đính kèm.
     */
    public function attachments(): array
    {
        return [];
    }
}
