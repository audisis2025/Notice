<?php

/**
 * Nombre de la clase           : BusinessController
 * Descripción de la clase      : Controlador que gestiona las operaciones CRUD
 *                                de negocios sin usar Services
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Versión                      : 2.1
 * Fecha de mantenimiento       : 15/01/2026
 * Folio de mantenimiento       : 3
 * Tipo de mantenimiento        : Perfectivo
 * Descripción del mantenimiento: Mejora de mensajes SweetAlert sin modificar lógica
 * Responsable                  : Sistema
 * Revisor                      : Jesús Núñez
 */

namespace App\Http\Controllers;

use App\Models\Business;
use App\Http\Requests\StoreBusinessRequest;
use App\Http\Requests\UpdateBusinessRequest;
use App\Http\Requests\SuspendBusinessRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * BusinessController
 * 
 * Controlador para gestionar negocios del sistema.
 *
 * @package App\Http\Controllers
 */
class BusinessController extends Controller
{
    /**
     * Muestra el listado de negocios.
     */
    public function index(Request $request)
    {
        $query = Business::with('user');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                    ->orWhere('legal_name', 'like', "%{$search}%")
                    ->orWhere('tax_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $businesses = $query->latest()->paginate(15);

        return view('businesses.index', compact('businesses'));
    }

    /**
     * Muestra el formulario para registrar un nuevo negocio.
     */
    public function create()
    {
        // Si ya tiene negocio, redirigir al dashboard
        if (Auth::user()->business) {
            return redirect()->route('dashboard')
                ->with('info', 'Ya tienes un negocio registrado.');
        }

        return view('businesses.create');
    }

    /**
     * Almacena un nuevo negocio en la base de datos.
     */
    public function store(StoreBusinessRequest $request)
    {
        Log::info('=== INICIO REGISTRO DE NEGOCIO ===');
        Log::info('Datos recibidos:', $request->all());

        DB::beginTransaction();

        try {
            $user = Auth::user();

            // Verificar si ya tiene negocio
            if ($user->business) {
                Log::warning('Usuario ya tiene negocio registrado', ['business_id' => $user->business->id]);
                return redirect()->route('dashboard')
                    ->with('info', 'Ya tienes un negocio registrado.');
            }

            // Crear negocio usando el método del modelo
            $business = Business::createBusiness($user, $request->validated());

            DB::commit();

            Log::info('Negocio registrado exitosamente', [
                'business_id' => $business->id,
                'business_name' => $business->business_name
            ]);

            Log::info('=== FIN REGISTRO DE NEGOCIO (ÉXITO) ===');

            return redirect()->route('select.package')
                ->with('success', '¡Negocio registrado exitosamente! Ahora selecciona un paquete para comenzar.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('=== ERROR EN REGISTRO DE NEGOCIO ===');
            Log::error('Mensaje:', ['error' => $e->getMessage()]);
            Log::error('Archivo:', ['file' => $e->getFile()]);
            Log::error('Línea:', ['line' => $e->getLine()]);

            return back()->withInput()
                ->with('error', 'Error al registrar el negocio: ' . $e->getMessage());
        }
    }

    /**
     * Muestra los detalles de un negocio específico.
     */
    public function show(Business $business)
    {
        $business->load('user', 'orders', 'ratings');

        return view('businesses.show', compact('business'));
    }

    /**
     * Muestra el formulario para editar un negocio.
     */
    public function edit(Business $business)
    {
        return view('businesses.edit', compact('business'));
    }

    /**
     * Actualiza un negocio en la base de datos.
     */
    public function update(UpdateBusinessRequest $request, Business $business)
    {
        DB::beginTransaction();

        try {
            // Actualizar negocio usando el método del modelo
            $business->updateBusiness($request->validated());

            DB::commit();

            return redirect()->route('dashboard')
                ->with('success', '¡Negocio actualizado exitosamente!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Error al actualizar el negocio: ' . $e->getMessage());
        }
    }

    /**
     * Suspende el servicio de un negocio.
     */
    public function suspend(SuspendBusinessRequest $request, Business $business)
    {
        DB::beginTransaction();

        try {
            $business->suspend($request->reason);

            DB::commit();

            return back()
                ->with('success', '¡Servicio del negocio suspendido exitosamente!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Error al suspender el negocio: ' . $e->getMessage());
        }
    }

    /**
     * Reactiva el servicio de un negocio.
     */
    public function reactivate(Business $business)
    {
        DB::beginTransaction();

        try {
            $business->reactivate();

            DB::commit();

            return back()
                ->with('success', '¡Servicio del negocio reactivado exitosamente!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Error al reactivar el negocio: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza la configuración de calificaciones.
     */
    public function toggleRatings(Request $request, Business $business)
    {
        DB::beginTransaction();

        try {
            $business->toggleRatings($request->can_be_rated);

            DB::commit();

            $message = $request->can_be_rated
                ? '¡Calificaciones habilitadas exitosamente!'
                : '¡Calificaciones deshabilitadas exitosamente!';

            return back()
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Error al actualizar configuración de calificaciones: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza el período de entrega.
     */
    public function updateDeliveryPeriod(Request $request, Business $business)
    {
        $request->validate([
            'delivery_period_minutes' => 'required|integer|min:5',
        ]);

        DB::beginTransaction();

        try {
            $business->updateDeliveryPeriod($request->delivery_period_minutes);

            DB::commit();

            return back()
                ->with('success', '¡Período de entrega actualizado exitosamente!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Error al actualizar el período de entrega: ' . $e->getMessage());
        }
    }
}