import 'package:flutter/material.dart';
import 'package:notice_app/features/auth/presentation/screens/login_screen.dart';
import 'package:shared_preferences/shared_preferences.dart'; // <-- 1. IMPORTAR PAQUETE

class TutorialScreen extends StatefulWidget {
  const TutorialScreen({super.key});

  @override
  State<TutorialScreen> createState() => _TutorialScreenState();
}

class _TutorialScreenState extends State<TutorialScreen> {
  final PageController _pageController = PageController();
  int _currentPage = 0;

  // Lista de páginas (igual que antes)
  final List<Widget> _tutorialPages = [
    _buildPage(
      title: '¡Bienvenido a Notice!',
      description: 'Tu app para gestionar avisos y órdenes fácilmente.',
      color: Colors.blue.shade100,
    ),
    _buildPage(
      title: 'Escanea tu QR',
      description: 'Asocia tus órdenes a tu cuenta en segundos.',
      color: Colors.green.shade100,
    ),
    _buildPage(
      title: 'Recibe Notificaciones',
      description: 'Te avisaremos en cuanto tu pedido esté listo.',
      color: Colors.purple.shade100,
    ),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
        child: Column(
          children: [
            Expanded(
              child: PageView(
                controller: _pageController,
                children: _tutorialPages,
                onPageChanged: (int page) {
                  setState(() {
                    _currentPage = page;
                  });
                },
              ),
            ),
            Padding(
              padding: const EdgeInsets.all(24.0),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const SizedBox(width: 80),
                  Text('${_currentPage + 1} / ${_tutorialPages.length}'),

                  // --- 2. EL BOTÓN AHORA ES 'async' ---
                  ElevatedButton(
                    onPressed: () async { // <-- AÑADIR 'async'
                      if (_currentPage == _tutorialPages.length - 1) {
                        
                        // --- 3. LÓGICA DE GUARDADO ---
                        final prefs = await SharedPreferences.getInstance();
                        await prefs.setBool('hasSeenTutorial', true); // Guardamos
                        
                        // Buena práctica: verificar que el widget sigue "montado"
                        if (!context.mounted) return;
                        Navigator.pushReplacement(
                          context,
                          MaterialPageRoute(
                              builder: (context) => const LoginScreen()),
                        );
                      } else {
                        // Lógica de Siguiente (igual que antes)
                        _pageController.nextPage(
                          duration: const Duration(milliseconds: 400),
                          curve: Curves.easeInOut,
                        );
                      }
                    },
                    child: Text(
                      _currentPage == _tutorialPages.length - 1
                          ? 'Finalizar'
                          : 'Siguiente',
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  // Constructor de página (igual que antes)
  static Widget _buildPage({
    required String title,
    required String description,
    required Color color,
  }) {
    return Container(
      color: color,
      padding: const EdgeInsets.all(32.0),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text(
            title,
            style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
            textAlign: TextAlign.center,
          ),
          const SizedBox(height: 16),
          Text(
            description,
            style: const TextStyle(fontSize: 16),
            textAlign: TextAlign.center,
          ),
        ],
      ),
    );
  }
}