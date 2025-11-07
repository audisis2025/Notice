import 'package:flutter/material.dart';
// Para formatear la fecha (ej. "06 nov 2025")
import 'package:intl/intl.dart'; 
// Importamos los moldes que creamos en el paso anterior
import '../../domain/entities/order.dart';
import '../../domain/entities/order_status.dart';

class OrderListItem extends StatelessWidget {
  final Order order;

  const OrderListItem({
    super.key,
    required this.order,
  });

  @override
  Widget build(BuildContext context) {
    // Formateador de fecha
    // (Usará 'es_ES' que configuraremos en main.dart)
    final DateFormat formatter = DateFormat('dd MMM yyyy', 'es_ES');
    
    // Obtenemos el color y el ícono según el estado
    final statusColor = _getColorForStatus(order.status);
    final statusIcon = _getIconForStatus(order.status);

    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      elevation: 2,
      clipBehavior: Clip.antiAlias,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: InkWell(
        onTap: () {
          // TODO: Navegar al detalle de la orden
          print('Tocado orden: ${order.id}');
        },
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // --- Fila Superior: Nombre del Negocio y Precio ---
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  // Nombre del negocio (flexible para que no se desborde)
                  Expanded(
                    child: Text(
                      order.businessName,
                      style: const TextStyle(
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                      ),
                      overflow: TextOverflow.ellipsis,
                    ),
                  ),
                  const SizedBox(width: 16),
                  // Precio
                  Text(
                    order.totalAmount,
                    style: const TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                      color: Colors.green, // Color de precio
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 12),

              // --- Fila Intermedia: Fecha ---
              Text(
                'Fecha: ${formatter.format(order.date)}',
                style: const TextStyle(fontSize: 14, color: Colors.grey),
              ),
              const SizedBox(height: 12),

              // --- Fila Inferior: Estado de la Orden ---
              Row(
                children: [
                  Icon(statusIcon, color: statusColor, size: 20),
                  const SizedBox(width: 8),
                  Text(
                    order.status.displayName, // Usamos la extensión del enum
                    style: TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.w500,
                      color: statusColor,
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }

  // --- Funciones de Ayuda ---

  Color _getColorForStatus(OrderStatus status) {
    switch (status) {
      case OrderStatus.pending:
        return Colors.orange.shade700;
      case OrderStatus.ready:
        return Colors.blue.shade700;
      case OrderStatus.completed:
        return Colors.green.shade700;
      case OrderStatus.cancelled:
        return Colors.red.shade700;
      default:
        return Colors.grey;
    }
  }

  IconData _getIconForStatus(OrderStatus status) {
    switch (status) {
      case OrderStatus.pending:
        return Icons.hourglass_top;
      case OrderStatus.ready:
        return Icons.check_circle_outline;
      case OrderStatus.completed:
        return Icons.check_circle;
      case OrderStatus.cancelled:
        return Icons.cancel;
      default:
        return Icons.help_outline;
    }
  }
}