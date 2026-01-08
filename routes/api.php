<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BusinessController as ApiBusinessController;
use App\Http\Controllers\Api\OrderController as ApiOrderController;
use App\Http\Controllers\Api\RatingController as ApiRatingController;
use App\Http\Controllers\Api\ChatController as ApiChatController;

/**
 * ============================================================================
 * API V1 - PARA APLICACIÓN MÓVIL FLUTTER
 * ============================================================================
 */

Route::prefix('v1')->group(function () {

    /**
     * ========================================================================
     * AUTENTICACIÓN - NO REQUIERE TOKEN
     * ========================================================================
     */
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/send-verification-code', [AuthController::class, 'sendVerificationCode']);
    Route::post('auth/verify-code', [AuthController::class, 'verifyCode']);

    /**
     * ========================================================================
     * RUTAS PROTEGIDAS - REQUIEREN TOKEN
     * ========================================================================
     */
    Route::middleware(['auth:sanctum'])->group(function () {

        // Cerrar sesión
        Route::post('auth/logout', [AuthController::class, 'logout']);
        
        // Perfil de usuario
        Route::get('profile', [AuthController::class, 'profile']);
        Route::put('profile', [AuthController::class, 'updateProfile']);

        /**
         * ====================================================================
         * NEGOCIOS
         * ====================================================================
         */
        Route::get('businesses', [ApiBusinessController::class, 'index']);
        Route::get('businesses/nearby', [ApiBusinessController::class, 'nearby']);
        Route::get('businesses/{business}', [ApiBusinessController::class, 'show']);

        /**
         * ====================================================================
         * ÓRDENES
         * ====================================================================
         */
        Route::get('orders', [ApiOrderController::class, 'index']);
        Route::get('orders/{order}', [ApiOrderController::class, 'show']);
        Route::post('orders/scan-qr', [ApiOrderController::class, 'scanQR']);
        Route::post('orders/{order}/confirm-delivery', [ApiOrderController::class, 'confirmDelivery']);

        /**
         * ====================================================================
         * CALIFICACIONES
         * ====================================================================
         */
        Route::post('orders/{order}/rate', [ApiRatingController::class, 'store']);
        Route::get('businesses/{business}/ratings', [ApiRatingController::class, 'index']);

        /**
         * ====================================================================
         * CHAT
         * ====================================================================
         */
        Route::get('orders/{order}/chat', [ApiChatController::class, 'show']);
        Route::post('orders/{order}/chat/messages', [ApiChatController::class, 'sendMessage']);
        Route::post('chats/{chat}/mark-read', [ApiChatController::class, 'markAsRead']);

        /**
         * ====================================================================
         * NOTIFICACIONES
         * ====================================================================
         */
        Route::get('notifications', [AuthController::class, 'notifications']);
        Route::post('notifications/{notification}/read', [AuthController::class, 'markNotificationAsRead']);
        Route::post('device-tokens', [AuthController::class, 'storeDeviceToken']);
    });
});