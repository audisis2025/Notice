#!/usr/bin/env php
<?php
/**
 * Script de Diagnóstico de Login
 * 
 * Ejecutar: php diagnose_login.php
 */

// Cargar Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║         DIAGNÓSTICO DE LOGIN - SUPER ADMINISTRADOR           ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

// 1. Verificar que el usuario existe
echo "1️⃣  VERIFICANDO USUARIO EN BASE DE DATOS...\n";
echo "─────────────────────────────────────────────────────────────────\n";

$user = \App\Models\User::where('email', 'admin@sisnotice.com')->first();

if (!$user) {
    echo "❌ ERROR: El usuario admin@sisnotice.com NO EXISTE en la base de datos\n";
    echo "   Solución: Ejecuta: php artisan db:seed --class=UserSeeder\n\n";
    exit(1);
}

echo "✅ Usuario encontrado:\n";
echo "   - ID: {$user->id}\n";
echo "   - Nombre: {$user->name}\n";
echo "   - Email: {$user->email}\n";
echo "   - Teléfono: {$user->phone}\n";
echo "   - Rol: {$user->role}\n";
echo "   - Activo: " . ($user->is_active ? 'SÍ' : 'NO') . "\n";
echo "   - Email verificado: " . ($user->email_verified_at ? 'SÍ' : 'NO') . "\n\n";

// 2. Verificar que el usuario está activo
echo "2️⃣  VERIFICANDO ESTADO DEL USUARIO...\n";
echo "─────────────────────────────────────────────────────────────────\n";

if (!$user->is_active) {
    echo "❌ ERROR: El usuario está INACTIVO\n";
    echo "   Solución: Actualiza is_active = 1 en la base de datos\n\n";
} else {
    echo "✅ Usuario está activo\n\n";
}

// 3. Verificar el hash de la contraseña
echo "3️⃣  VERIFICANDO HASH DE CONTRASEÑA...\n";
echo "─────────────────────────────────────────────────────────────────\n";

$passwordToTest = 'Admin123!';
$isPasswordValid = \Illuminate\Support\Facades\Hash::check($passwordToTest, $user->password);

echo "   - Hash almacenado: " . substr($user->password, 0, 50) . "...\n";
echo "   - Probando password: '{$passwordToTest}'\n";
echo "   - Resultado: " . ($isPasswordValid ? '✅ CORRECTO' : '❌ INCORRECTO') . "\n\n";

if (!$isPasswordValid) {
    echo "⚠️  La contraseña '{$passwordToTest}' NO coincide con el hash\n";
    echo "   Posibles causas:\n";
    echo "   - El seeder no se ejecutó correctamente\n";
    echo "   - La contraseña fue modificada manualmente\n";
    echo "   - Hay un problema con bcrypt/hashing\n\n";
    
    echo "   💡 SOLUCIÓN RÁPIDA:\n";
    echo "   Actualiza la contraseña manualmente:\n";
    $newHash = \Illuminate\Support\Facades\Hash::make('Admin123!');
    echo "   UPDATE users SET password = '$newHash' WHERE email = 'admin@sisnotice.com';\n\n";
}

// 4. Verificar configuración de guards
echo "4️⃣  VERIFICANDO CONFIGURACIÓN DE AUTENTICACIÓN...\n";
echo "─────────────────────────────────────────────────────────────────\n";

$authGuard = config('auth.defaults.guard');
$authProvider = config('auth.guards.' . $authGuard . '.provider');
$providerModel = config('auth.providers.' . $authProvider . '.model');

echo "   - Guard por defecto: {$authGuard}\n";
echo "   - Provider: {$authProvider}\n";
echo "   - Modelo: {$providerModel}\n\n";

if ($providerModel !== \App\Models\User::class) {
    echo "❌ ERROR: El modelo de autenticación no es correcto\n";
    echo "   Esperado: App\\Models\\User\n";
    echo "   Actual: {$providerModel}\n\n";
} else {
    echo "✅ Configuración de autenticación correcta\n\n";
}

// 5. Verificar que la tabla users tiene los campos correctos
echo "5️⃣  VERIFICANDO ESTRUCTURA DE LA TABLA USERS...\n";
echo "─────────────────────────────────────────────────────────────────\n";

