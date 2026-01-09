<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\BusinessPackageController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Chat\ChatComponent;

/**
 * ============================================================================
 * RUTAS PÚBLICAS
 * ============================================================================
 */

// Página principal
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

/**
 * ============================================================================
 * RUTAS PROTEGIDAS - REQUIEREN AUTENTICACIÓN
 * ============================================================================
 */

Route::middleware(['auth', 'verified'])->group(function () {

    /**
     * ========================================================================
     * DASHBOARD
     * ========================================================================
     */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /**
     * ========================================================================
     * RUTAS DE SUPER ADMINISTRADOR
     * ========================================================================
     */
    Route::middleware(['role:SuperAdministrator'])->group(function () {

        // Gestión de usuarios
        Route::resource('users', UserController::class);
        Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
            ->name('users.toggle-status');

        // Gestión de paquetes
        Route::resource('packages', PackageController::class);
        Route::post('packages/{package}/toggle-status', [PackageController::class, 'toggleStatus'])
            ->name('packages.toggle-status');

        // Gestión de cupones
        Route::resource('coupons', CouponController::class);
        Route::post('coupons/validate', [CouponController::class, 'validate'])
            ->name('coupons.validate');

        // Gestión de negocios (vista completa)
        Route::get('businesses', [BusinessController::class, 'index'])
            ->name('businesses.index');
        Route::get('businesses/{business}', [BusinessController::class, 'show'])
            ->name('businesses.show');
        Route::post('businesses/{business}/suspend', [BusinessController::class, 'suspend'])
            ->name('businesses.suspend');
        Route::post('businesses/{business}/reactivate', [BusinessController::class, 'reactivate'])
            ->name('businesses.reactivate');
    });

    /**
     * ========================================================================
     * RUTAS DE ADMINISTRADOR DE NEGOCIO
     * ========================================================================
     */
    Route::middleware(['role:BusinessAdministrator'])->group(function () {

        // Registro y gestión de negocio
        Route::get('business/create', [BusinessController::class, 'create'])
            ->name('business.create');
        Route::post('business', [BusinessController::class, 'store'])
            ->name('business.store');

        Route::middleware(['business.owner'])->group(function () {
            Route::get('business/edit', [BusinessController::class, 'edit'])
                ->name('business.edit');
            Route::put('business', [BusinessController::class, 'update'])
                ->name('business.update');
            Route::post('business/toggle-ratings', [BusinessController::class, 'toggleRatings'])
                ->name('business.toggle-ratings');
            Route::post('business/update-delivery-period', [BusinessController::class, 'updateDeliveryPeriod'])
                ->name('business.update-delivery-period');

            // Calificaciones del negocio
            Route::get('business/ratings', [RatingController::class, 'index'])
                ->name('business.ratings');

            /**
             * ================================================================
             * CONTRATACIÓN DE PAQUETES
             * ================================================================
             */
            Route::get('packages', [BusinessPackageController::class, 'index'])
                ->name('packages.available');
            Route::get('packages/{package}/contract', [BusinessPackageController::class, 'show'])
                ->name('packages.show');
            Route::post('packages/contract', [BusinessPackageController::class, 'contract'])
                ->name('packages.contract');
            Route::get('packages/history', [BusinessPackageController::class, 'history'])
                ->name('packages.history');

            /**
             * ================================================================
             * GESTIÓN DE ÓRDENES - REQUIERE PAQUETE ACTIVO Y NEGOCIO ACTIVO
             * ================================================================
             */
            Route::middleware(['package.active', 'business.active'])->group(function () {
                
                // CRUD de órdenes
                Route::resource('orders', OrderController::class);
                
                // Cambios de estado de orden
                Route::post('orders/{order}/mark-paid', [OrderController::class, 'markAsPaid'])
                    ->name('orders.mark-paid');
                Route::post('orders/{order}/mark-ready', [OrderController::class, 'markAsReady'])
                    ->name('orders.mark-ready');
                Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])
                    ->name('orders.cancel');
                
                // Recordatorios de órdenes
                Route::post('orders/{order}/schedule-reminders', [OrderController::class, 'scheduleReminders'])
                    ->name('orders.schedule-reminders');
                
                // Descargar QR
                Route::get('orders/{order}/qr/{type}', [OrderController::class, 'downloadQR'])
                    ->name('orders.download-qr');

                /**
                 * ========================================================
                 * REPORTES Y ESTADÍSTICAS - REQUIERE CARACTERÍSTICA
                 * ========================================================
                 */
                Route::middleware(['package.feature:reports'])->group(function () {
                    Route::get('reports', [ReportController::class, 'index'])
                        ->name('reports.index');
                    Route::post('reports/generate', [ReportController::class, 'generate'])
                        ->name('reports.generate');
                    Route::post('reports/export', [ReportController::class, 'export'])
                        ->name('reports.export');
                });
            });
        });
    });

    /**
     * ========================================================================
     * CHAT - LIVEWIRE COMPONENT
     * ========================================================================
     */
    Route::get('chat/{order}', \App\Livewire\Chat\ChatComponent::class)
        ->name('chat.show')
        ->middleware(['auth']);
});