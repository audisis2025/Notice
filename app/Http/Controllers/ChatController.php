<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function show(Order $order)
    {
        // Validaciones de acceso
        $user = Auth::user();
        
        if ($user->isBusinessAdministrator()) {
            if ($order->business_id !== $user->business->id) {
                abort(403);
            }
        }

        if (!$order->chat_enabled) {
            return redirect()->back()
                ->with('warning', 'El chat no está habilitado.');
        }

        return view('chat.show', ['order' => $order]);
    }
}