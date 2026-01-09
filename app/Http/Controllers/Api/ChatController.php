<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Chat;
use App\Services\ChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * ChatController
 * 
 * Controlador API para gestionar chats desde la app móvil.
 *
 * @package App\Http\Controllers\Api
 */
class ChatController extends Controller
{
    /**
     * Instancia del servicio de chat.
     *
     * @var ChatService
     */
    protected ChatService $chatService;

    /**
     * Constructor del controlador.
     *
     * @param ChatService $chatService
     */
    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * Obtiene o crea un chat para una orden.
     *
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Order $order)
    {
        try {
            $chat = $this->chatService->getOrCreateChat($order, $request->user());
            $chat->load('messages.sender');

            // Marcar mensajes como leídos
            $this->chatService->markMessagesAsRead($chat, $request->user());

            return response()->json([
                'success' => true,
                'data' => $chat,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 403);
        }
    }

    /**
     * Envía un mensaje en el chat.
     *
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $chat = $this->chatService->getOrCreateChat($order, $request->user());
            $message = $this->chatService->sendMessage($chat, $request->user(), $request->message);

            return response()->json([
                'success' => true,
                'message' => 'Mensaje enviado',
                'data' => $message,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 403);
        }
    }

    /**
     * Marca mensajes del chat como leídos.
     *
     * @param Request $request
     * @param Chat $chat
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Request $request, Chat $chat)
    {
        try {
            $this->chatService->markMessagesAsRead($chat, $request->user());

            return response()->json([
                'success' => true,
                'message' => 'Mensajes marcados como leídos',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}