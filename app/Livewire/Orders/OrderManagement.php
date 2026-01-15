<?php

/**
 * Nombre de la clase           : OrderManagement
 * Descripción de la clase      : Componente Livewire que gestiona la interfaz
 *                                interactiva para administrar órdenes
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 2.0
 * Fecha de mantenimiento       : 14/01/2026
 * Folio de mantenimiento       : 1
 * Tipo de mantenimiento        : Perfectivo
 * Descripción del mantenimiento: Eliminación de Services - Lógica usando métodos del modelo
 *                                Corrección de error toJSON en Livewire 3
 * Responsable                  : Jesús Núñez
 * Revisor                      : Jesús Núñez
 */

namespace App\Livewire\Orders;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $showQRModal = false;
    public $qrType = 'association'; // association o delivery

    // ✅ CORRECCIÓN: Guardar solo el ID, no el modelo completo
    public ?int $selectedOrderId = null;

    /**
     * Renderiza el componente con la lista de órdenes.
     */
    public function render()
    {
        $business = auth()->user()->business;

        $orders = $business->orders()
            ->with('user')
            ->when($this->search, function ($query) {
                $query->where('order_number', 'like', "%{$this->search}%");
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(15);

        // ✅ Obtener la orden seleccionada solo cuando se necesita
        $selectedOrder = $this->selectedOrderId
            ? Order::find($this->selectedOrderId)
            : null;

        return view('livewire.orders.order-management', [
            'orders' => $orders,
            'selectedOrder' => $selectedOrder,
        ]);
    }

    /**
     * Marca una orden como pagada.
     */
    public function markAsPaid($orderId)
    {
        DB::beginTransaction();

        try {
            $order = Order::findOrFail($orderId);

            // ✅ Usar método del modelo en lugar de Service
            $order->markAsPaid();

            DB::commit();

            $this->dispatch('success', message: 'Orden marcada como pagada. QR generado.');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('error', message: 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Marca una orden como lista para entrega.
     */
    public function markAsReady($orderId)
    {
        DB::beginTransaction();

        try {
            $order = Order::findOrFail($orderId);

            // ✅ Usar método del modelo en lugar de Service
            $order->markAsReady();

            DB::commit();

            $this->dispatch('success', message: 'Orden lista. Usuario notificado.');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('error', message: 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el modal con el código QR.
     */
    public function showQR($orderId, $type = 'association')
    {
        // ✅ CORRECCIÓN: Guardar solo el ID, no el modelo
        $this->selectedOrderId = $orderId;
        $this->qrType = $type;
        $this->showQRModal = true;
    }

    /**
     * Cierra el modal de QR.
     */
    public function closeQRModal()
    {
        $this->showQRModal = false;
        $this->selectedOrderId = null;
    }

    /**
     * Confirma la cancelación de una orden.
     */
    public function confirmCancel($orderId)
    {
        $this->dispatch('confirm-cancel-order', orderId: $orderId);
    }

    /**
     * Cancela una orden después de confirmar.
     */
    #[On('order-cancel-confirmed')]
    public function cancelOrder($orderId, $reason)
    {
        DB::beginTransaction();

        try {
            $order = Order::findOrFail($orderId);

            // ✅ Usar método del modelo en lugar de Service
            $order->cancelOrder($reason);

            DB::commit();

            $this->dispatch('success', message: 'Orden cancelada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('error', message: 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Resetea la paginación cuando cambia la búsqueda.
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Resetea la paginación cuando cambia el filtro de estado.
     */
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }
}
