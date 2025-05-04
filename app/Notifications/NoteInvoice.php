<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

class NoteInvoice extends Notification implements ShouldQueue
{
    use Queueable;
    private  $invoices;
    /**
     * Create a new notification instance.
     */
    public function __construct(Invoice $invoices)
    {
        $this->invoices = $invoices;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('فاتورة رقم: ' . $this->invoices['id'])
            ->markdown('emails.invoice_notification', [
                'invoices' => $this->invoices,
                'user' => $notifiable,
                'data' => [
                    'invoice_id' => $this->invoices['id'],
                    'invoice_Date' => $this->invoices['invoice_Date'],
                    'user_send' => Auth::user()->name,
                    'section_name' => $this->invoices['section_name'],
                    'product_name' => $this->invoices['product_name'],
                    'Status' => $this->invoices['Status'],
                    'Total' => $this->invoices['Total'],
                ]
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */

    public function toDatabase()
    {
        return [

                'invoice_id' => $this->invoices['id'],
                'product_name' => $this->invoices['product_name'],
                'Total' => $this->invoices['Total'],
                'user_send' => Auth::user()->name
            
        ];
    }
}
