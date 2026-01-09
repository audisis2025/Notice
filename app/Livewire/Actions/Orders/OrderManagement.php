<?php

namespace App\Livewire\Orders;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\Order;
use App\Services\OrderService;

class OrderManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $showQRModal = false;
    public $selectedOrder = null;
    public $qrType = 'association'; // association o delivery

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

        return view('livewire.orders.order-management', [
            'orders' => $orders
        ]);
    }

    public function markAsPaid($orderId)
    {
        try {
            $orderService = app(OrderService::class);
            $order = Order::findOrFail($orderId);
            $orderService->markAsPaid($order);

            $this->dispatch('success', message: 'Orden marcada como pagada. QR generado.');
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function markAsReady($orderId)
    {
        try {
            $orderService = app(OrderService::class);
            $order = Order::findOrFail($orderId);
            $orderService->markAsReady($order);

            $this->dispatch('success', message: 'Orden lista. Usuario notificado.');
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function showQR($orderId, $type = 'association')
    {
        $this->selectedOrder = Order::findOrFail($orderId);
        $this->qrType = $type;
        $this->showQRModal = true;
    }

    public function closeQRModal()
    {
        $this->showQRModal = false;
        $this->selectedOrder = null;
    }

    public function confirmCancel($orderId)
    {
        $this->dispatch('confirm-cancel-order', orderId: $orderId);
    }

    #[On('order-cancel-confirmed')]
    public function cancelOrder($orderId, $reason)
    {
        try {
            $orderService = app(OrderService::class);
            $order = Order::findOrFail($orderId);
            $orderService->cancelOrder($order, $reason);

            $this->dispatch('success', message: 'Orden cancelada exitosamente.');
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error: ' . $e->getMessage());
        }
    }
}