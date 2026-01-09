<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Http\Requests\StorePackageRequest;
use App\Http\Requests\UpdatePackageRequest;
use App\Services\PackageService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

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
        $this->middleware('can:manage-packages');
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
}