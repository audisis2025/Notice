// Un enum es simplemente una lista predefinida de valores
enum OrderStatus {
  pending,
  ready,
  completed,
  cancelled,
}

// Una función de ayuda para que el 'enum' se vea bonito en la UI
extension OrderStatusExtension on OrderStatus {
  String get displayName {
    switch (this) {
      case OrderStatus.pending:
        return 'Pendiente';
      case OrderStatus.ready:
        return 'Lista para Recoger';
      case OrderStatus.completed:
        return 'Completada';
      case OrderStatus.cancelled:
        return 'Cancelada';
      default:
        return 'Desconocido';
    }
  }
}