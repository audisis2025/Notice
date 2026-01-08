<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingSeeder extends Seeder
{
    /**
     * Ejecuta el seeder para crear configuraciones del sistema.
     * 
     * Define configuraciones generales que controlan el comportamiento
     * del sistema y pueden ser modificadas desde el panel de administración.
     *
     * @return void
     */
    public function run(): void
    {
        $settings = [
            // Configuraciones de notificaciones
            [
                'key' => 'notification_days_before_expiration',
                'value' => '7',
                'type' => 'integer',
                'description' => 'Días antes del vencimiento del paquete para enviar notificación al negocio',
            ],
            [
                'key' => 'enable_push_notifications',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Habilitar o deshabilitar notificaciones push globalmente',
            ],

            // Configuraciones de órdenes
            [
                'key' => 'default_delivery_period_minutes',
                'value' => '30',
                'type' => 'integer',
                'description' => 'Período de entrega por defecto en minutos para nuevos negocios',
            ],
            [
                'key' => 'enable_auto_chat_on_delay',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Habilitar automáticamente el chat cuando se excede el período de entrega',
            ],

            // Configuraciones de calificaciones
            [
                'key' => 'enable_ratings_by_default',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Habilitar calificaciones por defecto para nuevos negocios',
            ],
            [
                'key' => 'min_rating_stars',
                'value' => '0',
                'type' => 'integer',
                'description' => 'Número mínimo de estrellas permitido',
            ],
            [
                'key' => 'max_rating_stars',
                'value' => '5',
                'type' => 'integer',
                'description' => 'Número máximo de estrellas permitido',
            ],

            // Configuraciones de códigos QR
            [
                'key' => 'qr_code_size',
                'value' => '300',
                'type' => 'integer',
                'description' => 'Tamaño en píxeles de los códigos QR generados',
            ],
            [
                'key' => 'qr_code_format',
                'value' => 'png',
                'type' => 'string',
                'description' => 'Formato de imagen para códigos QR (png, svg)',
            ],

            // Configuraciones de autenticación móvil
            [
                'key' => 'verification_code_length',
                'value' => '6',
                'type' => 'integer',
                'description' => 'Longitud del código de verificación para login móvil',
            ],
            [
                'key' => 'verification_code_expiration_minutes',
                'value' => '10',
                'type' => 'integer',
                'description' => 'Minutos de validez del código de verificación',
            ],

            // Configuraciones de cupones
            [
                'key' => 'coupon_code_length',
                'value' => '8',
                'type' => 'integer',
                'description' => 'Longitud del código de los cupones generados',
            ],

            // Configuraciones de reportes
            [
                'key' => 'default_report_period',
                'value' => 'monthly',
                'type' => 'string',
                'description' => 'Período por defecto para reportes (weekly, monthly, quarterly, yearly)',
            ],

            // Configuraciones del sistema
            [
                'key' => 'system_name',
                'value' => 'SISNOTICE',
                'type' => 'string',
                'description' => 'Nombre del sistema',
            ],
            [
                'key' => 'system_timezone',
                'value' => 'America/Mexico_City',
                'type' => 'string',
                'description' => 'Zona horaria del sistema',
            ],
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
                'type' => 'boolean',
                'description' => 'Activar o desactivar modo de mantenimiento',
            ],
        ];

        foreach ($settings as $setting) {
            SystemSetting::create($setting);
        }
    }
}
