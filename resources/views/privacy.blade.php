{{--
/**
 * Nombre de la vista           : privacy.blade.php
 * Descripción de la vista      : Política de Privacidad de SISNOTICE
 * Fecha de creación            : 15/01/2026
 * Elaboró                      : Sistema
 * Fecha de liberación          : 15/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 1.0
 */
--}}

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidad - {{ config('app.name', 'SISNOTICE') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans bg-zinc-50 dark:bg-zinc-900 text-black dark:text-white min-h-screen">

    <main class="max-w-5xl mx-auto p-6 space-y-8">

        <!-- Header -->
        <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 pb-4">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                    <flux:icon name="shield-check" class="h-6 w-6 text-black dark:text-white" />
                </div>

                <div>
                    <flux:heading level="1" size="xl" class="text-2xl font-bold">
                        Política de Privacidad
                    </flux:heading>

                    <flux:text class="text-sm text-black/60 dark:text-white/60">
                        Última actualización: 15 de enero de 2026
                    </flux:text>
                </div>
            </div>

            <a href="{{ url('/') }}" class="flex items-center gap-2 text-black dark:text-white hover:text-zinc-600 dark:hover:text-zinc-300 transition-colors">
                <flux:icon name="arrow-left" class="h-4 w-4" />
                <span>Volver al Inicio</span>
            </a>
        </div>

        <!-- Introducción -->
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <flux:heading level="2" size="lg" class="text-xl font-semibold">
                Introducción
            </flux:heading>

            <flux:text class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                En <strong class="text-black dark:text-white">SISNOTICE</strong>, nos comprometemos a proteger su privacidad y la seguridad 
                de sus datos personales. Esta Política de Privacidad explica cómo recopilamos, usamos, 
                compartimos y protegemos su información cuando utiliza nuestra plataforma de gestión 
                de órdenes y notificaciones.
            </flux:text>
        </section>

        <!-- 1. Información que Recopilamos -->
        <section class="space-y-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <flux:heading level="2" size="lg" class="text-xl font-semibold">
                1. Información que Recopilamos
            </flux:heading>

            <!-- 1.1 Información de Registro -->
            <div class="space-y-2">
                <flux:heading level="3" size="md" class="font-semibold text-black dark:text-white">
                    1.1. Información de Registro del Negocio
                </flux:heading>
                <flux:text class="text-sm text-black/80 dark:text-white/80 mb-2">Cuando registra su negocio, recopilamos:</flux:text>
                <ul class="list-disc pl-6 space-y-1 text-sm text-black/80 dark:text-white/80">
                    <li>Nombre comercial y razón social del negocio</li>
                    <li>RFC (Registro Federal de Contribuyentes)</li>
                    <li>Dirección física completa del negocio</li>
                    <li>Coordenadas de geolocalización (latitud y longitud)</li>
                    <li>Número de teléfono y correo electrónico</li>
                    <li>Sitio web del negocio (si aplica)</li>
                    <li>Logotipo y descripción del negocio</li>
                </ul>
            </div>

            <!-- 1.2 Información de la Cuenta -->
            <div class="space-y-2">
                <flux:heading level="3" size="md" class="font-semibold text-black dark:text-white">
                    1.2. Información de la Cuenta
                </flux:heading>
                <ul class="list-disc pl-6 space-y-1 text-sm text-black/80 dark:text-white/80">
                    <li>Nombre completo del administrador</li>
                    <li>Correo electrónico de acceso</li>
                    <li>Contraseña encriptada</li>
                    <li>Rol asignado (SuperAdministrador, Administrador de Negocio)</li>
                </ul>
            </div>

            <!-- 1.3 Información de Transacciones -->
            <div class="space-y-2">
                <flux:heading level="3" size="md" class="font-semibold text-black dark:text-white">
                    1.3. Información de Transacciones
                </flux:heading>
                <ul class="list-disc pl-6 space-y-1 text-sm text-black/80 dark:text-white/80">
                    <li>Detalles de órdenes creadas (monto, estado, fecha)</li>
                    <li>Códigos QR generados para órdenes</li>
                    <li>Información de pagos y suscripciones a paquetes</li>
                    <li>Cupones de descuento utilizados</li>
                    <li>Historial de cambios de estado de órdenes</li>
                </ul>
            </div>

            <!-- 1.4 Información de Uso -->
            <div class="space-y-2">
                <flux:heading level="3" size="md" class="font-semibold text-black dark:text-white">
                    1.4. Información de Uso
                </flux:heading>
                <ul class="list-disc pl-6 space-y-1 text-sm text-black/80 dark:text-white/80">
                    <li>Dirección IP y tipo de dispositivo</li>
                    <li>Navegador y sistema operativo</li>
                    <li>Páginas visitadas y acciones realizadas en la plataforma</li>
                    <li>Fecha y hora de acceso</li>
                    <li>Registros de actividad (logs) para seguridad y soporte</li>
                </ul>
            </div>
        </section>

        <!-- 2. Cómo Usamos la Información -->
        <section class="space-y-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <flux:heading level="2" size="lg" class="text-xl font-semibold">
                2. Cómo Usamos la Información
            </flux:heading>

            <div class="grid md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <flux:heading level="4" size="sm" class="font-semibold text-black dark:text-white">
                        Provisión del Servicio
                    </flux:heading>
                    <ul class="text-sm space-y-1 text-black/80 dark:text-white/80">
                        <li>• Crear y gestionar cuentas de negocio</li>
                        <li>• Procesar órdenes y generar códigos QR</li>
                        <li>• Facilitar comunicación vía chat</li>
                        <li>• Enviar notificaciones de órdenes</li>
                    </ul>
                </div>

                <div class="space-y-2">
                    <flux:heading level="4" size="sm" class="font-semibold text-black dark:text-white">
                        Facturación y Pagos
                    </flux:heading>
                    <ul class="text-sm space-y-1 text-black/80 dark:text-white/80">
                        <li>• Procesar pagos de suscripciones</li>
                        <li>• Aplicar cupones de descuento</li>
                        <li>• Generar facturas y comprobantes</li>
                        <li>• Gestionar renovaciones</li>
                    </ul>
                </div>

                <div class="space-y-2">
                    <flux:heading level="4" size="sm" class="font-semibold text-black dark:text-white">
                        Análisis y Mejora
                    </flux:heading>
                    <ul class="text-sm space-y-1 text-black/80 dark:text-white/80">
                        <li>• Generar reportes y estadísticas</li>
                        <li>• Analizar patrones de uso</li>
                        <li>• Identificar y corregir errores</li>
                        <li>• Desarrollar nuevas funcionalidades</li>
                    </ul>
                </div>

                <div class="space-y-2">
                    <flux:heading level="4" size="sm" class="font-semibold text-black dark:text-white">
                        Seguridad y Cumplimiento
                    </flux:heading>
                    <ul class="text-sm space-y-1 text-black/80 dark:text-white/80">
                        <li>• Detectar y prevenir fraudes</li>
                        <li>• Proteger la seguridad de la plataforma</li>
                        <li>• Cumplir obligaciones legales</li>
                        <li>• Responder a requerimientos legales</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- 3. Compartir Información con Terceros -->
        <section class="space-y-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <flux:heading level="2" size="lg" class="text-xl font-semibold">
                3. Compartir Información con Terceros
            </flux:heading>

            <!-- Info Box Destacado -->
            <div class="flex items-start gap-3 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                <flux:icon name="shield-check" class="h-5 w-5 text-green-600 dark:text-green-400 mt-0.5 flex-shrink-0" />
                <div>
                    <flux:heading level="3" size="sm" class="font-semibold text-green-900 dark:text-green-100">
                        No Vendemos sus Datos
                    </flux:heading>
                    <flux:text class="text-sm text-green-800 dark:text-green-200">
                        SISNOTICE NO vende, alquila ni intercambia su información personal con terceros 
                        con fines comerciales o de marketing.
                    </flux:text>
                </div>
            </div>

            <!-- 3.1 Proveedores de Servicios -->
            <div class="space-y-2">
                <flux:heading level="3" size="md" class="font-semibold text-black dark:text-white">
                    3.1. Proveedores de Servicios
                </flux:heading>
                <flux:text class="text-sm text-black/80 dark:text-white/80 mb-2">
                    Podemos compartir información con proveedores bajo acuerdos de confidencialidad:
                </flux:text>
                <ul class="list-disc pl-6 space-y-1 text-sm text-black/80 dark:text-white/80">
                    <li>Servicios de hosting y almacenamiento de datos</li>
                    <li>Procesadores de pagos y pasarelas de pago</li>
                    <li>Servicios de envío de notificaciones y correos electrónicos</li>
                    <li>Herramientas de análisis y monitoreo de rendimiento</li>
                </ul>
            </div>
        </section>

        <!-- 4. Seguridad de los Datos -->
        <section class="space-y-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <flux:heading level="2" size="lg" class="text-xl font-semibold">
                4. Seguridad de los Datos
            </flux:heading>

            <!-- 4.1 Medidas Técnicas -->
            <div class="space-y-2">
                <flux:heading level="3" size="md" class="font-semibold text-black dark:text-white">
                    4.1. Medidas Técnicas
                </flux:heading>
                <ul class="list-disc pl-6 space-y-1 text-sm text-black/80 dark:text-white/80">
                    <li>Encriptación SSL/TLS para todas las comunicaciones</li>
                    <li>Contraseñas hasheadas con algoritmos seguros (bcrypt)</li>
                    <li>Autenticación de dos factores disponible</li>
                    <li>Firewalls y sistemas de detección de intrusos</li>
                    <li>Backups automáticos y regulares de datos</li>
                    <li>Tokens de sesión con expiración automática</li>
                </ul>
            </div>

            <!-- 4.2 Limitaciones -->
            <div class="space-y-2">
                <flux:heading level="3" size="md" class="font-semibold text-black dark:text-white">
                    4.2. Limitaciones
                </flux:heading>
                <flux:text class="text-sm text-black/80 dark:text-white/80">
                    Si bien implementamos medidas robustas de seguridad, ningún sistema es completamente 
                    infalible. No podemos garantizar la seguridad absoluta de los datos transmitidos 
                    a través de Internet. Usted también es responsable de mantener seguras sus credenciales 
                    de acceso.
                </flux:text>
            </div>
        </section>

        <!-- 5. Sus Derechos -->
        <section class="space-y-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <flux:heading level="2" size="lg" class="text-xl font-semibold">
                5. Sus Derechos
            </flux:heading>

            <flux:text class="text-sm text-black/80 dark:text-white/80">
                De acuerdo con las leyes de protección de datos aplicables, usted tiene derecho a:
            </flux:text>

            <div class="grid md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <flux:heading level="4" size="sm" class="font-semibold text-black dark:text-white">
                        Derecho de Acceso
                    </flux:heading>
                    <flux:text class="text-sm text-black/80 dark:text-white/80">
                        Puede solicitar una copia de la información personal que tenemos sobre usted.
                    </flux:text>
                </div>

                <div class="space-y-2">
                    <flux:heading level="4" size="sm" class="font-semibold text-black dark:text-white">
                        Derecho de Rectificación
                    </flux:heading>
                    <flux:text class="text-sm text-black/80 dark:text-white/80">
                        Puede corregir información inexacta o incompleta desde su panel de control.
                    </flux:text>
                </div>

                <div class="space-y-2">
                    <flux:heading level="4" size="sm" class="font-semibold text-black dark:text-white">
                        Derecho de Supresión
                    </flux:heading>
                    <flux:text class="text-sm text-black/80 dark:text-white/80">
                        Puede solicitar la eliminación de su información personal, sujeto a obligaciones legales.
                    </flux:text>
                </div>

                <div class="space-y-2">
                    <flux:heading level="4" size="sm" class="font-semibold text-black dark:text-white">
                        Derecho de Portabilidad
                    </flux:heading>
                    <flux:text class="text-sm text-black/80 dark:text-white/80">
                        Puede solicitar una copia de sus datos en un formato estructurado y de uso común.
                    </flux:text>
                </div>
            </div>

            <div class="mt-4 p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                <flux:text class="text-sm text-black/80 dark:text-white/80">
                    <strong class="text-black dark:text-white">Para ejercer cualquiera de estos derechos:</strong>
                    Contáctenos en <strong class="text-black dark:text-white">privacidad@sisnotice.com</strong>.
                    Responderemos a su solicitud dentro de 30 días.
                </flux:text>
            </div>
        </section>

        <!-- 6. Retención de Datos -->
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <flux:heading level="2" size="lg" class="text-xl font-semibold">
                6. Retención de Datos
            </flux:heading>

            <flux:text class="text-sm text-black/80 dark:text-white/80">
                Conservamos su información personal durante el tiempo necesario para proveer nuestros servicios
                y cumplir con obligaciones legales, fiscales y contables (mínimo 5 años según legislación mexicana).
            </flux:text>
        </section>

        <!-- 7. Cookies y Tecnologías Similares -->
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <flux:heading level="2" size="lg" class="text-xl font-semibold">
                7. Cookies y Tecnologías Similares
            </flux:heading>

            <flux:text class="text-sm text-black/80 dark:text-white/80 mb-2">
                Utilizamos cookies y tecnologías similares para:
            </flux:text>
            <ul class="list-disc pl-6 space-y-1 text-sm text-black/80 dark:text-white/80">
                <li>Mantener su sesión activa y recordar sus preferencias</li>
                <li>Mejorar la seguridad y prevenir fraudes</li>
                <li>Analizar el uso de la plataforma y su rendimiento</li>
                <li>Recordar su idioma y configuraciones preferidas</li>
            </ul>
        </section>

        <!-- 8. Contacto -->
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <flux:heading level="2" size="lg" class="text-xl font-semibold">
                8. Contacto
            </flux:heading>

            <flux:text class="text-sm text-black/80 dark:text-white/80">
                Para consultas sobre esta Política de Privacidad:
            </flux:text>
            <div class="mt-2 space-y-1 text-sm text-black/80 dark:text-white/80">
                <p><strong>Email de Privacidad:</strong> privacidad@sisnotice.com</p>
                <p><strong>Email de Soporte:</strong> soporte@sisnotice.com</p>
                <p><strong>Sistema de Chat:</strong> Disponible en su panel de control</p>
                <p><strong>Horario de atención:</strong> Lunes a Viernes, 9:00 - 18:00</p>
            </div>
        </section>

        <!-- Acceptance Box -->
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6 text-center">
            <flux:text class="text-sm font-semibold text-black/80 dark:text-white/80">
                Al utilizar SISNOTICE, confirma que ha leído y comprende esta Política de Privacidad 
                y acepta el procesamiento de sus datos como se describe aquí.
            </flux:text>
        </div>

        <!-- Footer -->
        <div class="text-center text-xs text-black/60 dark:text-white/60 pt-6 border-t border-zinc-200 dark:border-zinc-700">
            <flux:text class="text-xs text-black/60 dark:text-white/60">
                &copy; {{ now()->year }} {{ config('app.name', 'SISNOTICE') }}. Todos los derechos reservados.
            </flux:text>

            <div class="mt-2 space-x-4">
                <a href="{{ url('/') }}" class="text-black dark:text-white hover:text-zinc-600 dark:hover:text-zinc-300 text-sm transition-colors">Inicio</a>
                <a href="{{ route('terms') }}" class="text-black dark:text-white hover:text-zinc-600 dark:hover:text-zinc-300 text-sm transition-colors">Términos y Condiciones</a>
            </div>
        </div>

    </main>

</body>

</html>