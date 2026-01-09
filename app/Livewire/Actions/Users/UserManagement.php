<?php
/**
 * Nombre de la clase           : UserManagement
 * Descripción de la clase      : Componente Livewire que gestiona la interfaz
 *                                interactiva para administrar usuarios
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
namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;

class UserManagement extends Component
{
    use WithPagination;

    // Propiedades de búsqueda y filtros
    public $search = '';
    public $roleFilter = '';
    public $statusFilter = '';

    // Propiedades del modal
    public $showModal = false;
    public $editMode = false;
    public $userId = null;

    // Propiedades del formulario
    public $name = '';
    public $email = '';
    public $phone = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = 'MobileUser';
    public $birth_date = '';
    public $is_active = true;

    /**
     * Reglas de validación
     */
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $this->userId,
            'phone' => 'required|string|max:20|unique:users,phone,' . $this->userId,
            'password' => $this->editMode ? 'nullable|min:8|confirmed' : 'required|min:8|confirmed',
            'birth_date' => 'nullable|date|before:today',
            'role' => 'required|in:SuperAdministrator,BusinessAdministrator,MobileUser',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Mensajes de validación personalizados
     */
    protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'phone.required' => 'El teléfono es obligatorio.',
        'phone.unique' => 'Este teléfono ya está registrado.',
        'email.email' => 'El correo debe ser válido.',
        'email.unique' => 'Este correo ya está registrado.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
        'role.required' => 'El rol es obligatorio.',
    ];

    /**
     * Validación en tiempo real
     */
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    /**
     * Resetea la paginación cuando cambia la búsqueda
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Renderiza el componente
     */
    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%")
                      ->orWhere('phone', 'like', "%{$this->search}%");
                });
            })
            ->when($this->roleFilter, function ($query) {
                $query->where('role', $this->roleFilter);
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter);
            })
            ->latest()
            ->paginate(15);

        return view('livewire.users.user-management', [
            'users' => $users
        ]);
    }

    /**
     * Abre el modal para crear usuario
     */
    public function create()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    /**
     * Abre el modal para editar usuario
     */
    public function edit($userId)
    {
        $user = User::findOrFail($userId);

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->role = $user->role;
        $this->birth_date = $user->birth_date?->format('Y-m-d');
        $this->is_active = $user->is_active;
        $this->password = '';
        $this->password_confirmation = '';

        $this->editMode = true;
        $this->showModal = true;
    }

    /**
     * Guarda el usuario (crear o actualizar)
     */
    public function save()
    {
        $this->validate();

        try {
            $userService = app(UserService::class);

            if ($this->editMode) {
                $user = User::findOrFail($this->userId);
                $userService->updateUser($user, $this->getFormData());
                $message = 'Usuario actualizado exitosamente';
            } else {
                $userService->createUser($this->getFormData());
                $message = 'Usuario creado exitosamente';
            }

            $this->showModal = false;
            $this->resetForm();
            $this->dispatch('success', message: $message);

        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Confirma y elimina un usuario
     */
    public function deleteConfirm($userId)
    {
        $this->userId = $userId;
        $this->dispatch('confirm-delete', userId: $userId);
    }

    /**
     * Elimina el usuario
     */
    #[On('user-delete-confirmed')]
    public function delete($userId)
    {
        try {
            $userService = app(UserService::class);
            $user = User::findOrFail($userId);
            $userService->deleteUser($user);

            $this->dispatch('success', message: 'Usuario eliminado exitosamente');
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error al eliminar: ' . $e->getMessage());
        }
    }

    /**
     * Cambia el estado del usuario
     */
    public function toggleStatus($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $userService = app(UserService::class);
            $userService->toggleUserStatus($user, !$user->is_active);

            $message = $user->is_active ? 'Usuario desactivado' : 'Usuario activado';
            $this->dispatch('toast', message: $message, type: 'success');

        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Cierra el modal
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    /**
     * Resetea el formulario
     */
    private function resetForm()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = 'MobileUser';
        $this->birth_date = '';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    /**
     * Obtiene los datos del formulario
     */
    private function getFormData()
    {
        $data = [
            'name' => $this->name,
            'email' => $this->email ?: null,
            'phone' => $this->phone,
            'role' => $this->role,
            'birth_date' => $this->birth_date ?: null,
            'is_active' => $this->is_active,
        ];

        if (!empty($this->password)) {
            $data['password'] = $this->password;
            $data['password_confirmation'] = $this->password_confirmation;
        }

        return $data;
    }
}