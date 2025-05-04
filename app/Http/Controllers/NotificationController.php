<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{

    public function read($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);

        // تعليم كمقروء
        if ($notification->unread()) {
            $notification->markAsRead();
        }

        // تحويل المستخدم إلى صفحة الفاتورة
        $invoiceId = $notification->data['invoice_id'] ?? null;

        if ($invoiceId) {
            return redirect()->route('invoices.details', [$invoiceId]);
        }

        return redirect()->back()->with('error', 'Invoice not found.');
    }

    public function markAll()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
