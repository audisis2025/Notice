<?php
/**
 * Nombre de la clase           : ChatController
 * Descripción de la clase      : Controlador que gestiona la visualización de
 *                                conversaciones de chat
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 1.0
 * Fecha de mantenimiento       : 
 * Folio de mantenimiento       : 
 * Tipo de mantenimiento        :
 * Descripción del mantenimiento: 
 * Responsable                  : 
 * Revisor                      : 
 */
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