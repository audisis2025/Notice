<?php
/**
 * Nombre de la clase           : BusinessPackageController
 * Descripción de la clase      : Controlador que gestiona la contratación y renovación
 *                                de paquetes por parte de negocios
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 1.0
 * Fecha de mantenimiento       : 
 * Folio de mantenimiento       : 
 * Tipo de mantenimiento        :
 * Descripción del mantenimiento: 
 * Responsable                  : 
 * Revisor                      : 
 */
namespace App\Http\Controllers;

use App\Models\Package;
use App\Http\Requests\ContractPackageRequest;
use App\Services\BusinessPackageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

/**
 * BusinessPackageController
 * 
 * Controlador para gestionar contratación de paquetes.
 *
 * @package App\Http\Controllers
 */
class BusinessPackageController extends Controller
{
    /**
     * Instancia del servicio de suscripciones.
     *
     * @var BusinessPackageService
     */
    protected BusinessPackageService $businessPackageService;

    /**
     * Constructor del controlador.
     *
     * @param BusinessPackageService $businessPackageService
     */
    public function __construct(BusinessPackageService $businessPackageService)
    {
        $this->middleware('auth');
        $this->businessPackageService = $businessPackageService;
    }

    /**
     * Muestra los paquetes disponibles para contratar.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $packages = Package::active()->orderBy('price')->get();
        $business = Auth::user()->business;

        return view('business-packages.index', compact('packages', 'business'));
    }

    /**
     * Muestra el formulario de contratación de un paquete.
     *
     * @param Package $package
     * @return \Illuminate\View\View
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
     *
     * @param ContractPackageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function contract(ContractPackageRequest $request)
    {
        try {
            $business = Auth::user()->business;
            $package = Package::findOrFail($request->package_id);

            $this->businessPackageService->contractPackage(
                $business,
                $package,
                $request->only(['payment_method', 'card_number', 'card_holder', 'card_expiry', 'card_cvv']),
                $request->coupon_code
            );

            return redirect()->route('dashboard')
                ->with('success', 'Paquete contratado exitosamente.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al contratar el paquete: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el historial de paquetes contratados.
     *
     * @return \Illuminate\View\View
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