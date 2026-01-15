<?php
/**
 * Script de prueba para generar QR con Chillerlan
 * Ejecutar: php test_qr_chillerlan.php
 */

require __DIR__ . '/vendor/autoload.php';

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

echo "=== TEST GENERACIÓN QR CHILLERLAN ===\n\n";

// Test 1: Generación básica
echo "Test 1: Generación básica PNG\n";
try {
    $options = new QROptions([
        'outputType' => QRCode::OUTPUT_IMAGE_PNG,
        'eccLevel' => QRCode::ECC_H,
        'scale' => 10,
        'imageBase64' => false,
    ]);
    
    $qrcode = new QRCode($options);
    $output = $qrcode->render('https://google.com');
    
    $path = __DIR__ . '/storage/app/public/test-method1.png';
    file_put_contents($path, $output);
    
    echo "✅ Método 1 OK - Archivo: " . filesize($path) . " bytes\n";
    echo "   Ruta: $path\n\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n\n";
}

// Test 2: Con imageTransparent
echo "Test 2: Con fondo transparente desactivado\n";
try {
    $options = new QROptions([
        'outputType' => QRCode::OUTPUT_IMAGE_PNG,
        'eccLevel' => QRCode::ECC_H,
        'scale' => 10,
        'imageBase64' => false,
        'imageTransparent' => false,
    ]);
    
    $qrcode = new QRCode($options);
    $output = $qrcode->render('https://google.com');
    
    $path = __DIR__ . '/storage/app/public/test-method2.png';
    file_put_contents($path, $output);
    
    echo "✅ Método 2 OK - Archivo: " . filesize($path) . " bytes\n";
    echo "   Ruta: $path\n\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n\n";
}

// Test 3: Con más opciones
echo "Test 3: Con colores específicos\n";
try {
    $options = new QROptions([
        'version'    => 5,
        'outputType' => QRCode::OUTPUT_IMAGE_PNG,
        'eccLevel'   => QRCode::ECC_H,
        'scale'      => 10,
        'imageBase64' => false,
        'imageTransparent' => false,
        'drawCircularModules' => false,
        'drawLightModules' => true,
        'moduleValues' => [
            // finder
            1536 => [0, 0, 0], // dark (foreground)
            6    => [255, 255, 255], // light (background)
        ],
    ]);
    
    $qrcode = new QRCode($options);
    $output = $qrcode->render('https://google.com');
    
    $path = __DIR__ . '/storage/app/public/test-method3.png';
    file_put_contents($path, $output);
    
    echo "✅ Método 3 OK - Archivo: " . filesize($path) . " bytes\n";
    echo "   Ruta: $path\n\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n\n";
}

// Test 4: Verificar archivo actual
echo "Test 4: Verificar archivo test.png actual\n";
$currentFile = __DIR__ . '/storage/app/public/test.png';
if (file_exists($currentFile)) {
    $size = filesize($currentFile);
    echo "✅ Archivo existe: $size bytes\n";
    
    // Verificar que sea PNG válido
    $imageInfo = @getimagesize($currentFile);
    if ($imageInfo) {
        echo "✅ Imagen válida: {$imageInfo[0]}x{$imageInfo[1]} - {$imageInfo['mime']}\n";
    } else {
        echo "❌ No es una imagen PNG válida\n";
    }
} else {
    echo "❌ Archivo no existe\n";
}

echo "\n=== RECOMENDACIÓN ===\n";
echo "Probar en navegador:\n";
echo "- http://127.0.0.1:8000/storage/test-method1.png\n";
echo "- http://127.0.0.1:8000/storage/test-method2.png\n";
echo "- http://127.0.0.1:8000/storage/test-method3.png\n";