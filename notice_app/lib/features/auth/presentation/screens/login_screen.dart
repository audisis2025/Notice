import 'package:flutter/material.dart';
// Importamos la pantalla de OTP para poder navegar a ella
import 'package:notice_app/features/auth/presentation/screens/otp_screen.dart';

// --- CAMBIO 1: Convertido a StatefulWidget ---
// Necesitamos estado para manejar lo que el usuario escribe en el campo de texto.
class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  // --- CAMBIO 2: Controladores y Llaves ---
  // El _formKey nos ayuda a validar el formulario (ej. que no esté vacío).
  final _formKey = GlobalKey<FormState>();
  // El _phoneController lee el texto del campo de teléfono.
  final _phoneController = TextEditingController();

  // Es buena práctica "limpiar" los controladores cuando la pantalla se destruye.
  @override
  void dispose() {
    _phoneController.dispose();
    super.dispose();
  }

  // --- CAMBIO 3: La función para enviar el código ---
  void _sendCode() {
    // Primero, valida que el formulario esté correcto (según nuestras reglas)
    if (_formKey.currentState!.validate()) {
      // Si está bien, obtenemos el número
      final phoneNumber = _phoneController.text;

      // --- ¡IMPORTANTE! ---
      // Aquí es donde, en el futuro, llamaremos a nuestra API de Laravel
      // para que ENVÍE el código SMS al teléfono del usuario.
      //
      // Por ahora, solo simularemos que funciona y navegaremos
      // a la pantalla de OTP.

      // Navegamos a la pantalla de OTP y le pasamos el número de teléfono
      Navigator.push(
        context,
        MaterialPageRoute(
          builder: (context) => OtpScreen(phoneNumber: phoneNumber),
        ),
      );
    }
  }

  // --- CAMBIO 4: Construimos la UI (la parte visual) ---
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Iniciar Sesión'),
        centerTitle: true,
      ),
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(24.0),
          // Usamos un Form para poder validar
          child: Form(
            key: _formKey,
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              crossAxisAlignment: CrossAxisAlignment.stretch, // Estira los widgets
              children: [
                // Título
                const Text(
                  'Ingresa tu número de teléfono',
                  style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
                  textAlign: TextAlign.center,
                ),
                const SizedBox(height: 16),
                // Subtítulo
                const Text(
                  'Te enviaremos un código de verificación para continuar.',
                  style: TextStyle(fontSize: 16, color: Colors.grey),
                  textAlign: TextAlign.center,
                ),
                const SizedBox(height: 32),

                // Campo de texto para el teléfono
                TextFormField(
                  controller: _phoneController,
                  decoration: const InputDecoration(
                    labelText: 'Número de teléfono',
                    border: OutlineInputBorder(),
                    prefixIcon: Icon(Icons.phone),
                  ),
                  keyboardType: TextInputType.phone, // Muestra el teclado numérico
                  
                  // Regla de validación
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return 'Por favor ingresa tu teléfono';
                    }
                    if (value.length < 10) {
                      // Puedes hacer esta regla más compleja (ej. 10 dígitos)
                      return 'El número no parece válido';
                    }
                    return null;
                  },
                ),
                const SizedBox(height: 24),

                // Botón de Enviar
                ElevatedButton(
                  onPressed: _sendCode, // Llama a nuestra función
                  style: ElevatedButton.styleFrom(
                    padding: const EdgeInsets.symmetric(vertical: 16),
                  ),
                  child: const Text('Enviar Código', style: TextStyle(fontSize: 16)),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}