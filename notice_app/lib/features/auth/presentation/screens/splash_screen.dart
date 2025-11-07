import 'package:flutter/material.dart';
import 'package:flutter/scheduler.dart';
import 'package:notice_app/features/auth/presentation/screens/login_screen.dart';
import 'package:notice_app/features/auth/presentation/screens/tutorial_screen.dart';
// Importamos la pantalla de navegación principal
import 'package:notice_app/features/home/presentation/screens/main_navigation_screen.dart'; 
import 'package:shared_preferences/shared_preferences.dart';

class SplashScreen extends StatefulWidget {
  const SplashScreen({super.key});

  @override
  State<SplashScreen> createState() => _SplashScreenState();
}

class _SplashScreenState extends State<SplashScreen> {
  @override
  void initState() {
    super.initState();
    _navigateToNextScreen();
  }

  void _navigateToNextScreen() {
    SchedulerBinding.instance.addPostFrameCallback((_) async {
      
      // --- ¡AQUÍ ESTÁ EL INTERRUPTOR! ---
      // Pon esto en 'true' para saltarte el login e ir directo al Home.
      // Pon esto en 'false' para probar el flujo de Login normal.
      const bool devBypassLogin = true; 
      
      // Espera 2 segundos (le bajé de 3 a 2)
      await Future.delayed(const Duration(seconds: 2));

      if (!context.mounted) return;

      // --- LÓGICA DE NAVEGACIÓN ACTUALIZADA ---
      if (devBypassLogin) {
        // 1. MODO DESARROLLADOR: Saltar directo al menú principal
        Navigator.pushReplacement(
          context,
          MaterialPageRoute(builder: (context) => const MainNavigationScreen()),
        );
      } else {
        // 2. MODO NORMAL: Revisar si ya vio el tutorial
        final prefs = await SharedPreferences.getInstance();
        final bool hasSeenTutorial = prefs.getBool('hasSeenTutorial') ?? false;

        if (hasSeenTutorial) {
          // Si ya vio el tutorial, lo mandamos al Login
          Navigator.pushReplacement(
            context,
            MaterialPageRoute(builder: (context) => const LoginScreen()),
          );
        } else {
          // Si es la primera vez, lo mandamos al Tutorial
          Navigator.pushReplacement(
            context,
            MaterialPageRoute(builder: (context) => const TutorialScreen()),
          );
        }
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return const Scaffold(
      body: Center(
        child: Text(
          'Notice App - Splash Screen',
          style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
        ),
      ),
    );
  }
}