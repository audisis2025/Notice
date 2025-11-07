import 'package:flutter/material.dart';
import '../../domain/entities/order.dart';
import '../../domain/entities/order_status.dart';
import '../widgets/order_list_item.dart';

// --- CAMBIO 1: Convertido a StatefulWidget ---
// Necesitamos estado para manejar el 'TabController' (el controlador de pestañas)
class OrdersScreen extends StatefulWidget {
  const OrdersScreen({super.key});

  @override
  State<OrdersScreen> createState() => _OrdersScreenState();
}

// --- CAMBIO 2: Añadimos 'TickerProviderStateMixin' ---
// Es necesario para la animación del TabController
class _OrdersScreenState extends State<OrdersScreen>
    with SingleTickerProviderStateMixin {
  
  // --- CAMBIO 3: Declaramos el controlador ---
  late TabController _tabController;

  // --- CAMBIO 4: Creamos datos de prueba ---
  // (Más adelante, esto vendrá de tu API de Laravel)
  final List<Order> allOrders = [
    Order(
      id: 'ORD-001',
      businessName: 'Tintorería "El Sol"',
      totalAmount: '\$150.00',
      date: DateTime.now().subtract(const Duration(days: 1)),
      status: OrderStatus.ready, // Esta irá a "Activas"
    ),
    Order(
      id: 'ORD-002',
      businessName: 'Reparación de Calzado "Zapatero Veloz"',
      totalAmount: '\$80.00',
      date: DateTime.now().subtract(const Duration(days: 3)),
      status: OrderStatus.pending, // Esta irá a "Activas"
    ),
    Order(
      id: 'ORD-003',
      businessName: 'Lavandería "Burbujas"',
      totalAmount: '\$220.00',
      date: DateTime.now().subtract(const Duration(days: 5)),
      status: OrderStatus.completed, // Esta irá a "Completadas"
    ),
    Order(
      id: 'ORD-004',
      businessName: 'Tintorería "El Sol"',
      totalAmount: '\$120.00',
      date: DateTime.now().subtract(const Duration(days: 7)),
      status: OrderStatus.cancelled, // Esta irá a "Completadas"
    ),
  ];

  // --- CAMBIO 5: Creamos las listas filtradas ---
  late List<Order> activeOrders;
  late List<Order> completedOrders;

  @override
  void initState() {
    super.initState();
    // Inicializamos el controlador con 2 pestañas
    _tabController = TabController(length: 2, vsync: this);

    // Filtramos las listas
    activeOrders = allOrders
        .where((o) =>
            o.status == OrderStatus.pending || o.status == OrderStatus.ready)
        .toList();
    completedOrders = allOrders
        .where((o) =>
            o.status == OrderStatus.completed || o.status == OrderStatus.cancelled)
        .toList();
  }

  @override
  void dispose() {
    // Es importante "limpiar" el controlador cuando la pantalla se destruye
    _tabController.dispose();
    super.dispose();
  }

  // --- CAMBIO 6: El nuevo 'build' con pestañas ---
  @override
  Widget build(BuildContext context) {
    // Usamos un 'Column' para poner las pestañas arriba y el contenido abajo
    return Column(
      children: [
        // --- Las Pestañas ---
        TabBar(
          controller: _tabController,
          tabs: const [
            Tab(text: 'Activas'),
            Tab(text: 'Completadas'),
          ],
          labelColor: Colors.deepPurple, // Color del texto seleccionado
          unselectedLabelColor: Colors.grey, // Color del texto no seleccionado
        ),

        // --- El Contenido de las Pestañas ---
        Expanded(
          child: TabBarView(
            controller: _tabController,
            children: [
              // Contenido de la Pestaña 1 (Activas)
              _buildOrderList(activeOrders),
              // Contenido de la Pestaña 2 (Completadas)
              _buildOrderList(completedOrders),
            ],
          ),
        ),
      ],
    );
  }

  // --- CAMBIO 7: Un widget de ayuda para construir las listas ---
  // (Para no repetir código)
  Widget _buildOrderList(List<Order> orders) {
    // Si la lista está vacía, muestra un mensaje
    if (orders.isEmpty) {
      return const Center(
        child: Text(
          'No hay órdenes en esta categoría.',
          style: TextStyle(fontSize: 16, color: Colors.grey),
        ),
      );
    }

    // Si hay órdenes, muestra el ListView
    return ListView.builder(
      itemCount: orders.length,
      itemBuilder: (context, index) {
        final order = orders[index];
        return OrderListItem(order: order);
      },
    );
  }
}