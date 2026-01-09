<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Http\Requests\ValidateCouponRequest;
use App\Services\CouponService;
use Illuminate\Http\Request;

/**
 * CouponController
 * 
 * Controlador para gestionar cupones de descuento.
 *
 * @package App\Http\Controllers
 */
class CouponController extends Controller
{
    /**
     * Instancia del servicio de cupones.
     *
     * @var CouponService
     */
    protected CouponService $couponService;

    /**
     * Constructor del controlador.
     *
     * @param CouponService $couponService
     */
    public function __construct(CouponService $couponService)
    {
        $this->middleware('can:manage-coupons');
        $this->couponService = $couponService;
    }

    /**
     * Muestra el listado de cupones.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Coupon::query();

        if ($request->filled('status')) {
            if ($request->status === 'available') {
                $query->where('is_active', true)
                    ->where('is_used', false)
                    ->where('expiration_date', '>=', now());
            } elseif ($request->status === 'used') {
                $query->where('is_used', true);
            } elseif ($request->status === 'expired') {
                $query->where('expiration_date', '<', now());
            }
        }

        $coupons = $query->latest()->paginate(15);

        return view('coupons.index', compact('coupons'));
    }

    /**
     * Muestra el formulario para crear un nuevo cupón.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('coupons.create');
    }

    /**
     * Almacena un nuevo cupón en la base de datos.
     *
     * @param StoreCouponRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCouponRequest $request)
    {
        try {
            $this->couponService->generateCoupon($request->validated());

            return redirect()->route('coupons.index')
                ->with('success', 'Cupón generado exitosamente.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al generar el cupón: ' . $e->getMessage());
        }
    }

    /**
     * Muestra los detalles de un cupón específico.
     *
     * @param Coupon $coupon
     * @return \Illuminate\View\View
     */
    public function show(Coupon $coupon)
    {
        return view('coupons.show', compact('coupon'));
    }

    /**
     * Muestra el formulario para editar un cupón.
     *
     * @param Coupon $coupon
     * @return \Illuminate\View\View
     */
    public function edit(Coupon $coupon)
    {
        return view('coupons.edit', compact('coupon'));
    }

    /**
     * Actualiza un cupón en la base de datos.
     *
     * @param UpdateCouponRequest $request
     * @param Coupon $coupon
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCouponRequest $request, Coupon $coupon)
    {
        try {
            $this->couponService->updateCoupon($coupon, $request->validated());

            return redirect()->route('coupons.index')
                ->with('success', 'Cupón actualizado exitosamente.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al actualizar el cupón: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un cupón de la base de datos.
     *
     * @param Coupon $coupon
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Coupon $coupon)
    {
        try {
            $this->couponService->deleteCoupon($coupon);

            return redirect()->route('coupons.index')
                ->with('success', 'Cupón eliminado exitosamente.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al eliminar el cupón: ' . $e->getMessage());
        }
    }

    /**
     * Valida un cupón por su código (API para AJAX).
     *
     * @param ValidateCouponRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validate(ValidateCouponRequest $request)
    {
        try {
            $coupon = $this->couponService->validateCoupon($request->code);

            return response()->json([
                'valid' => true,
                'coupon' => $coupon,
                'message' => 'Cupón válido.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'valid' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}