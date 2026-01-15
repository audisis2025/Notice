<?php

/**
 * Nombre de la clase           : PackageController
 * Descripción de la clase      : Controlador que gestiona las operaciones CRUD
 *                                de paquetes comerciales sin Services
 * Versión                      : 2.0
 * Fecha de mantenimiento       : 14/01/2026
 * Tipo de mantenimiento        : Perfectivo
 * Descripción del mantenimiento: Eliminación de Services - Lógica en Modelos y Controlador
 */

namespace App\Http\Controllers;

use App\Models\Package;
use App\Http\Requests\StorePackageRequest;
use App\Http\Requests\UpdatePackageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
     * Muestra el listado de paquetes.
     */
    public function index()
    {
        $packages = Package::latest()->paginate(15);

        return view('packages.index', compact('packages'));
    }

    /**
     * Muestra el formulario para crear un nuevo paquete.
     */
    public function create()
    {
        return view('packages.create');
    }

    /**
     * Almacena un nuevo paquete en la base de datos.
     */
    public function store(StorePackageRequest $request)
    {
        DB::beginTransaction();

        try {
            // Crear paquete usando el método del modelo
            Package::createPackage($request->validated());

            DB::commit();

            return redirect()->route('packages.index')
                ->with('success', 'Paquete creado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Error al crear el paquete: ' . $e->getMessage());
        }
    }

    /**
     * Muestra los detalles de un paquete específico.
     */
    public function show(Package $package)
    {
        return view('packages.show', compact('package'));
    }

    /**
     * Muestra el formulario para editar un paquete.
     */
    public function edit(Package $package)
    {
        return view('packages.edit', compact('package'));
    }

    /**
     * Actualiza un paquete en la base de datos.
     */
    public function update(UpdatePackageRequest $request, Package $package)
    {
        DB::beginTransaction();

        try {
            $package->updatePackage($request->validated());

            DB::commit();

            return redirect()->route('packages.index')
                ->with('success', 'Paquete actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Error al actualizar el paquete: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un paquete de la base de datos.
     */
    public function destroy(Package $package)
    {
        DB::beginTransaction();

        try {
            $package->delete();

            DB::commit();

            return redirect()->route('packages.index')
                ->with('success', 'Paquete eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Error al eliminar el paquete: ' . $e->getMessage());
        }
    }

    /**
     * Activa o desactiva un paquete.
     */
    public function toggleStatus(Request $request, Package $package)
    {
        DB::beginTransaction();

        try {
            $package->toggleStatus($request->is_active);

            DB::commit();

            $message = $request->is_active 
                ? 'Paquete activado.' 
                : 'Paquete desactivado.';

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Muestra los paquetes disponibles para contratar (BusinessAdministrator).
     */
    public function available()
    {
        // Solo BusinessAdministrator puede ver esta página
        if (Auth::user()->role !== 'BusinessAdministrator') {
            abort(403, 'Acceso no autorizado.');
        }

        $packages = Package::getActivePackages();
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

        DB::beginTransaction();

        try {
            // Suscribir negocio usando el método del modelo Package
            $package->subscribeBusinessTo($business);

            DB::commit();

            return redirect()->route('dashboard')
                ->with('success', "¡Paquete {$package->name} contratado exitosamente!");

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Error al contratar el paquete: ' . $e->getMessage());
        }
    }
}
