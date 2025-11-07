import 'package:flutter/material.dart';
// Importamos nuestro "molde" de Business
import '../../domain/entities/business.dart';

class BusinessDetailScreen extends StatelessWidget {
  // Esta pantalla debe RECIBIR el negocio que queremos mostrar
  final Business business;

  const BusinessDetailScreen({
    super.key,
    required this.business,
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        // Mostramos el nombre del negocio en la barra
        title: Text(business.name),
      ),
      body: SingleChildScrollView(
        // Para poder scrollear si el contenido es largo
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // 1. La misma imagen que vimos en la lista
            Image.network(
              business.imageUrl,
              height: 250, // Más alta que en la lista
              width: double.infinity,
              fit: BoxFit.cover,
            ),
            
            // 2. El contenido
            Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Nombre (otra vez, como título)
                  Text(
                    business.name,
                    style: const TextStyle(
                      fontSize: 24,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const SizedBox(height: 16),
                  
                  // Descripción
                  Text(
                    business.description,
                    style: const TextStyle(
                      fontSize: 16,
                      height: 1.5, // Interlineado
                    ),
                  ),
                  const SizedBox(height: 24),
                  
                  // Un botón de acción (de ejemplo)
                  ElevatedButton(
                    onPressed: () {
                      // TODO: Lógica para crear una orden
                    },
                    style: ElevatedButton.styleFrom(
                      minimumSize: const Size(double.infinity, 50), // Ancho completo
                    ),
                    child: const Text('Crear Nueva Orden'),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}