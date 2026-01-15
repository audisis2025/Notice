{{--
/**
 * Nombre de la vista           : terms.blade.php
 * Descripción de la vista      : Términos y Condiciones de uso de SISNOTICE
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
    <title>Términos y Condiciones - {{ config('app.name', 'SISNOTICE') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans bg-zinc-50 dark:bg-zinc-900 text-black dark:text-white min-h-screen">

    <main class="max-w-5xl mx-auto p-6 space-y-8">

        <!-- Header -->
        <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 pb-4">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                    <flux:icon name="document-text" class="h-6 w-6 text-black dark:text-white" />
                </div>

                <div>
                    <flux:heading level="1" size="xl" class="text-2xl font-bold">
                        Términos y Condiciones
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

        <!-- 1. Introducción -->
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <flux:heading level="2" size="lg" class="text-xl font-semibold">
                1. Introducción
            </flux:heading>

            <flux:text class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Bienvenido a <strong class="text-black dark:text-white">SISNOTICE</strong>, un sistema de gestión de órdenes y notificaciones 
                para negocios. Al registrar su negocio y utilizar nuestra plataforma, usted acepta estar 
                sujeto a los siguientes términos y condiciones. Le recomendamos leer este documento 
                cuidadosamente antes de proceder con el registro.
            </flux:text>
        </section>

        <!-- 2. Definiciones -->
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <flux:heading level="2" size="lg" class="text-xl font-semibold">
                2. Definiciones
            </flux:heading>

            <ul class="list-disc pl-6 space-y-2 text-sm text-black/80 dark:text-white/80">
                <li><strong class="text-black dark:text-white">"Plataforma":</strong> Se refiere al sistema SISNOTICE y todos sus servicios</li>
                <li><strong class="text-black dark:text-white">"Usuario":</strong> Persona que administra un negocio registrado en la plataforma</li>
                <li><strong class="text-black dark:text-white">"Negocio":</strong> Establecimiento comercial registrado en SISNOTICE</li>
                <li><strong class="text-black dark:text-white">"Orden":</strong> Solicitud de servicio o producto gestionada a través de la plataforma</li>
                <li><strong class="text-black dark:text-white">"Cliente Final":</strong> Usuario móvil que realiza órdenes a los negocios</li>
                <li><strong class="text-black dark:text-white">"Paquete":</strong> Plan de suscripción que otorga acceso a funcionalidades específicas</li>
            </ul>
        </section>

        <!-- 3. Registro y Cuenta -->
        <section class="space-y-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <flux:heading level="2" size="lg" class="text-xl font-semibold">
                3. Registro y Cuenta
            </flux:heading>

            <!-- 3.1 Requisitos de Registro -->
            <div class="space-y-2">
                <flux:heading level="3" size="md" class="font-semibold text-black dark:text-white">
                    3.1. Requisitos de Registro
                </flux:heading>
                <ul class="list-disc pl-6 space-y-1 text-sm text-black/80 dark:text-white/80">
                    <li>Ser mayor de 18 años y tener capacidad legal para contratar</li>
                    <li>Proporcionar información veraz, completa y actualizada sobre su negocio</li>
                    <li>Contar con un negocio legalmente establecido con documentación fiscal válida</li>
                    <li>Aceptar contratar al menos un paquete de servicios para activar su cuenta</li>
                </ul>
            </div>

            <!-- 3.2 Responsabilidad de la Cuenta -->
            <div class="space-y-2">
                <flux:heading level="3" size="md" class="font-semibold text-black dark:text-white">
                    3.2. Responsabilidad de la Cuenta
                </flux:heading>
                <flux:text class="text-sm text-black/80 dark:text-white/80">
                    Usted es responsable de mantener la confidencialidad de sus credenciales de acceso 
                    y de todas las actividades que ocurran bajo su cuenta. Debe notificarnos inmediatamente 
                    de cualquier uso no autorizado de su cuenta.
                </flux:text>
            </div>
        </section>

        <!-- 4. Paquetes y Pagos -->
        <section class="space-y-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <flux:heading level="2" size="lg" class="text-xl font-semibold">
                4. Paquetes y Pagos
            </flux:heading>

            <!-- 4.1 Paquetes de Servicio -->
            <div class="space-y-2">
                <flux:heading level="3" size="md" class="font-semibold text-black dark:text-white">
                    4.1. Paquetes de Servicio
                </flux:heading>
                <flux:text class="text-sm text-black/80 dark:text-white/80 mb-2">
                    SISNOTICE ofrece diferentes paquetes de servicio con características específicas:
                </flux:text>
                <ul class="list-disc pl-6 space-y-1 text-sm text-black/80 dark:text-white/80">
                    <li><strong class="text-black dark:text-white">Paquete Básico:</strong> Acceso a funcionalidades esenciales de gestión de órdenes</li>
                    <li><strong class="text-black dark:text-white">Paquete Profesional:</strong> Incluye reportes, estadísticas y funciones avanzadas</li>
                    <li><strong class="text-black dark:text-white">Paquete Premium:</strong> Acceso completo a todas las funcionalidades y soporte prioritario</li>
                </ul>
            </div>

            <!-- 4.2 Facturación y Renovación -->
            <div class="space-y-2">
                <flux:heading level="3" size="md" class="font-semibold text-black dark:text-white">
                    4.2. Facturación y Renovación
                </flux:heading>
                <ul class="list-disc pl-6 space-y-1 text-sm text-black/80 dark:text-white/80">
                    <li>Los paquetes se cobran mensualmente según el plan seleccionado</li>
                    <li>Los pagos se procesan al inicio de cada período de facturación</li>
                    <li>La renovación es automática a menos que se cancele antes de la fecha de renovación</li>
                    <li>Los precios pueden modificarse con un aviso previo de 30 días</li>
                </ul>
            </div>

            <!-- 4.3 Cupones de Descuento -->
            <div class="space-y-2">
                <flux:heading level="3" size="md" class="font-semibold text-black dark:text-white">
                    4.3. Cupones de Descuento
                </flux:heading>
                <flux:text class="text-sm text-black/80 dark:text-white/80">
                    Los cupones de descuento son de un solo uso y están sujetos a condiciones específicas. 
                    No son transferibles ni canjeables por efectivo. SISNOTICE se reserva el derecho de 
                    invalidar cupones utilizados fraudulentamente.
                </flux:text>
            </div>
        </section>

        <!-- 5. Uso de la Plataforma -->
        <section class="space-y-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <flux:heading level="2" size="lg" class="text-xl font-semibold">
                5. Uso de la Plataforma
            </flux:heading>

            <!-- 5.1 Uso Permitido -->
            <div class="space-y-2">
                <flux:heading level="3" size="md" class="font-semibold text-black dark:text-white">
                    5.1. Uso Permitido
                </flux:heading>
                <flux:text class="text-sm text-black/80 dark:text-white/80 mb-2">Usted se compromete a:</flux:text>
                <ul class="list-disc pl-6 space-y-1 text-sm text-black/80 dark:text-white/80">
                    <li>Utilizar la plataforma únicamente para fines comerciales legítimos</li>
                    <li>Proporcionar información precisa sobre productos, servicios y tiempos de entrega</li>
                    <li>Responder a las órdenes de clientes de manera oportuna y profesional</li>
                    <li>Mantener actualizada la información de su negocio</li>
                    <li>Cumplir con todas las leyes y regulaciones aplicables</li>
                </ul>
            </div>

            <!-- 5.2 Uso Prohibido -->
            <div class="space-y-2">
                <flux:heading level="3" size="md" class="font-semibold text-black dark:text-white">
                    5.2. Uso Prohibido
                </flux:heading>
                <flux:text class="text-sm text-black/80 dark:text-white/80 mb-2">Está estrictamente prohibido:</flux:text>
                <ul class="list-disc pl-6 space-y-1 text-sm text-black/80 dark:text-white/80">
                    <li>Utilizar la plataforma para actividades ilegales o fraudulentas</li>
                    <li>Intentar acceder sin autorización a cuentas de otros usuarios o al sistema</li>
                    <li>Interferir con el funcionamiento normal de la plataforma</li>
                    <li>Usar scripts, bots o herramientas automatizadas sin autorización</li>
                    <li>Copiar, modificar o distribuir el código fuente de la plataforma</li>
                    <li>Suplantar la identidad de otro negocio o persona</li>
                    <li>Publicar contenido ofensivo, difamatorio o que viole derechos de terceros</li>
                </ul>
            </div>
        </section>

        <!-- 6. Gestión de Órdenes y Códigos QR -->
        <section class="space-y-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <flux:heading level="2" size="lg" class="text-xl font-semibold">
                6. Gestión de Órdenes y Códigos QR
            </flux:heading>

            <!-- Info Box -->
            <div class="flex items-start gap-3 p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                <flux:icon name="information-circle" class="h-5 w-5 text-black dark:text-white mt-0.5 flex-shrink-0" />
                <div>
                    <flux:heading level="3" size="sm" class="font-semibold text-black dark:text-white">
                        Códigos QR Únicos
                    </flux:heading>
                    <flux:text class="text-sm text-black/80 dark:text-white/80">
                        Cada orden genera un código QR único que permite vincular al cliente con la orden.
                    </flux:text>
                </div>
            </div>

            <!-- 6.1 Códigos QR -->
            <div class="space-y-2">
                <flux:heading level="3" size="md" class="font-semibold text-black dark:text-white">
                    6.1. Responsabilidades con Códigos QR
                </flux:heading>
                <ul class="list-disc pl-6 space-y-1 text-sm text-black/80 dark:text-white/80">
                    <li>Proteger y no compartir los códigos QR de manera indebida</li>
                    <li>Utilizar los códigos QR únicamente para el propósito designado</li>
                    <li>Notificar inmediatamente cualquier problema de seguridad con los códigos QR</li>
                </ul>
            </div>

            <!-- 6.2 Proceso de Órdenes -->
            <div class="space-y-2">
                <flux:heading level="3" size="md" class="font-semibold text-black dark:text-white">
                    6.2. Proceso de Órdenes
                </flux:heading>
                <flux:text class="text-sm text-black/80 dark:text-white/80">
                    Las órdenes pasan por diferentes estados (pendiente, pagada, lista, entregada, cancelada). 
                    Usted es responsable de actualizar el estado de las órdenes de manera precisa y oportuna 
                    para mantener informados a sus clientes.
                </flux:text>
            </div>
        </section>

        <!-- 7. Privacidad y Protección de Datos -->
        <section class="space-y-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <flux:heading level="2" size="lg" class="text-xl font-semibold">
                7. Privacidad y Protección de Datos
            </flux:heading>

            <flux:text class="text-sm text-black/80 dark:text-white/80">
                SISNOTICE recopila y procesa información de registro del negocio, órdenes, transacciones y 
                datos de uso. Para más detalles, consulte nuestra 
                <a href="{{ route('privacy') }}" class="text-black dark:text-white font-semibold hover:underline">
                    Política de Privacidad
                </a>.
            </flux:text>
        </section>

        <!-- 8. Sistema de Calificaciones -->
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <flux:heading level="2" size="lg" class="text-xl font-semibold">
                8. Sistema de Calificaciones
            </flux:heading>

            <flux:text class="text-sm text-black/80 dark:text-white/80">
                Los clientes pueden calificar su negocio después de completar una orden. Estas calificaciones 
                son públicas y contribuyen a su reputación en la plataforma.
            </flux:text>

            <ul class="list-disc pl-6 space-y-1 text-sm text-black/80 dark:text-white/80">
                <li>Puede habilitar o deshabilitar las calificaciones desde su configuración</li>
                <li>Las calificaciones son responsabilidad de los clientes que las emiten</li>
                <li>No está permitido solicitar calificaciones falsas o manipular el sistema</li>
                <li>SISNOTICE se reserva el derecho de eliminar calificaciones fraudulentas o abusivas</li>
            </ul>
        </section>

        <!-- 9. Limitación de Responsabilidad -->
        <section class="space-y-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <flux:heading level="2" size="lg" class="text-xl font-semibold">
                9. Limitación de Responsabilidad
            </flux:heading>

            <flux:text class="text-sm text-black/80 dark:text-white/80">
                SISNOTICE se proporciona "tal cual" sin garantías de ningún tipo. No garantizamos que:
            </flux:text>

            <ul class="list-disc pl-6 space-y-2 text-sm text-black/80 dark:text-white/80">
                <li>La plataforma estará disponible ininterrumpidamente o libre de errores</li>
                <li>Los resultados obtenidos serán exactos o confiables</li>
                <li>Los defectos serán corregidos inmediatamente</li>
            </ul>

            <flux:text class="text-sm text-black/80 dark:text-white/80 mt-3">
                En ningún caso SISNOTICE será responsable por daños indirectos, incidentales, 
                especiales, consecuentes o punitivos, incluyendo pérdida de beneficios, datos, 
                uso o pérdidas intangibles.
            </flux:text>
        </section>

        <!-- 10. Suspensión y Terminación -->
        <section class="space-y-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <flux:heading level="2" size="lg" class="text-xl font-semibold">
                10. Suspensión y Terminación
            </flux:heading>

            <flux:text class="text-sm text-black/80 dark:text-white/80">
                Podemos suspender o terminar su cuenta si:
            </flux:text>
            <ul class="list-disc pl-6 mt-2 space-y-1 text-sm text-black/80 dark:text-white/80">
                <li>Viola estos términos y condiciones</li>
                <li>No paga las cuotas correspondientes a su paquete</li>
                <li>Realiza actividades fraudulentas o ilegales</li>
                <li>Recibimos múltiples quejas justificadas sobre su negocio</li>
                <li>Su negocio representa un riesgo para la plataforma o sus usuarios</li>
            </ul>
        </section>

        <!-- 11. Modificaciones -->
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <flux:heading level="2" size="lg" class="text-xl font-semibold">
                11. Modificaciones
            </flux:heading>

            <flux:text class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                SISNOTICE se reserva el derecho de modificar estos términos en cualquier momento. 
                Los cambios entrarán en vigor inmediatamente después de su publicación en la plataforma. 
                Su uso continuado de la plataforma después de dichos cambios constituye su aceptación 
                de los nuevos términos.
            </flux:text>
        </section>

        <!-- 12. Ley Aplicable y Jurisdicción -->
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <flux:heading level="2" size="lg" class="text-xl font-semibold">
                12. Ley Aplicable y Jurisdicción
            </flux:heading>

            <flux:text class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Estos términos se regirán e interpretarán de acuerdo con las leyes de México. 
                Cualquier disputa relacionada con estos términos estará sujeta a la jurisdicción 
                exclusiva de los tribunales competentes de México.
            </flux:text>
        </section>

        <!-- 13. Contacto -->
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <flux:heading level="2" size="lg" class="text-xl font-semibold">
                13. Contacto
            </flux:heading>

            <flux:text class="text-sm text-black/80 dark:text-white/80">
                Para consultas sobre estos Términos y Condiciones:
            </flux:text>
            <div class="mt-2 space-y-1 text-sm text-black/80 dark:text-white/80">
                <p><strong>Email:</strong> soporte@sisnotice.com</p>
                <p><strong>Plataforma:</strong> Sistema de chat integrado</p>
                <p><strong>Horario de atención:</strong> Lunes a Viernes, 9:00 - 18:00</p>
            </div>
        </section>

        <!-- Acceptance Box -->
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6 text-center">
            <flux:text class="text-sm font-semibold text-black/80 dark:text-white/80">
                AL REGISTRAR SU NEGOCIO EN SISNOTICE, USTED RECONOCE QUE HA LEÍDO, 
                ENTENDIDO Y ACEPTA ESTAR OBLIGADO POR ESTOS TÉRMINOS Y CONDICIONES.
            </flux:text>
        </div>

        <!-- Footer -->
        <div class="text-center text-xs text-black/60 dark:text-white/60 pt-6 border-t border-zinc-200 dark:border-zinc-700">
            <flux:text class="text-xs text-black/60 dark:text-white/60">
                &copy; {{ now()->year }} {{ config('app.name', 'SISNOTICE') }}. Todos los derechos reservados.
            </flux:text>

            <div class="mt-2 space-x-4">
                <a href="{{ url('/') }}" class="text-black dark:text-white hover:text-zinc-600 dark:hover:text-zinc-300 text-sm transition-colors">Inicio</a>
                <a href="{{ route('privacy') }}" class="text-black dark:text-white hover:text-zinc-600 dark:hover:text-zinc-300 text-sm transition-colors">Política de Privacidad</a>
            </div>
        </div>

    </main>

</body>

</html>