// Importamos el enum que acabamos de crear
import 'order_status.dart';

class Order {
  final String id;
  final String businessName;
  final String totalAmount;
  final DateTime date;
  final OrderStatus status;

  // ¡SIN CONST! (Ya aprendimos la lección)
  Order({
    required this.id,
    required this.businessName,
    required this.totalAmount,
    required this.date,
    required this.status,
  });
}