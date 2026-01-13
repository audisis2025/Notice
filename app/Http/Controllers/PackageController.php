<?php
/**
 * Nombre de la clase           : PackageController
 * Descripción de la clase      : Controlador que gestiona las operaciones CRUD
 *                                de paquetes comerciales del sistema
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 1.0
 * Fecha de mantenimiento       : 12/01/2026
 * Folio de mantenimiento       : 2
 * Tipo de mantenimiento        : Perfectivo
 * Descripción del mantenimiento: Se agregaron métodos available() y subscribe()
 *                                para que BusinessAdministrator pueda contratar paquetes
 * Responsable                  : Jesús Núñez
 * Revisor                      : Jesús Núñez
 */
namespace App\Http\Controllers;

use App\Models\Package;
use App\Http\Requests\StorePackageRequest;
use App\Http\Requests\UpdatePackageRequest;
use App\Services\PackageService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\BusinessPackage;

/**
 * PackageController
 * 
 * Controlador para gestionar paquetes comerciales.
 *
 * @package App\Http\Controllers
 */
class PackageController extends Controller
{
    /**
     * Instancia del servicio de paquetes.
     *
     * @var PackageService
     */
    protected PackageService $packageService;

    /**
     * Constructor del controlador.
     *
     * @param PackageService $packageService
     */
    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
    }

    /**
     * Muestra el listado de paquetes.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $packages = Package::latest()->paginate(15);

        return view('packages.index', compact('packages'));
    }

    /**
     * Muestra el formulario para crear un nuevo paquete.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('packages.create');
    }

    /**
     * Almacena un nuevo paquete en la base de datos.
     *
     * @param StorePackageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StorePackageRequest $request)
    {
        try {
            $this->packageService->createPackage($request->validated());

            return redirect()->route('packages.index')
                ->with('success', 'Paquete creado exitosamente.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al crear el paquete: ' . $e->getMessage());
        }
    }

    /**
     * Muestra los detalles de un paquete específico.
     *
     * @param Package $package
     * @return \Illuminate\View\View
     */
    public function show(Package $package)
    {
        return view('packages.show', compact('package'));
    }

    /**
     * Muestra el formulario para editar un paquete.
     *
     * @param Package $package
     * @return \Illuminate\View\View
     */
    public function edit(Package $package)
    {
        return view('packages.edit', compact('package'));
    }

    /**
     * Actualiza un paquete en la base de datos.
     *
     * @param UpdatePackageRequest $request
     * @param Package $package
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdatePackageRequest $request, Package $package)
    {
        try {
            $this->packageService->updatePackage($package, $request->validated());

            return redirect()->route('packages.index')
                ->with('success', 'Paquete actualizado exitosamente.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al actualizar el paquete: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un paquete de la base de datos.
     *
     * @param Package $package
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Package $package)
    {
        try {
            $this->packageService->deletePackage($package);

            return redirect()->route('packages.index')
                ->with('success', 'Paquete eliminado exitosamente.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al eliminar el paquete: ' . $e->getMessage());
        }
    }

    /**
     * Activa o desactiva un paquete.
     *
     * @param Request $request
     * @param Package $package
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(Request $request, Package $package)
    {
        try {
            $this->packageService->togglePackageStatus($package, $request->is_active);

            $message = $request->is_active 
                ? 'Paquete activado.' 
                : 'Paquete desactivado.';

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Muestra los paquetes disponibles para contratar (BusinessAdministrator).
     *
     * @return \Illuminate\View\View
     */
    public function available()
    {
        // Solo BusinessAdministrator puede ver esta página
        if (Auth::user()->role !== 'BusinessAdministrator') {
            abort(403, 'Acceso no autorizado.');
        }

        $packages = Package::where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();

        $business = Auth::user()->business;
        
        // Obtener el paquete actual si existe
        $currentPackage = $business?->businessPackages()
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->with('package')
            ->latest()
            ->first();

        return view('packages.available', compact('packages', 'currentPackage'));
    }

    /**
     * Suscribir el negocio a un paquete.
     *
     * @param Package $package
     * @return \Illuminate\Http\RedirectResponse
     */
    public function subscribe(Package $package)
    {
        // Solo BusinessAdministrator puede contratar paquetes
        if (Auth::user()->role !== 'BusinessAdministrator') {
            abort(403, 'Acceso no autorizado.');
        }

        $business = Auth::user()->business;

        if (!$business) {
            return redirect()->route('business.create')
                ->with('error', 'Primero debes registrar tu negocio.');
        }

        // Verificar que el paquete esté activo
        if (!$package->is_active) {
            return back()->with('error', 'Este paquete no está disponible actualmente.');
        }

        try {
            // Crear la suscripción usando el servicio
            $this->packageService->subscribeBusinessToPackage($business, $package);

            return redirect()->route('dashboard')
                ->with('success', "¡Paquete {$package->name} contratado exitosamente!");
        } catch (\Exception $e) {
            return back()->with('error', 'Error al contratar el paquete: ' . $e->getMessage());
        }
    }
}