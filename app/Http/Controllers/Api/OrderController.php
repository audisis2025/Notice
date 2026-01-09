<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * OrderController
 * 
 * Controlador API para gestionar órdenes desde la app móvil.
 *
 * @package App\Http\Controllers\Api
 */
class OrderController extends Controller
{
    /**
     * Instancia del servicio de órdenes.
     *
     * @var OrderService
     */
    protected OrderService $orderService;

    /**
     * Constructor del controlador.
     *
     * @param OrderService $orderService
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Lista las órdenes del usuario autenticado.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $orders = $request->user()
            ->orders()
            ->with('business')
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    /**
     * Muestra los detalles de una orden específica.
     *
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Order $order)
    {
        // Verificar que la orden pertenezca al usuario
        if ($order->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes acceso a esta orden',
            ], 403);
        }

        $order->load('business', 'rating');

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    /**
     * Escanea un código QR para asociar orden con usuario.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scanQR(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qr_data' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Decodificar datos del QR
            $qrData = json_decode($request->qr_data, true);

            if (!$qrData || !isset($qrData['order_id']) || $qrData['type'] !== 'association') {
                return response()->json([
                    'success' => false,
                    'message' => 'Código QR inválido',
                ], 422);
            }

            $order = Order::find($qrData['order_id']);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Orden no encontrada',
                ], 404);
            }

            if ($order->status !== 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta orden no está pagada',
                ], 422);
            }

            if ($order->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta orden ya está asociada a otro usuario',
                ], 422);
            }

            // Asociar orden con usuario
            $order = $this->orderService->associateOrderWithUser($order, $request->user());

            return response()->json([
                'success' => true,
                'message' => 'Orden asociada exitosamente',
                'data' => $order,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar QR',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Confirma la entrega de una orden mediante QR.
     *
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmDelivery(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'qr_data' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Decodificar datos del QR
            $qrData = json_decode($request->qr_data, true);

            if (!$qrData || $qrData['order_id'] !== $order->id || $qrData['type'] !== 'delivery') {
                return response()->json([
                    'success' => false,
                    'message' => 'Código QR inválido',
                ], 422);
            }

            // Confirmar entrega
            $order = $this->orderService->confirmDelivery($order, $request->user());

            return response()->json([
                'success' => true,
                'message' => 'Entrega confirmada exitosamente',
                'data' => $order,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}