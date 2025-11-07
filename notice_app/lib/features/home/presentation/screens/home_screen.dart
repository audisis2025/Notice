import 'dart:convert'; // Para decodificar el JSON
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http; // El paquete que acabamos de instalar
import '../../domain/entities/business.dart';
import '../widgets/business_list_item.dart';

// --- CAMBIO 1: Convertido a StatefulWidget ---
class HomeScreen extends StatefulWidget {
  // Ya no necesitamos el constructor 'const' ni la lista 'dummy' aquí
  HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  // --- CAMBIO 2: El 'Future' ---
  // 'Future' es un objeto que "promete" que tendrá
  // una Lista de Negocios... en el futuro.
  late Future<List<Business>> _businessFuture;

  // --- CAMBIO 3: La URL (¡MUY IMPORTANTE!) ---
  // Esta es la IP especial que usa el emulador de Android
  // para hablar con tu computadora (el 'localhost' de tu PC).
  final String apiUrl = 'http://10.0.2.2:8000/api/businesses';

  @override
  void initState() {
    super.initState();
    // Cuando la pantalla se carga, le pedimos que
    // inicie la "promesa" de ir a buscar los negocios.
    _businessFuture = fetchBusinesses();
  }

  // --- CAMBIO 4: La Función de Red ---
  Future<List<Business>> fetchBusinesses() async {
    try {
      final response = await http.get(Uri.parse(apiUrl));

      if (response.statusCode == 200) {
        // ¡Éxito!
        // 1. Decodifica el texto JSON (la lista de negocios)
        List<dynamic> jsonList = jsonDecode(response.body);
        
        // 2. Convierte cada objeto JSON en un objeto 'Business'
        //    usando el 'fromJson' que creamos.
        List<Business> businesses = jsonList
            .map((jsonItem) => Business.fromJson(jsonItem))
            .toList();
            
        return businesses;
      } else {
        // Si el servidor falló (ej. error 500)
        throw Exception('Error del servidor: ${response.statusCode}');
      }
    } catch (e) {
      // Si falló la conexión (ej. no hay internet o el servidor está apagado)
      throw Exception('Falló la conexión: $e');
    }
  }

  // --- CAMBIO 5: El 'build' con FutureBuilder ---
  @override
  Widget build(BuildContext context) {
    // FutureBuilder es el widget perfecto para manejar 'Futures'
    // Se redibuja solo cuando el 'Future' se completa.
    return FutureBuilder<List<Business>>(
      future: _businessFuture,
      builder: (context, snapshot) {
        
        // --- CASO 1: Cargando ---
        // Mientras el 'Future' está esperando la respuesta
        if (snapshot.connectionState == ConnectionState.waiting) {
          return const Center(
            child: CircularProgressIndicator(), // Muestra un círculo de "cargando"
          );
        }

        // --- CASO 2: Error ---
        // Si el 'Future' falló (no hay internet, error de API, etc.)
        if (snapshot.hasError) {
          return Center(
            child: Text(
              'Error al cargar los negocios:\n${snapshot.error}',
              textAlign: TextAlign.center,
              style: const TextStyle(color: Colors.red),
            ),
          );
        }

        // --- CASO 3: Éxito (Pero no hay datos) ---
        // Si el 'Future' tuvo éxito pero la lista vino vacía
        if (!snapshot.hasData || snapshot.data!.isEmpty) {
          return const Center(
            child: Text('No se encontraron negocios.'),
          );
        }

        // --- CASO 4: ¡ÉXITO TOTAL! ---
        // Si todo salió bien y SÍ hay datos
        final businesses = snapshot.data!;
        
        // Construimos el mismo ListView que ya teníamos
        return ListView.builder(
          itemCount: businesses.length,
          itemBuilder: (context, index) {
            final business = businesses[index];
            return BusinessListItem(business: business);
          },
        );
      },
    );
  }
}