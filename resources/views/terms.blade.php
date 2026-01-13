{{--
* Nombre de la vista           : terms.blade.php
* Descripción de la vista      : Página de términos y condiciones de la aplicación.
* Fecha de creación            : 03/11/2025
* Elaboró                      : Jesús Núñez
* Fecha de liberación          : 03/11/2025
* Autorizó                     : Jesús Núñez
* Version                      : 1.0
* Fecha de mantenimiento       : 
* Folio de mantenimiento       : 
* Tipo de mantenimiento        :
* Descripción del mantenimiento: 
* Responsable                  : 
* Revisor                      : 
--}}

<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Términos y Condiciones - {{ config('app.name', 'Laravel') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans bg-zinc-50 dark:bg-zinc-900 text-black dark:text-white min-h-screen">

        <main class="max-w-5xl mx-auto p-6 space-y-8">

            <!-- Header -->
            <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 pb-4">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                        <flux:icon name="document-text" class="h-6 w-6 text-custom-orange" />
                    </div>

                    <div>
                        <flux:heading level="1" size="xl" class="text-2xl font-bold">
                            Términos y Condiciones
                        </flux:heading>

                        <flux:text class="text-sm text-black/60 dark:text-white/60">
                            Última actualización: {{ now()->format('d/m/Y') }}
                        </flux:text>
                    </div>
                </div>

                <a href="{{ url('/') }}" class="flex items-center gap-2 text-black dark:text-white hover:text-zinc-600 dark:hover:text-zinc-300 transition-colors">
                    <flux:icon name="arrow-left" class="h-4 w-4" />
                    <span>Volver al Inicio</span>
                </a>
            </div>

            

            <!-- 1. Aceptación de los Términos -->
            <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
                <flux:heading level="2" size="lg" class="text-xl font-semibold">
                    1. Aceptación de los Términos
                </flux:heading>

                <flux:text class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                    Al acceder y utilizar <strong class="text-black dark:text-white">{{ config('app.name', 'SBVC') }}</strong>, aceptas estar legalmente obligado por estos Términos y Condiciones. Si no estás de acuerdo con alguno de estos términos, por favor no utilices nuestra plataforma.
                </flux:text>
            </section>

            <!-- 2. Definiciones -->
            <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
                <flux:heading level="2" size="lg" class="text-xl font-semibold">
                    2. Definiciones
                </flux:heading>

                <ul class="list-disc pl-6 space-y-2 text-sm text-black/80 dark:text-white/80">
                    <li><strong class="text-black dark:text-white">"Plataforma":</strong> El sitio web y aplicación {{ config('app.name', 'SBVC') }}</li>
                    <li><strong class="text-black dark:text-white">"Usuario":</strong> Persona que utiliza la plataforma para buscar establecimientos</li>
                    <li><strong class="text-black dark:text-white">"Establecimiento":</strong> Negocio registrado que ofrece productos/servicios</li>
                    <li><strong class="text-black dark:text-white">"Servicios":</strong> Funcionalidades ofrecidas por la plataforma</li>
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
                        <li>Debes ser mayor de 18 años</li>
                        <li>Proporcionar información veraz y actualizada</li>
                        <li>Mantener la confidencialidad de tu cuenta</li>
                        <li>Notificar cualquier uso no autorizado inmediatamente</li>
                    </ul>
                </div>

                <!-- 3.2 Responsabilidades del Usuario -->
                <div class="space-y-2">
                    <flux:heading level="3" size="md" class="font-semibold text-black dark:text-white">
                        3.2. Responsabilidades del Usuario
                    </flux:heading>
                    <ul class="list-disc pl-6 space-y-1 text-sm text-black/80 dark:text-white/80">
                        <li>No compartir tu cuenta con terceros</li>
                        <li>No utilizar la plataforma para actividades ilegales</li>
                        <li>Respetar los derechos de propiedad intelectual</li>
                        <li>No realizar reservas falsas o fraudulentas</li>
                    </ul>
                </div>
            </section>

            <!-- 4. Para Establecimientos -->
            <section class="space-y-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
                <flux:heading level="2" size="lg" class="text-xl font-semibold">
                    4. Para Establecimientos
                </flux:heading>

                <!-- 4.1 Requisitos Comerciales -->
                <div class="space-y-2">
                    <flux:heading level="3" size="md" class="font-semibold text-black dark:text-white">
                        4.1. Requisitos Comerciales
                    </flux:heading>
                    <ul class="list-disc pl-6 space-y-1 text-sm text-black/80 dark:text-white/80">
                        <li>Contar con los permisos y licencias necesarias</li>
                        <li>Proporcionar información comercial veraz</li>
                        <li>Mantener actualizados precios y disponibilidad</li>
                        <li>Cumplir con las reservas y pedidos confirmados</li>
                    </ul>
                </div>

                <!-- 4.2 Calidad del Servicio -->
                <div class="space-y-2">
                    <flux:heading level="3" size="md" class="font-semibold text-black dark:text-white">
                        4.2. Calidad del Servicio
                    </flux:heading>
                    <ul class="list-disc pl-6 space-y-1 text-sm text-black/80 dark:text-white/80">
                        <li>Ofrecer productos/servicios según lo descrito</li>
                        <li>Mantener estándares de higiene y seguridad</li>
                        <li>Responder a consultas en tiempo razonable</li>
                        <li>Gestionar quejas de manera profesional</li>
                    </ul>
                </div>
            </section>

            <!-- 5. Reservas y Pedidos -->
            <section class="space-y-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
                <flux:heading level="2" size="lg" class="text-xl font-semibold">
                    5. Reservas y Pedidos
                </flux:heading>

                <!-- Info Box -->
                <div class="flex items-start gap-3 p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                    <flux:icon name="information-circle" class="h-5 w-5 text-black dark:text-white mt-0.5 flex-shrink-0" />
                    <div>
                        <flux:heading level="3" size="sm" class="font-semibold text-black dark:text-white">
                            Proceso de Reservas
                        </flux:heading>
                        <flux:text class="text-sm text-black/80 dark:text-white/80">
                            Las reservas están sujetas a disponibilidad y políticas específicas de cada establecimiento.
                        </flux:text>
                    </div>
                </div>

                <!-- 5.1 Confirmación -->
                <div class="space-y-2">
                    <flux:heading level="3" size="md" class="font-semibold text-black dark:text-white">
                        5.1. Confirmación
                    </flux:heading>
                    <ul class="list-disc pl-6 space-y-1 text-sm text-black/80 dark:text-white/80">
                        <li>Las reservas requieren confirmación del establecimiento</li>
                        <li>Recibirás notificación de confirmación o rechazo</li>
                        <li>Los establecimientos pueden establecer límites de tiempo</li>
                    </ul>
                </div>

                <!-- 5.2 Cancelaciones -->
                <div class="space-y-2">
                    <flux:heading level="3" size="md" class="font-semibold text-black dark:text-white">
                        5.2. Cancelaciones
                    </flux:heading>
                    <ul class="list-disc pl-6 space-y-1 text-sm text-black/80 dark:text-white/80">
                        <li>Consulta políticas de cancelación específicas</li>
                        <li>Notifica con anticipación según lo establecido</li>
                        <li>Cancelaciones repetitivas pueden afectar tu cuenta</li>
                    </ul>
                </div>
            </section>

            <!-- 6. Conducta Prohibida -->
            <section class="space-y-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
                <flux:heading level="2" size="lg" class="text-xl font-semibold">
                    6. Conducta Prohibida
                </flux:heading>

                <div class="space-y-2">
                    <flux:heading level="3" size="md" class="font-semibold text-black dark:text-white">
                        No está permitido
                    </flux:heading>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <flux:heading level="4" size="sm" class="font-semibold text-black dark:text-white">
                            Actividades Prohibidas
                        </flux:heading>
                        <ul class="text-sm space-y-1 text-black/80 dark:text-white/80">
                            <li>• Suplantación de identidad</li>
                            <li>• Spam o publicidad no autorizada</li>
                            <li>• Contenido ofensivo o ilegal</li>
                            <li>• Actividades fraudulentas</li>
                        </ul>
                    </div>
                    <div class="space-y-2">
                        <flux:heading level="4" size="sm" class="font-semibold text-black dark:text-white">
                            Conducta Inapropiada
                        </flux:heading>
                        <ul class="text-sm space-y-1 text-black/80 dark:text-white/80">
                            <li>• Acoso a otros usuarios</li>
                            <li>• Reseñas falsas o malintencionadas</li>
                            <li>• Uso excesivo de recursos</li>
                            <li>• Violación de derechos de autor</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- 7. Propiedad Intelectual -->
            <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
                <flux:heading level="2" size="lg" class="text-xl font-semibold">
                    7. Propiedad Intelectual
                </flux:heading>

                <flux:text class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                    Todos los derechos de propiedad intelectual relacionados con la plataforma, incluyendo pero no limitado a software, diseño, logotipos y contenido, son propiedad de <strong class="text-black dark:text-white">{{ config('app.name', 'SBVC') }}</strong> o de sus licenciantes.
                </flux:text>
            </section>

            <!-- 8. Limitación de Responsabilidad -->
            <section class="space-y-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
                <flux:heading level="2" size="lg" class="text-xl font-semibold">
                    8. Limitación de Responsabilidad
                </flux:heading>

                <div class="space-y-2">
                    <flux:heading level="3" size="sm" class="font-semibold text-black dark:text-white">
                        Limitaciones
                    </flux:heading>
                    <flux:text class="text-sm text-black/80 dark:text-white/80">
                        {{ config('app.name', 'SBVC') }} actúa como intermediario entre usuarios y establecimientos. No somos responsables por:
                    </flux:text>
                </div>

                <ul class="list-disc pl-6 space-y-2 text-sm text-black/80 dark:text-white/80">
                    <li>La calidad de productos/servicios de establecimientos</li>
                    <li>Disputas entre usuarios y establecimientos</li>
                    <li>Daños o pérdidas derivadas del uso de la plataforma</li>
                    <li>Interrupciones temporales del servicio</li>
                </ul>
            </section>

            <!-- 9. Modificaciones de los Términos -->
            <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
                <flux:heading level="2" size="lg" class="text-xl font-semibold">
                    9. Modificaciones de los Términos
                </flux:heading>

                <flux:text class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                    Nos reservamos el derecho de modificar estos Términos y Condiciones en cualquier momento. Las modificaciones entrarán en vigor inmediatamente después de su publicación en la plataforma. El uso continuado constituye aceptación de los términos modificados.
                </flux:text>
            </section>

            <!-- 10. Terminación -->
            <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
                <flux:heading level="2" size="lg" class="text-xl font-semibold">
                    10. Terminación
                </flux:heading>

                <flux:text class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                    Podemos suspender o terminar tu acceso a la plataforma si:
                </flux:text>
                <ul class="list-disc pl-6 mt-2 space-y-1 text-sm text-black/80 dark:text-white/80">
                    <li>Violas estos términos y condiciones</li>
                    <li>Realizas actividades fraudulentas</li>
                    <li>Incumples leyes aplicables</li>
                    <li>Pones en riesgo la seguridad de la plataforma</li>
                </ul>
            </section>

            <!-- 11. Ley Aplicable y Jurisdicción -->
            <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
                <flux:heading level="2" size="lg" class="text-xl font-semibold">
                    11. Ley Aplicable y Jurisdicción
                </flux:heading>

                <flux:text class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                    Estos términos se rigen por las leyes de [País]. Cualquier disputa será resuelta en los tribunales competentes de [Ciudad, País].
                </flux:text>
            </section>

            <!-- 12. Contacto -->
            <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
                <flux:heading level="2" size="lg" class="text-xl font-semibold">
                    12. Contacto
                </flux:heading>

                <flux:text class="text-sm text-black/80 dark:text-white/80">
                    Para consultas sobre estos Términos y Condiciones:
                </flux:text>
                <div class="mt-2 space-y-1 text-sm text-black/80 dark:text-white/80">
                    <p><strong>Email:</strong> legal@{{ parse_url(config('app.url'), PHP_URL_HOST) ?? 'tudominio.com' }}</p>
                    <p><strong>Dirección:</strong> [Tu dirección legal]</p>
                    <p><strong>Horario de atención:</strong> Lunes a Viernes, 9:00 - 18:00</p>
                </div>
            </section>

            <!-- Acceptance Box -->
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6 text-center">
                <flux:text class="text-sm font-semibold text-black/80 dark:text-white/80">
                    Al utilizar {{ config('app.name', 'nuestra plataforma') }}, confirmas que has leído, entendido y aceptas estos Términos y Condiciones en su totalidad.
                </flux:text>
            </div>

            <!-- Footer -->
            <div class="text-center text-xs text-black/60 dark:text-white/60 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                <flux:text class="text-xs text-black/60 dark:text-white/60">
                    &copy; {{ now()->year }} {{ config('app.name', 'Laravel') }}. Todos los derechos reservados.
                </flux:text>

                <div class="mt-2 space-x-4">
                    <a href="{{ url('/') }}" class="text-black dark:text-white hover:text-zinc-600 dark:hover:text-zinc-300 text-sm transition-colors">Inicio</a>
                    <a href="{{ route('privacy') }}" class="text-black dark:text-white hover:text-zinc-600 dark:hover:text-zinc-300 text-sm transition-colors">Política de Privacidad</a>
                </div>
            </div>

        </main>

    </body>

</html>