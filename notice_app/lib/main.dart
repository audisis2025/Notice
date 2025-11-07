import 'package:flutter/material.dart';

// --- ¡AQUÍ ESTÁ LA CORRECCIÓN! ---
// (Cambié 'package.' por 'package:')
import 'package:flutter_localizations/flutter_localizations.dart';
import 'package:intl/date_symbol_data_local.dart';

// (Este también lo revisé, está bien)
import 'package:notice_app/features/auth/presentation/screens/splash_screen.dart';

void main() async {
  // Asegura que Flutter esté inicializado
  WidgetsFlutterBinding.ensureInitialized();
  // Inicializa los datos de localización para español
  await initializeDateFormatting('es_ES', null);

  runApp(const NoticeApp());
}

class NoticeApp extends StatelessWidget {
  const NoticeApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Notice App',
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: Colors.deepPurple),
        useMaterial3: true,
      ),
      debugShowCheckedModeBanner: false,

      // Para registrar el idioma español en la app
      localizationsDelegates: const [
        GlobalMaterialLocalizations.delegate,
        GlobalWidgetsLocalizations.delegate,
        GlobalCupertinoLocalizations.delegate,
      ],
      supportedLocales: const [
        Locale('es', 'ES'), // Español de España (nos sirve para el formato)
      ],

      home: const SplashScreen(),
    );
  }
}