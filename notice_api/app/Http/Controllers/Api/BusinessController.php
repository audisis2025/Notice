<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// --- 1. ¡AQUÍ ESTÁ LA CORRECCIÓN! ---
// Debe ser 'App\Models\Business' porque tu versión de
// Laravel SÍ usa la carpeta 'Models'.
use App\Models\Business;

class BusinessController extends Controller
{
    /**
     * Muestra una lista de todos los negocios.
     */
    public function index()
    {
        // 1. Ve a la base de datos y trae TODOS los negocios
        $businesses = Business::all();
        
        // 2. Devuélvelos como un JSON
        return response()->json($businesses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    // (Corregí el error de dedo 'publicS' de antes)
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}