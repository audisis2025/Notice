<?php

/**
 * Nombre de la clase           : BusinessController
 * Descripción de la clase      : Controlador que gestiona las operaciones CRUD
 *                                de negocios y su configuración
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 1.0
 * Fecha de mantenimiento       : 12/01/2026
 * Folio de mantenimiento       : 1
 * Tipo de mantenimiento        : Perfectivo
 * Descripción del mantenimiento: Tiene como objetivo mejorar la funcionalidad
 *                                del controlador de negocios agregando nuevas
 *                                características o optimizando las existentes.
 * Responsable                  : Jesús Núñez
 * Revisor                      : Jesús Núñez
 */

namespace App\Http\Controllers;

use App\Models\Business;
use App\Http\Requests\StoreBusinessRequest;
use App\Http\Requests\UpdateBusinessRequest;
use App\Http\Requests\SuspendBusinessRequest;
use App\Services\BusinessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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
     * Instancia del servicio de negocios.
     *
     * @var BusinessService
     */
    protected BusinessService $businessService;

    /**
     * Constructor del controlador.
     *
     * @param BusinessService $businessService
     */
    public function __construct(BusinessService $businessService)
    {
        $this->businessService = $businessService;
    }

    /**
     * Muestra el listado de negocios.
     *
     * @param Request $request
     * @return \Illuminate\View\View
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
     *
     * @return \Illuminate\View\View
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
     *
     * @param StoreBusinessRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreBusinessRequest $request)
    {
        // ✅ LOG: Datos recibidos
        \Log::info('=== INICIO REGISTRO DE NEGOCIO ===');
        \Log::info('Datos recibidos:', $request->all());
        \Log::info('Datos validados:', $request->validated());
        \Log::info('Usuario:', [
            'id' => Auth::id(),
            'email' => Auth::user()->email,
            'role' => Auth::user()->role
        ]);

        try {
            $user = Auth::user();

            // ✅ LOG: Verificar si ya tiene negocio
            if ($user->business) {
                \Log::warning('Usuario ya tiene negocio registrado', ['business_id' => $user->business->id]);
                return redirect()->route('dashboard')
                    ->with('info', 'Ya tienes un negocio registrado.');
            }

            \Log::info('Llamando a BusinessService::registerBusiness');

            $business = $this->businessService->registerBusiness($user, $request->validated());

            $user->refresh();

            \Log::info('Negocio registrado exitosamente', [
                'business_id' => $business->id,
                'business_name' => $business->business_name
            ]);

            \Log::info('=== FIN REGISTRO DE NEGOCIO (ÉXITO) ===');

            return redirect()->route('packages.available')
                ->with('success', '¡Negocio registrado exitosamente! Ahora puedes contratar un paquete.');
        } catch (\Exception $e) {
            \Log::error('=== ERROR EN REGISTRO DE NEGOCIO ===');
            \Log::error('Mensaje:', ['error' => $e->getMessage()]);
            \Log::error('Archivo:', ['file' => $e->getFile()]);
            \Log::error('Línea:', ['line' => $e->getLine()]);
            \Log::error('Trace:', ['trace' => $e->getTraceAsString()]);

            return back()->withInput()
                ->with('error', 'Error al registrar el negocio: ' . $e->getMessage());
        }
    }

    /**
     * Muestra los detalles de un negocio específico.
     *
     * @param Business $business
     * @return \Illuminate\View\View
     */
    public function show(Business $business)
    {
        $business->load('user', 'orders', 'ratings');

        return view('businesses.show', compact('business'));
    }

    /**
     * Muestra el formulario para editar un negocio.
     *
     * @param Business $business
     * @return \Illuminate\View\View
     */
    public function edit(Business $business)
    {
        return view('businesses.edit', compact('business'));
    }

    /**
     * Actualiza un negocio en la base de datos.
     *
     * @param UpdateBusinessRequest $request
     * @param Business $business
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateBusinessRequest $request, Business $business)
    {
        try {
            $this->businessService->updateBusiness($business, $request->validated());

            return redirect()->route('businesses.show', $business)
                ->with('success', 'Negocio actualizado exitosamente.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al actualizar el negocio: ' . $e->getMessage());
        }
    }

    /**
     * Suspende el servicio de un negocio.
     *
     * @param SuspendBusinessRequest $request
     * @param Business $business
     * @return \Illuminate\Http\RedirectResponse
     */
    public function suspend(SuspendBusinessRequest $request, Business $business)
    {
        try {
            $this->businessService->suspendBusiness($business, $request->reason);

            return back()->with('success', 'Servicio del negocio suspendido.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al suspender: ' . $e->getMessage());
        }
    }

    /**
     * Reactiva el servicio de un negocio.
     *
     * @param Business $business
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reactivate(Business $business)
    {
        try {
            $this->businessService->reactivateBusiness($business);

            return back()->with('success', 'Servicio del negocio reactivado.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al reactivar: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza la configuración de calificaciones.
     *
     * @param Request $request
     * @param Business $business
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleRatings(Request $request, Business $business)
    {
        try {
            $this->businessService->toggleRatings($business, $request->can_be_rated);

            $message = $request->can_be_rated
                ? 'Calificaciones habilitadas.'
                : 'Calificaciones deshabilitadas.';

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza el período de entrega.
     *
     * @param Request $request
     * @param Business $business
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateDeliveryPeriod(Request $request, Business $business)
    {
        $request->validate([
            'delivery_period_minutes' => 'required|integer|min:5',
        ]);

        try {
            $this->businessService->updateDeliveryPeriod(
                $business,
                $request->delivery_period_minutes
            );

            return back()->with('success', 'Período de entrega actualizado.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
