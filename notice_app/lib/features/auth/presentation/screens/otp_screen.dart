// --- ¡¡ESTA LÍNEA FALTABA!! ---
// Define Scaffold, Text, Color, Column, etc.
import 'package:flutter/material.dart';

// Importamos el paquete que acabamos de instalar
import 'package:pinput/pinput.dart';

// Importamos la pantalla de navegación principal
import 'package:notice_app/features/home/presentation/screens/main_navigation_screen.dart';


class OtpScreen extends StatefulWidget {
  final String phoneNumber;

  const OtpScreen({
    super.key,
    required this.phoneNumber,
  });

  @override
  State<OtpScreen> createState() => _OtpScreenState();
}

class _OtpScreenState extends State<OtpScreen> {
  final _pinController = TextEditingController();
  final _formKey = GlobalKey<FormState>();

  @override
  void dispose() {
    _pinController.dispose();
    super.dispose();
  }

  // --- Función de Verificación (MODIFICADA) ---
  void _verifyCode() {
    // Validamos que el PIN no esté vacío
    if (_formKey.currentState!.validate()) {
      final otpCode = _pinController.text;

      // Por ahora, solo simularemos que el código '123456' es el correcto.
      if (otpCode == '123456') {
        
        // ¡Éxito!
        print('Navegando a la Home Screen...');

        // Navegamos a la pantalla principal y eliminamos TODAS
        // las pantallas anteriores (Login, OTP, Splash) de la pila.
        Navigator.pushAndRemoveUntil(
          context,
          MaterialPageRoute(builder: (context) => const MainNavigationScreen()),
          (Route<dynamic> route) => false, // Esta línea borra todo lo anterior
        );

      } else {
        // Error
        print('Código incorrecto');
        // TODO: Mostrar un mensaje de error (un SnackBar sería ideal)
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    // Estilo por defecto para los cuadritos del PIN
    final defaultPinTheme = PinTheme(
      width: 56,
      height: 60,
      textStyle: const TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
      decoration: BoxDecoration(
        color: Colors.grey.shade200,
        borderRadius: BorderRadius.circular(8),
        border: Border.all(color: Colors.transparent),
      ),
    );

    return Scaffold(
      appBar: AppBar(
        title: const Text('Verificar Código'),
      ),
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(24.0),
          child: Form(
            key: _formKey,
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                const Text(
                  'Verifica tu número',
                  style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
                  textAlign: TextAlign.center,
                ),
                const SizedBox(height: 16),
                // Usamos widget.phoneNumber para acceder al teléfono
                Text(
                  'Ingresa el código de 6 dígitos enviado a:\n${widget.phoneNumber}',
                  style: const TextStyle(fontSize: 16, color: Colors.grey),
                  textAlign: TextAlign.center,
                ),
                const SizedBox(height: 32),

                // --- El widget PINPUT ---
                Pinput(
                  length: 6, // 6 dígitos
                  controller: _pinController,
                  
                  // Esta es la corrección del typo anterior
                  defaultPinTheme: defaultPinTheme, 

                  focusedPinTheme: defaultPinTheme.copyWith(
                    decoration: defaultPinTheme.decoration!.copyWith(
                      border: Border.all(color: Colors.deepPurple),
                    ),
                  ),
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return 'Ingresa el código';
                    }
                    if (value.length < 6) {
                      return 'El código debe tener 6 dígitos';
                    }
                    return null;
                  },
                ),
                const SizedBox(height: 32),

                // Botón de Verificar
                ElevatedButton(
                  onPressed: _verifyCode, // Llama a nuestra función modificada
                  style: ElevatedButton.styleFrom(
                    padding: const EdgeInsets.symmetric(vertical: 16),
                  ),
                  child: const Text('Verificar', style: TextStyle(fontSize: 16)),
                ),
                const SizedBox(height: 24),

                // Botón de Reenviar
                TextButton(
                  onPressed: () {
                    // TODO: Lógica para reenviar el código
                    print('Reenviando código...');
                  },
                  child: const Text('¿No recibiste el código? Reenviar'),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}