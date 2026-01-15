<?php

/**
 * Nombre de la clase           : CouponController
 * Descripción de la clase      : Controlador que gestiona las operaciones CRUD
 *                                de cupones sin Services
 * Versión                      : 2.2
 * Fecha de mantenimiento       : 15/01/2026
 * Tipo de mantenimiento        : Perfectivo
 * Descripción del mantenimiento: Mejora de mensajes SweetAlert sin modificar lógica
 */

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Http\Requests\ValidateCouponRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

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
     * Muestra el listado de cupones.
     */
    public function index(Request $request)
    {
        // Verificar autorización
        Gate::authorize('manage-coupons');

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
     */
    public function create()
    {
        // Verificar autorización
        Gate::authorize('manage-coupons');

        return view('coupons.create');
    }

    /**
     * Almacena un nuevo cupón en la base de datos.
     */
    public function store(StoreCouponRequest $request)
    {
        // Verificar autorización
        Gate::authorize('manage-coupons');

        DB::beginTransaction();

        try {
            Coupon::generateCoupon($request->validated());

            DB::commit();

            return redirect()->route('coupons.index')
                ->with('success', '¡Cupón generado exitosamente!');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Error al generar el cupón: ' . $e->getMessage());
        }
    }

    /**
     * Muestra los detalles de un cupón específico.
     */
    public function show(Coupon $coupon)
    {
        // Verificar autorización
        Gate::authorize('manage-coupons');

        return view('coupons.show', compact('coupon'));
    }

    /**
     * Muestra el formulario para editar un cupón.
     */
    public function edit(Coupon $coupon)
    {
        // Verificar autorización
        Gate::authorize('manage-coupons');

        return view('coupons.edit', compact('coupon'));
    }

    /**
     * Actualiza un cupón en la base de datos.
     */
    public function update(UpdateCouponRequest $request, Coupon $coupon)
    {
        // Verificar autorización
        Gate::authorize('manage-coupons');

        DB::beginTransaction();

        try {
            $coupon->updateCoupon($request->validated());

            DB::commit();

            return redirect()->route('coupons.index')
                ->with('success', '¡Cupón actualizado exitosamente!');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Error al actualizar el cupón: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un cupón de la base de datos.
     */
    public function destroy(Coupon $coupon)
    {
        // Verificar autorización
        Gate::authorize('manage-coupons');

        DB::beginTransaction();

        try {
            $coupon->delete();

            DB::commit();

            return redirect()->route('coupons.index')
                ->with('success', '¡Cupón eliminado exitosamente!');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Error al eliminar el cupón: ' . $e->getMessage());
        }
    }

    /**
     * Valida un cupón por su código (API para AJAX).
     */
    public function validate(ValidateCouponRequest $request)
    {
        try {
            $coupon = Coupon::validateCoupon($request->code);

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