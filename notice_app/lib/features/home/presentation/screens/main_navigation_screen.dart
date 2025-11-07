// --- ¡¡ESTA LÍNEA FALTABA!! ---
// Define Scaffold, AppBar, Text, BottomNavigationBar, Colors, etc.
import 'package:flutter/material.dart';

// Estos imports sí los tenías, son para las 3 pantallas
import 'package:notice_app/features/home/presentation/screens/home_screen.dart';
import 'package:notice_app/features/orders/presentation/screens/orders_screen.dart';
import 'package:notice_app/features/profile/presentation/screens/profile_screen.dart';


class MainNavigationScreen extends StatefulWidget {
  const MainNavigationScreen({super.key});

  @override
  State<MainNavigationScreen> createState() => _MainNavigationScreenState();
}

class _MainNavigationScreenState extends State<MainNavigationScreen> {
  int _selectedIndex = 0; // 0 = Home, 1 = Órdenes, 2 = Perfil
  
  // Lista de Pantallas
  // --- ¡CORRECCIÓN DE CONST! ---
  // Quitamos 'const' de la lista porque HomeScreen no es const
  static final List<Widget> _widgetOptions = <Widget>[
    HomeScreen(), // Quitamos 'const'
    const OrdersScreen(),
    const ProfileScreen(),
  ];

  void _onItemTapped(int index) {
    setState(() {
      _selectedIndex = index;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(_getAppBarTitle(_selectedIndex)),
        centerTitle: true,
        automaticallyImplyLeading: false, // Oculta la flecha de "atrás"
      ),
      body: Center(
        child: _widgetOptions.elementAt(_selectedIndex),
      ),
      bottomNavigationBar: BottomNavigationBar(
        items: const <BottomNavigationBarItem>[
          BottomNavigationBarItem(
            icon: Icon(Icons.home),
            label: 'Inicio',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.list_alt),
            label: 'Órdenes',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.person),
            label: 'Perfil',
          ),
        ],
        currentIndex: _selectedIndex,
        selectedItemColor: Colors.deepPurple, // Ahora sí encontrará 'Colors'
        onTap: _onItemTapped,
      ),
    );
  }

  // Función de ayuda para cambiar el título del AppBar
  String _getAppBarTitle(int index) {
    switch (index) {
      case 0:
        return 'Inicio';
      case 1:
        return 'Mis Órdenes';
      case 2:
        return 'Mi Perfil';
      default:
        return 'Notice';
    }
  }
}