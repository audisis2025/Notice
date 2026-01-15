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

// ===================================================================
// RUTAS DE AUTENTICACIÓN (Blade tradicional, NO Livewire)
// ===================================================================

Route::middleware('guest')->group(function () {
    // Login
    Route::get('login', function () {
        return view('livewire.auth.login');
    })->name('login');

    Route::post('login', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // ✅ CORREGIDO: Usar traducción en vez de texto hardcodeado
        return back()->withErrors([
            'email' => __('auth.failed'),
        ])->onlyInput('email');
    })->name('login.store');

    // Register
    Route::get('register', function () {
        return view('livewire.auth.register');
    })->name('register');

    Route::post('register', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'BusinessAdministrator',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        Auth::login($user);

        return redirect('/dashboard')->with('success', '¡Cuenta creada exitosamente!');
    })->name('register.store');

    // Forgot Password
    Route::get('forgot-password', function () {
        return view('livewire.auth.forgot-password');
    })->name('password.request');

    Route::post('forgot-password', function (\Illuminate\Http\Request $request) {
        $request->validate(['email' => 'required|email']);

        $status = \Illuminate\Support\Facades\Password::sendResetLink(
            $request->only('email')
        );

        return $status === \Illuminate\Support\Facades\Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    })->name('password.email');

    // Reset Password
    Route::get('reset-password/{token}', function ($token) {
        return view('livewire.auth.reset-password', ['token' => $token]);
    })->name('password.reset');

    Route::post('reset-password', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = \Illuminate\Support\Facades\Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => \Illuminate\Support\Facades\Hash::make($password)
                ])->setRememberToken(\Illuminate\Support\Str::random(60));

                $user->save();
            }
        );

        return $status === \Illuminate\Support\Facades\Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    })->name('password.update');

    // Two Factor Challenge
    Route::get('two-factor-challenge', function () {
        return view('livewire.auth.two-factor-challenge');
    })->name('two-factor.login');
});

Route::middleware('auth')->group(function () {
    // Logout
    Route::post('logout', function (\Illuminate\Http\Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    // Verify Email
    Route::get('verify-email', function () {
        return view('livewire.auth.verify-email');
    })->name('verification.notice');

    // Confirm Password
    Route::get('confirm-password', function () {
        return view('livewire.auth.confirm-password');
    })->name('password.confirm');
});

// ===================================================================
// RUTAS PÚBLICAS
// ===================================================================

// Ruta raíz
Route::get('/', function () {
    $packages = \App\Models\Package::where('is_active', true)
        ->orderBy('price')
        ->limit(3)
        ->get();
    return view('welcome', compact('packages'));
})->name('home');

// ✅ RUTA PÚBLICA - Asociar orden mediante QR (fuera de middleware auth)
Route::get('orders/associate/{token}', [OrderController::class, 'associate'])
    ->name('orders.associate');

// ===================================================================
// RUTAS PROTEGIDAS POR AUTENTICACIÓN
// ===================================================================
Route::middleware(['auth'])->group(function () {

    // Dashboard con redirección según rol
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Selección de paquete
    Route::get('/select-package', function () {
        $packages = \App\Models\Package::where('is_active', true)
            ->orderBy('price')
            ->get();
        $business = auth()->user()->business;
        $currentPackage = $business?->activePackage;

        return view('dashboard.select-package', compact('packages', 'currentPackage'));
    })->name('select.package');

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

        // Gestión de Negocios (Solo ver)
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

        // ✅ REGISTRO DE NEGOCIO (PRIMER ACCESO)
        Route::get('business/create', [BusinessController::class, 'create'])->name('business.create');
        Route::post('business', [BusinessController::class, 'store'])->name('business.store');

        // Edición del negocio
        Route::get('business/edit', [BusinessController::class, 'edit'])->name('business.edit');
        Route::put('business', [BusinessController::class, 'update'])->name('business.update');
        Route::post('business/toggle-ratings', [BusinessController::class, 'toggleRatings'])
            ->name('business.toggle-ratings');
        Route::post('business/update-delivery-period', [BusinessController::class, 'updateDeliveryPeriod'])
            ->name('business.update-delivery-period');

        // ✅ PAQUETES DISPONIBLES (DESPUÉS DE TENER NEGOCIO)
        Route::get('packages/available', [PackageController::class, 'available'])->name('packages.available');
        Route::post('packages/{package}/subscribe', [PackageController::class, 'subscribe'])->name('packages.subscribe');

        // Historial de paquetes contratados
        Route::get('business-packages/history', [BusinessPackageController::class, 'history'])
            ->name('business-packages.history');

        // Órdenes (requiere paquete activo y negocio activo)
        Route::middleware(['package.active', 'business.active'])->group(function () {

            // ✅ NUEVAS RUTAS - Deben estar ANTES del resource
            Route::get('orders/{order}/show-qr', [OrderController::class, 'showQR'])
                ->name('orders.show-qr');
            Route::get('orders/{order}/check-scanned', [OrderController::class, 'checkScanned'])
                ->name('orders.check-scanned');

            // Resource de órdenes
            Route::resource('orders', OrderController::class);

            Route::post('orders/{order}/mark-paid', [OrderController::class, 'markAsPaid'])
                ->name('orders.mark-paid');
            Route::post('orders/{order}/mark-ready', [OrderController::class, 'markAsReady'])
                ->name('orders.mark-ready');
            Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])
                ->name('orders.cancel');
            Route::post('orders/{order}/change-status', [OrderController::class, 'changeStatus'])
                ->name('orders.change-status');
            Route::post('orders/{order}/schedule-reminders', [OrderController::class, 'scheduleReminders'])
                ->name('orders.schedule-reminders');
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
});

// Términos y Privacidad (accesibles sin login)
Route::get('/terminos', function () {
    return view('terms');
})->name('terms');

Route::get('/privacidad', function () {
    return view('privacy');
})->name('privacy');
