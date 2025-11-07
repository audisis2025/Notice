import 'package:flutter/material.dart';
// Importamos la pantalla de Login para poder regresar a ella
import 'package:notice_app/features/auth/presentation/screens/login_screen.dart';
// Importamos shared_preferences para poder borrar la sesión
import 'package:shared_preferences/shared_preferences.dart';

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});

  // --- 1. Creamos la función para Cerrar Sesión ---
  // La hacemos 'async' porque 'shared_preferences' es asíncrono
  void _logout(BuildContext context) async {
    try {
      // 1. Obtenemos la instancia de SharedPreferences
      final prefs = await SharedPreferences.getInstance();
      
      // 2. Borramos TODOS los datos guardados
      // (Esto incluye 'hasSeenTutorial' y cualquier token de sesión futuro)
      await prefs.clear();

      // 3. Navegamos al Login y borramos todo el historial
      // (Usamos 'context' y 'Navigator' de forma segura)
      if (context.mounted) {
        Navigator.pushAndRemoveUntil(
          context,
          MaterialPageRoute(builder: (context) => const LoginScreen()),
          (Route<dynamic> route) => false, // Borra todas las rutas anteriores
        );
      }
    } catch (e) {
      // Por si algo falla al borrar
      print('Error al cerrar sesión: $e');
      // TODO: Mostrar un SnackBar de error al usuario
    }
  }

  // --- 2. Construimos la UI de la pantalla ---
  @override
  Widget build(BuildContext context) {
    // Usamos 'Padding' para dar aire y 'Column' para apilar los elementos
    return Padding(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        // Centramos todo
        mainAxisAlignment: MainAxisAlignment.center,
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          // --- 3. Avatar de Perfil (Placeholder) ---
          const CircleAvatar(
            radius: 50, // Tamaño del círculo
            backgroundColor: Colors.grey,
            child: Icon(
              Icons.person,
              size: 50,
              color: Colors.white,
            ),
          ),
          const SizedBox(height: 16),

          // --- 4. Nombre de Usuario (Placeholder) ---
          const Text(
            'Nombre de Usuario', // TODO: Cargar el nombre real
            textAlign: TextAlign.center,
            style: TextStyle(
              fontSize: 22,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 8),
          
          // --- 5. Teléfono (Placeholder) ---
          const Text(
            '+52 123 456 7890', // TODO: Cargar el teléfono real
            textAlign: TextAlign.center,
            style: TextStyle(
              fontSize: 16,
              color: Colors.grey,
            ),
          ),
          const SizedBox(height: 32),

          // --- 6. El Botón de Cerrar Sesión ---
          ElevatedButton(
            // Le damos un estilo rojo para que sea claro
            style: ElevatedButton.styleFrom(
              backgroundColor: Colors.red.shade700,
              foregroundColor: Colors.white, // Color del texto
              padding: const EdgeInsets.symmetric(vertical: 16),
            ),
            // ¡Llamamos a nuestra función de logout!
            onPressed: () {
              // Le pasamos el 'context' actual
              _logout(context);
            },
            child: const Text('Cerrar Sesión', style: TextStyle(fontSize: 16)),
          ),
        ],
      ),
    );
  }
}