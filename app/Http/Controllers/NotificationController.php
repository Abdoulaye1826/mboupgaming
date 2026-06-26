<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use Illuminate\Http\RedirectResponse;

class NotificationController extends Controller
{
    public function read(AppNotification $notification): RedirectResponse
    {
        abort_unless($notification->user_id === auth()->id(), 403);

        if (!$notification->isRead()) {
            $notification->markAsRead();
        }

        $data = $notification->data ?? [];
        if (($data['out_of_stock_count'] ?? 0) > 0) {
            return redirect()->route('products.index', ['stock_status' => 'out']);
        }
        if (($data['low_stock_count'] ?? 0) > 0) {
            return redirect()->route('products.index', ['stock_status' => 'low']);
        }

        return back();
    }

    public function readAll(): RedirectResponse
    {
        AppNotification::forUser(auth()->id())->unread()->update(['read_at' => now()]);

        return back();
    }
}
