<?php

/**
 * Nombre de la clase           : BusinessPackageController
 * Descripción de la clase      : Controlador que gestiona la contratación de paquetes sin Services
 * Versión                      : 2.0
 * Fecha de mantenimiento       : 14/01/2026
 * Tipo de mantenimiento        : Perfectivo
 */

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\BusinessPackage;
use App\Http\Requests\ContractPackageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * BusinessPackageController
 * 
 * Controlador para gestionar contratación de paquetes.
 *
 * @package App\Http\Controllers
 */
class BusinessPackageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Muestra los paquetes disponibles para contratar.
     */
    public function index()
    {
        $packages = Package::active()->orderBy('price')->get();
        $business = Auth::user()->business;

        return view('business-packages.index', compact('packages', 'business'));
    }

    /**
     * Muestra el formulario de contratación de un paquete.
     */
    public function show(Package $package)
    {
        $business = Auth::user()->business;

        if (!$business) {
            return redirect()->route('business.create')
                ->with('error', 'Primero debes registrar tu negocio.');
        }

        return view('business-packages.contract', compact('package', 'business'));
    }

    /**
     * Procesa la contratación de un paquete.
     */
    public function contract(ContractPackageRequest $request)
    {
        DB::beginTransaction();

        try {
            $business = Auth::user()->business;
            $package = Package::findOrFail($request->package_id);

            // Contratar paquete usando el método del modelo BusinessPackage
            BusinessPackage::contractPackage(
                $business,
                $package,
                $request->only(['payment_method', 'card_number', 'card_holder', 'card_expiry', 'card_cvv']),
                $request->coupon_code
            );

            DB::commit();

            return redirect()->route('dashboard')
                ->with('success', 'Paquete contratado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Error al contratar el paquete: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el historial de paquetes contratados.
     */
    public function history()
    {
        $business = Auth::user()->business;
        $businessPackages = $business->businessPackages()
            ->with('package')
            ->latest()
            ->paginate(15);

        return view('business-packages.history', compact('businessPackages'));
    }
}
