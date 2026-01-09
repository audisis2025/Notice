<x-app-layout>
    @section('page-title', 'Generar Reporte')

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <x-heroicon-o-chart-bar class="w-8 h-8 mr-3" />
                    Generar Reporte de Órdenes
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Selecciona el rango de fechas para generar un reporte detallado de tus órdenes.
                </p>
            </div>

            <form action="{{ route('reports.generate') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Rango de fechas --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input 
                        label="Fecha inicio"
                        name="start_date"
                        type="date"
                        :value="old('start_date', now()->startOfMonth()->format('Y-m-d'))"
                        required
                        :error="$errors->first('start_date')"
                    >
                        <x-slot:iconTrailing>
                            <x-heroicon-o-calendar class="w-5 h-5" />
                        </x-slot:iconTrailing>
                    </flux:input>

                    <flux:input 
                        label="Fecha fin"
                        name="end_date"
                        type="date"
                        :value="old('end_date', now()->format('Y-m-d'))"
                        required
                        :error="$errors->first('end_date')"
                    >
                        <x-slot:iconTrailing>
                            <x-heroicon-o-calendar class="w-5 h-5" />
                        </x-slot:iconTrailing>
                    </flux:input>
                </div>

                {{-- Botones de rango rápido --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rangos rápidos:</label>
                    <div class="flex flex-wrap gap-2">
                        <flux:button 
                            type="button"
                            variant="ghost"
                            size="sm"
                            onclick="setDateRange('today')"
                        >
                            Hoy
                        </flux:button>
                        <flux:button 
                            type="button"
                            variant="ghost"
                            size="sm"
                            onclick="setDateRange('week')"
                        >
                            Esta Semana
                        </flux:button>
                        <flux:button 
                            type="button"
                            variant="ghost"
                            size="sm"
                            onclick="setDateRange('month')"
                        >
                            Este Mes
                        </flux:button>
                        <flux:button 
                            type="button"
                            variant="ghost"
                            size="sm"
                            onclick="setDateRange('last_month')"
                        >
                            Mes Pasado
                        </flux:button>
                    </div>
                </div>

                {{-- Información adicional --}}
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                    <div class="flex items-start">
                        <x-heroicon-o-information-circle class="w-5 h-5 text-blue-600 mr-3 mt-0.5" />
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold mb-1">El reporte incluirá:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Total de órdenes en el período</li>
                                <li>Ingresos totales y promedio</li>
                                <li>Distribución por estado</li>
                                <li>Detalle de cada orden</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="flex justify-end space-x-3 pt-6 border-t">
                    <flux:button 
                        variant="ghost"
                        type="button"
                        href="{{ route('dashboard') }}"
                    >
                        <x-heroicon-o-x-mark class="w-5 h-5 mr-2" />
                        Cancelar
                    </flux:button>

                    <flux:button 
                        variant="primary"
                        type="submit"
                        class="bg-black hover:bg-[#494949]"
                    >
                        <x-heroicon-o-chart-bar class="w-5 h-5 mr-2" />
                        Generar Reporte
                    </flux:button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function setDateRange(range) {
            const today = new Date();
            let startDate, endDate;

            switch(range) {
                case 'today':
                    startDate = endDate = today;
                    break;
                case 'week':
                    startDate = new Date(today.setDate(today.getDate() - today.getDay()));
                    endDate = new Date();
                    break;
                case 'month':
                    startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                    endDate = new Date();
                    break;
                case 'last_month':
                    startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    endDate = new Date(today.getFullYear(), today.getMonth(), 0);
                    break;
            }

            document.querySelector('input[name="start_date"]').value = formatDate(startDate);
            document.querySelector('input[name="end_date"]').value = formatDate(endDate);
        }

        function formatDate(date) {
            return date.toISOString().split('T')[0];
        }
    </script>
    @endpush
</x-app-layout>