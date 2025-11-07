import 'package:flutter/material.dart';
// Importamos la pantalla de detalle que acabamos de crear
import '../screens/business_detail_screen.dart'; 
import '../../domain/entities/business.dart';

class BusinessListItem extends StatelessWidget {
  final Business business;

  const BusinessListItem({
    super.key,
    required this.business,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      elevation: 2,
      clipBehavior: Clip.antiAlias,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: InkWell(
        
        // --- ¡AQUÍ ESTÁ EL CAMBIO! ---
        onTap: () {
          // Ya no solo imprimimos en consola, ahora navegamos
          Navigator.push(
            context,
            MaterialPageRoute(
              // Le pasamos el 'business' a la nueva pantalla
              builder: (context) => BusinessDetailScreen(business: business),
            ),
          );
        },
        // --- FIN DEL CAMBIO ---

        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Image.network(
              business.imageUrl,
              height: 150,
              width: double.infinity,
              fit: BoxFit.cover,
              loadingBuilder: (context, child, progress) {
                if (progress == null) return child;
                return const SizedBox(
                  height: 150,
                  child: Center(child: CircularProgressIndicator()),
                );
              },
              errorBuilder: (context, error, stackTrace) {
                return const SizedBox(
                  height: 150,
                  child: Center(child: Icon(Icons.error, color: Colors.red)),
                );
              },
            ),
            Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    business.name,
                    style: const TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    business.description,
                    style: const TextStyle(fontSize: 14, color: Colors.grey),
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
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