<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Services\OrderService;
use App\Services\OrderReminderService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;


/**
 * OrderController
 * 
 * Controlador para gestionar órdenes del negocio.
 *
 * @package App\Http\Controllers
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
     * Instancia del servicio de recordatorios.
     *
     * @var OrderReminderService
     */
    protected OrderReminderService $reminderService;

    /**
     * Constructor del controlador.
     *
     * @param OrderService $orderService
     * @param OrderReminderService $reminderService
     */
    public function __construct(
        OrderService $orderService,
        OrderReminderService $reminderService
    ) {
        $this->middleware('auth');
        $this->orderService = $orderService;
        $this->reminderService = $reminderService;
    }

    /**
     * Muestra el listado de órdenes del negocio.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $business = Auth::user()->business;
        $query = $business->orders()->with('user');

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $orders = $query->latest()->paginate(15);

        return view('orders.index', compact('orders', 'business'));
    }

    /**
     * Muestra el formulario para crear una nueva orden.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $business = Auth::user()->business;

        return view('orders.create', compact('business'));
    }

    /**
     * Almacena una nueva orden en la base de datos.
     *
     * @param StoreOrderRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreOrderRequest $request)
    {
        try {
            $business = Auth::user()->business;
            $order = $this->orderService->createOrder($business, $request->validated());

            return redirect()->route('orders.show', $order)
                ->with('success', 'Orden creada exitosamente.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al crear la orden: ' . $e->getMessage());
        }
    }

    /**
     * Muestra los detalles de una orden específica.
     *
     * @param Order $order
     * @return \Illuminate\View\View
     */
    public function show(Order $order)
    {
        $order->load('business', 'user', 'reminders', 'rating');

        return view('orders.show', compact('order'));
    }

    /**
     * Marca una orden como pagada y genera QR.
     *
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsPaid(Order $order)
    {
        try {
            $this->orderService->markAsPaid($order);

            return back()->with('success', 'Orden marcada como pagada. QR generado.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Marca una orden como lista.
     *
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsReady(Order $order)
    {
        try {
            $this->orderService->markAsReady($order);

            return back()->with('success', 'Orden marcada como lista. Usuario notificado.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Cancela una orden.
     *
     * @param UpdateOrderRequest $request
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(UpdateOrderRequest $request, Order $order)
    {
        try {
            $this->orderService->cancelOrder($order, $request->cancellation_reason);

            return back()->with('success', 'Orden cancelada. Usuario notificado.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Programa recordatorios para una orden.
     *
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function scheduleReminders(Request $request, Order $order)
    {
        $request->validate([
            'reminder_minutes' => 'required|array',
            'reminder_minutes.*' => 'integer|min:1',
        ]);

        try {
            $this->reminderService->scheduleReminders($order, $request->reminder_minutes);

            return back()->with('success', 'Recordatorios programados exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}