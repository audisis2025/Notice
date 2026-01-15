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

            return redirect()->route('orders.index')
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
}
