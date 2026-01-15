<?php

/**
 * Nombre de la clase           : OrderController
 * Descripción de la clase      : Controlador que gestiona las operaciones CRUD
 *                                de órdenes sin usar Services ni Livewire
 * Versión                      : 3.0
 * Fecha de mantenimiento       : 14/01/2026
 * Folio de mantenimiento       : 4
 * Tipo de mantenimiento        : Correctivo
 * Descripción del mantenimiento: Eliminación de Livewire, actualización para
 *                                vista Blade tradicional con filtros y estadísticas
 */

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Muestra el listado de órdenes con filtros y estadísticas.
     */
    public function index(Request $request)
    {
        $business = Auth::user()->business;

        if (!$business) {
            return redirect()->route('business.create')
                ->with('error', 'Primero debes registrar tu negocio.');
        }

        // Query base
        $query = Order::where('business_id', $business->id)
            ->with('user');

        // Filtro por búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('order_number', 'like', "%{$search}%");
        }

        // Filtro por estado
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Obtener órdenes paginadas
        $orders = $query->latest()->paginate(15);

        // Estadísticas por estado
        $totalOrders = Order::where('business_id', $business->id)->count();
        $pendingCount = Order::where('business_id', $business->id)->where('status', 'pending')->count();
        $paidCount = Order::where('business_id', $business->id)->where('status', 'paid')->count();
        $readyCount = Order::where('business_id', $business->id)->where('status', 'ready')->count();
        $deliveredCount = Order::where('business_id', $business->id)->where('status', 'delivered')->count();

        return view('orders.index', compact(
            'orders',
            'totalOrders',
            'pendingCount',
            'paidCount',
            'readyCount',
            'deliveredCount'
        ));
    }

    /**
     * Muestra el formulario para crear una nueva orden.
     */
    public function create()
    {
        $business = Auth::user()->business;

        if (!$business) {
            return redirect()->route('business.create')
                ->with('error', 'Primero debes registrar tu negocio.');
        }

        return view('orders.create');
    }

    /**
     * Almacena una nueva orden en la base de datos.
     */
    public function store(StoreOrderRequest $request)
    {
        Log::info('=== INICIO CREACIÓN DE ORDEN ===');
        Log::info('Datos recibidos:', $request->all());

        DB::beginTransaction();

        try {
            $business = Auth::user()->business;

            if (!$business) {
                return redirect()->route('business.create')
                    ->with('error', 'Primero debes registrar tu negocio.');
            }

            // Crear orden usando el método del modelo
            $order = Order::createOrder($business, $request->validated());

            DB::commit();

            Log::info('Orden creada exitosamente', [
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ]);

            Log::info('=== FIN CREACIÓN DE ORDEN (ÉXITO) ===');

            return redirect()->route('orders.show-qr', $order)
                ->with('success', '¡Orden creada exitosamente!');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('=== ERROR EN CREACIÓN DE ORDEN ===');
            Log::error('Mensaje:', ['error' => $e->getMessage()]);
            Log::error('Archivo:', ['file' => $e->getFile()]);
            Log::error('Línea:', ['line' => $e->getLine()]);

            return back()->withInput()
                ->with('error', 'Error al crear la orden: ' . $e->getMessage());
        }
    }

    /**
     * Muestra los detalles de una orden específica.
     */
    public function show(Order $order)
    {
        // Verificar que la orden pertenezca al negocio del usuario autenticado
        if ($order->business_id !== Auth::user()->business->id) {
            abort(403, 'No tienes permiso para ver esta orden.');
        }

        $order->load('user', 'business');

        return view('orders.show', compact('order'));
    }

    /**
     * Marca una orden como pagada.
     */
    public function markAsPaid(Order $order)
    {
        // Verificar que la orden pertenezca al negocio del usuario autenticado
        if ($order->business_id !== Auth::user()->business->id) {
            abort(403, 'No tienes permiso para modificar esta orden.');
        }

        DB::beginTransaction();

        try {
            $order->markAsPaid();

            DB::commit();

            return back()->with('success', 'Orden marcada como pagada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al marcar orden como pagada', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Error al marcar la orden como pagada: ' . $e->getMessage());
        }
    }

    /**
     * Marca una orden como lista para entrega.
     */
    public function markAsReady(Order $order)
    {
        // Verificar que la orden pertenezca al negocio del usuario autenticado
        if ($order->business_id !== Auth::user()->business->id) {
            abort(403, 'No tienes permiso para modificar esta orden.');
        }

        DB::beginTransaction();

        try {
            $order->markAsReady();

            DB::commit();

            return back()->with('success', 'Orden marcada como lista para entrega.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al marcar orden como lista', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Error al marcar la orden como lista: ' . $e->getMessage());
        }
    }

    /**
     * Cancela una orden.
     */
    public function cancel(Request $request, Order $order)
    {
        // Verificar que la orden pertenezca al negocio del usuario autenticado
        if ($order->business_id !== Auth::user()->business->id) {
            abort(403, 'No tienes permiso para modificar esta orden.');
        }

        $request->validate([
            'reason' => 'required|string|min:10',
        ]);

        DB::beginTransaction();

        try {
            $order->cancelOrder($request->reason);

            DB::commit();

            return redirect()->route('orders.index')
                ->with('success', 'Orden cancelada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al cancelar orden', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Error al cancelar la orden: ' . $e->getMessage());
        }
    }

    /**
     * Programa recordatorios para una orden.
     */
    public function scheduleReminders(Request $request, Order $order)
    {
        // Verificar que la orden pertenezca al negocio del usuario autenticado
        if ($order->business_id !== Auth::user()->business->id) {
            abort(403, 'No tienes permiso para modificar esta orden.');
        }

        $request->validate([
            'reminder_minutes' => 'required|array|min:1',
            'reminder_minutes.*' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            \App\Models\OrderReminder::scheduleReminders($order, $request->reminder_minutes);

            DB::commit();

            return back()->with('success', 'Recordatorios programados exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al programar recordatorios', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Error al programar recordatorios: ' . $e->getMessage());
        }
    }

    public function changeStatus(Request $request, Order $order)
    {
        // Verificar que la orden pertenezca al negocio del usuario autenticado
        $business = Auth::user()->business;

        if (!$business || $order->business_id !== $business->id) {
            abort(403, 'No tienes permiso para modificar esta orden.');
        }

        // Validar el nuevo estado
        $request->validate([
            'status' => 'required|in:pending,paid,ready,delivered,cancelled',
        ]);

        DB::beginTransaction();

        try {
            // Usar el método del modelo
            $order->changeStatus($request->status);

            DB::commit();

            return back()->with('success', 'Estado de la orden actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error al cambiar estado de orden', [
                'order_id' => $order->id,
                'new_status' => $request->status,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Error al cambiar el estado: ' . $e->getMessage());
        }
    }

    /**
     * Asocia una orden a un usuario mediante token del QR
     */
    public function associate(Request $request, $token)
    {
        try {
            // Buscar orden por token
            $order = Order::where('association_token', $token)->firstOrFail();

            // Verificar que no esté ya asociada
            if ($order->associated_at) {
                return view('orders.already-associated', [
                    'order' => $order,
                    'message' => 'Esta orden ya fue asociada previamente.'
                ]);
            }

            // ✅ CAPTURAR INFORMACIÓN DEL DISPOSITIVO
            $deviceInfo = [
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
                'device' => $this->getDeviceType($request),
            ];

            // ✅ ASOCIAR ORDEN INMEDIATAMENTE (sin login)
            $order->update([
                'associated_at' => now(),
                // Guardar info del dispositivo temporalmente en description si lo necesitas
                'description' => ($order->description ?? '') . "\n[Escaneado desde: {$deviceInfo['device']} - IP: {$deviceInfo['ip']}]"
            ]);

            \Log::info('Orden asociada mediante QR', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'device_info' => $deviceInfo,
                'associated_at' => $order->associated_at
            ]);

            // ✅ MOSTRAR PÁGINA DE CONFIRMACIÓN
            return view('orders.qr-scanned', [
                'order' => $order,
                'business' => $order->business
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::warning('Token de orden inválido', ['token' => $token]);

            return view('orders.invalid-qr', [
                'message' => 'El código QR no es válido o ya expiró.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al asociar orden', [
                'token' => $token,
                'error' => $e->getMessage()
            ]);

            return view('orders.error-qr', [
                'message' => 'Hubo un error al procesar el código QR. Intenta de nuevo.'
            ]);
        }
    }

    /**
     * Helper para detectar tipo de dispositivo
     */
    private function getDeviceType(Request $request): string
    {
        $userAgent = strtolower($request->userAgent());

        if (str_contains($userAgent, 'iphone') || str_contains($userAgent, 'ipad')) {
            return 'iOS';
        }

        if (str_contains($userAgent, 'android')) {
            return 'Android';
        }

        if (str_contains($userAgent, 'mobile')) {
            return 'Mobile';
        }

        return 'Desktop';
    }

    /**
     * Muestra el QR para escanear
     */
    public function showQR(Order $order)
    {
        if ($order->business_id !== Auth::user()->business->id) {
            abort(403, 'No tienes permiso para ver esta orden.');
        }

        return view('orders.show-qr', compact('order'));
    }

    /**
     * Verifica si el QR fue escaneado (AJAX)
     */
    public function checkScanned(Order $order)
    {
        if ($order->business_id !== Auth::user()->business->id) {
            abort(403);
        }

        return response()->json([
            'scanned' => !is_null($order->user_id),
            'user_name' => $order->user ? $order->user->name : null,
            'associated_at' => $order->associated_at ? $order->associated_at->format('H:i:s') : null,
        ]);
    }
}
