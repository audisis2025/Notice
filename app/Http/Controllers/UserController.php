<?php

/**
 * Nombre de la clase           : UserController
 * Descripción de la clase      : Controlador que gestiona las operaciones CRUD
 *                                de usuarios sin Services
 * Versión                      : 2.0
 * Fecha de mantenimiento       : 14/01/2026
 * Tipo de mantenimiento        : Perfectivo
 */

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * UserController
 * 
 * Controlador para gestionar usuarios del sistema.
 *
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage-users');
    }

    /**
     * Muestra el listado de usuarios.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filtros
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15);

        return view('users.index', compact('users'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Almacena un nuevo usuario en la base de datos.
     */
    public function store(StoreUserRequest $request)
    {
        DB::beginTransaction();

        try {
            User::createUser($request->validated());

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'Usuario creado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Error al crear el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Muestra los detalles de un usuario específico.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Muestra el formulario para editar un usuario.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Actualiza un usuario en la base de datos.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        DB::beginTransaction();

        try {
            $user->updateUser($request->validated());

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'Usuario actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Error al actualizar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un usuario de la base de datos.
     */
    public function destroy(User $user)
    {
        DB::beginTransaction();

        try {
            $user->delete();

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'Usuario eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Activa o desactiva un usuario.
     */
    public function toggleStatus(Request $request, User $user)
    {
        DB::beginTransaction();

        try {
            $user->toggleStatus($request->is_active);

            DB::commit();

            $message = $request->is_active ? 'Usuario activado.' : 'Usuario desactivado.';

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Error al cambiar estado: ' . $e->getMessage());
        }
    }
}
