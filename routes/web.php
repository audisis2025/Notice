<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\BusinessPackageController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ChatController;

// Ruta raíz
Route::get('/', function () {
    $packages = \App\Models\Package::where('is_active', true)
        ->orderBy('price')
        ->get();
    return view('welcome', compact('packages'));
})->name('home');


// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {

    // Dashboard con redirección según rol
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ===================================================================
    // RUTAS PARA SUPER ADMINISTRADOR
    // ===================================================================
    Route::middleware(['role:SuperAdministrator'])->group(function () {

        // Gestión de Usuarios
        Route::resource('users', UserController::class);
        Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
            ->name('users.toggle-status');

        // Gestión de Paquetes
        Route::resource('packages', PackageController::class);
        Route::post('packages/{package}/toggle-status', [PackageController::class, 'toggleStatus'])
            ->name('packages.toggle-status');

        // Gestión de Cupones
        Route::resource('coupons', CouponController::class);
        Route::post('coupons/validate', [CouponController::class, 'validate'])
            ->name('coupons.validate');
        Route::post('coupons/{coupon}/toggle-status', [CouponController::class, 'toggleStatus'])
            ->name('coupons.toggle-status');

        // Gestión de Negocios
        Route::get('businesses', [BusinessController::class, 'index'])->name('businesses.index');
        Route::get('businesses/{business}', [BusinessController::class, 'show'])->name('businesses.show');
        Route::post('businesses/{business}/suspend', [BusinessController::class, 'suspend'])
            ->name('businesses.suspend');
        Route::post('businesses/{business}/reactivate', [BusinessController::class, 'reactivate'])
            ->name('businesses.reactivate');
    });

    // ===================================================================
    // RUTAS PARA ADMINISTRADOR DE NEGOCIO
    // ===================================================================
    Route::middleware(['role:BusinessAdministrator'])->group(function () {

        // Registro y edición del negocio
        Route::get('business/create', [BusinessController::class, 'create'])->name('business.create');
        Route::post('business', [BusinessController::class, 'store'])->name('business.store');
        Route::get('business/edit', [BusinessController::class, 'edit'])->name('business.edit');
        Route::put('business', [BusinessController::class, 'update'])->name('business.update');
        Route::post('business/toggle-ratings', [BusinessController::class, 'toggleRatings'])
            ->name('business.toggle-ratings');
        Route::post('business/update-delivery-period', [BusinessController::class, 'updateDeliveryPeriod'])
            ->name('business.update-delivery-period');

        // Paquetes disponibles para contratar
        Route::get('packages/available', [BusinessPackageController::class, 'index'])
            ->name('packages.available');
        Route::get('packages/{package}/contract', [BusinessPackageController::class, 'show'])
            ->name('packages.show');
        Route::post('packages/contract', [BusinessPackageController::class, 'contract'])
            ->name('packages.contract');
        Route::get('packages/history', [BusinessPackageController::class, 'history'])
            ->name('packages.history');

        // Órdenes (requiere paquete activo y negocio activo)
        Route::middleware(['package.active', 'business.active'])->group(function () {
            Route::resource('orders', OrderController::class);
            Route::post('orders/{order}/mark-paid', [OrderController::class, 'markAsPaid'])
                ->name('orders.mark-paid');
            Route::post('orders/{order}/mark-ready', [OrderController::class, 'markAsReady'])
                ->name('orders.mark-ready');
            Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])
                ->name('orders.cancel');
            Route::post('orders/{order}/schedule-reminders', [OrderController::class, 'scheduleReminders'])
                ->name('orders.schedule-reminders');
            Route::get('orders/{order}/download-qr/{type}', [OrderController::class, 'downloadQR'])
                ->name('orders.download-qr');
        });

        // Calificaciones del negocio
        Route::get('business/ratings', [RatingController::class, 'index'])
            ->name('business.ratings');

        // Reportes (requiere característica de reportes en el paquete)
        Route::middleware(['package.feature:reports'])->group(function () {
            Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
            Route::post('reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
            Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
        });

        // Chat
        Route::get('chat/{order}', [ChatController::class, 'show'])->name('chat.show');
    });

        Route::middleware(['has.business'])->group(function () {
        
        // Registro de negocio (PRIMER ACCESO después de registrarse)
        Route::get('business/create', [BusinessController::class, 'create'])->name('business.create');
        Route::post('business', [BusinessController::class, 'store'])->name('business.store');
        
        // Ver paquetes disponibles (DESPUÉS de tener negocio)
        Route::get('packages/available', [PackageController::class, 'available'])->name('packages.available');
        Route::post('packages/{package}/subscribe', [PackageController::class, 'subscribe'])->name('packages.subscribe');
    });
});