try {
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('users');
    
    $requiredColumns = ['id', 'name', 'email', 'password', 'role', 'is_active'];
    $missingColumns = array_diff($requiredColumns, $columns);
    
    if (empty($missingColumns)) {
        echo "✅ Todos los campos requeridos existen\n";
        echo "   Columnas: " . implode(', ', $columns) . "\n\n";
    } else {
        echo "❌ ERROR: Faltan columnas en la tabla users\n";
        echo "   Faltan: " . implode(', ', $missingColumns) . "\n\n";
    }
} catch (\Exception $e) {
    echo "❌ ERROR al verificar estructura: " . $e->getMessage() . "\n\n";
}

// 6. Simular login
echo "6️⃣  SIMULANDO PROCESO DE LOGIN...\n";
echo "─────────────────────────────────────────────────────────────────\n";

try {
    $credentials = [
        'email' => 'admin@sisnotice.com',
        'password' => 'Admin123!',
    ];
    
    echo "   Intentando autenticar con:\n";
    echo "   - Email: {$credentials['email']}\n";
    echo "   - Password: {$credentials['password']}\n\n";
    
    if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
        echo "✅ ¡LOGIN EXITOSO! El usuario puede iniciar sesión\n\n";
        \Illuminate\Support\Facades\Auth::logout();
    } else {
        echo "❌ LOGIN FALLÓ - El usuario NO puede iniciar sesión\n\n";
        
        echo "   🔍 DIAGNÓSTICO ADICIONAL:\n";
        
        // Verificar si es por contraseña
        if ($user && !\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            echo "   - Causa: Contraseña incorrecta\n";
        }
        
        // Verificar si es por usuario inactivo
        if ($user && !$user->is_active) {
            echo "   - Causa: Usuario inactivo\n";
        }
        
        // Verificar si es por email no verificado (si se requiere)
        if ($user && !$user->email_verified_at && config('auth.verification')) {
            echo "   - Causa: Email no verificado\n";
        }
        
        echo "\n";
    }
    
} catch (\Exception $e) {
    echo "❌ ERROR en simulación: " . $e->getMessage() . "\n\n";
}

// 7. Resumen y recomendaciones
echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║                    RESUMEN Y SOLUCIONES                      ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

if ($isPasswordValid && $user->is_active) {
    echo "✅ TODO ESTÁ CORRECTO\n";
    echo "   El usuario debería poder iniciar sesión.\n\n";
    echo "   Si aún no puedes entrar, verifica:\n";
    echo "   1. Que estás usando el email: admin@sisnotice.com\n";
    echo "   2. Que estás usando la contraseña: Admin123!\n";
    echo "   3. Limpia caché: php artisan config:clear\n";
    echo "   4. Limpia cache de auth: php artisan cache:clear\n";
    echo "   5. Verifica las rutas de login en web.php\n\n";
} else {
    echo "⚠️  SE ENCONTRARON PROBLEMAS\n\n";
    
    if (!$isPasswordValid) {
        echo "   📝 SOLUCIÓN 1: Actualizar contraseña\n";
        echo "   Ejecuta en tu terminal:\n";
        echo "   php artisan tinker\n";
        echo "   \$user = App\\Models\\User::where('email', 'admin@sisnotice.com')->first();\n";
        echo "   \$user->password = Hash::make('Admin123!');\n";
        echo "   \$user->save();\n";
        echo "   exit\n\n";
    }
    
    if (!$user->is_active) {
        echo "   📝 SOLUCIÓN 2: Activar usuario\n";
        echo "   Ejecuta en tu terminal:\n";
        echo "   php artisan tinker\n";
        echo "   \$user = App\\Models\\User::where('email', 'admin@sisnotice.com')->first();\n";
        echo "   \$user->is_active = true;\n";
        echo "   \$user->save();\n";
        echo "   exit\n\n";
    }
}

echo "═══════════════════════════════════════════════════════════════\n";
echo "Diagnóstico completado - " . date('Y-m-d H:i:s') . "\n";
echo "═══════════════════════════════════════════════════════════════\n";