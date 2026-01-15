#!/usr/bin/env php
<?php
/**
 * Script para verificar componentes Livewire de Auth
 * 
 * Ejecutar: php check_livewire_auth.php
 */

echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║     VERIFICACIÓN DE COMPONENTES LIVEWIRE AUTH                ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

$componentsPath = __DIR__ . '/app/Livewire/Auth';
$viewsPath = __DIR__ . '/resources/views/livewire/auth';

// Componentes requeridos
$requiredComponents = [
    'Login.php',
    'Register.php',
    'ForgotPassword.php',
    'ResetPassword.php',
];

$requiredViews = [
    'login.blade.php',
    'register.blade.php',
    'forgot-password.blade.php',
    'reset-password.blade.php',
];

echo "1️⃣  VERIFICANDO COMPONENTES LIVEWIRE...\n";
echo "─────────────────────────────────────────────────────────────────\n";
echo "Ruta: {$componentsPath}\n\n";

$missingComponents = [];

foreach ($requiredComponents as $component) {
    $path = $componentsPath . '/' . $component;
    if (file_exists($path)) {
        echo "✅ {$component}\n";
    } else {
        echo "❌ {$component} - NO EXISTE\n";
        $missingComponents[] = $component;
    }
}

echo "\n2️⃣  VERIFICANDO VISTAS LIVEWIRE...\n";
echo "─────────────────────────────────────────────────────────────────\n";
echo "Ruta: {$viewsPath}\n\n";

$missingViews = [];

foreach ($requiredViews as $view) {
    $path = $viewsPath . '/' . $view;
    if (file_exists($path)) {
        echo "✅ {$view}\n";
    } else {
        echo "❌ {$view} - NO EXISTE\n";
        $missingViews[] = $view;
    }
}

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";

if (empty($missingComponents) && empty($missingViews)) {
    echo "✅ TODOS LOS ARCHIVOS EXISTEN\n\n";
    echo "💡 SIGUIENTE PASO:\n";
    echo "   1. Reemplaza tu web.php con el archivo web_livewire.php\n";
    echo "   2. Ejecuta: php artisan route:clear\n";
    echo "   3. Ejecuta: php artisan config:clear\n";
    echo "   4. Visita: http://sisnotice.test/login\n";
} else {
    echo "⚠️  FALTAN ARCHIVOS\n\n";
    
    if (!empty($missingComponents)) {
        echo "📝 COMPONENTES FALTANTES:\n";
        foreach ($missingComponents as $comp) {
            echo "   - {$comp}\n";
        }
        echo "\n";
    }
    
    if (!empty($missingViews)) {
        echo "📝 VISTAS FALTANTES:\n";
        foreach ($missingViews as $view) {
            echo "   - {$view}\n";
        }
        echo "\n";
    }
    
    echo "💡 SOLUCIÓN:\n";
    echo "   Necesitas crear los componentes Livewire faltantes.\n";
    echo "   Te los puedo generar si me confirmas.\n";
}

echo "═══════════════════════════════════════════════════════════════\n";

// Verificar si Livewire está instalado
echo "\n3️⃣  VERIFICANDO INSTALACIÓN DE LIVEWIRE...\n";
echo "─────────────────────────────────────────────────────────────────\n";

$composerJson = __DIR__ . '/composer.json';
if (file_exists($composerJson)) {
    $json = json_decode(file_get_contents($composerJson), true);
    
    if (isset($json['require']['livewire/livewire'])) {
        $version = $json['require']['livewire/livewire'];
        echo "✅ Livewire instalado: {$version}\n";
    } else {
        echo "❌ Livewire NO está en composer.json\n";
        echo "   Ejecuta: composer require livewire/livewire\n";
    }
} else {
    echo "❌ No se encontró composer.json\n";
}

echo "\n═══════════════════════════════════════════════════════════════\n";