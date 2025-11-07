class Business {
  // --- CAMBIO 1 ---
  // El JSON manda el 'id' como un número (int), no como texto (String).
  final int id;
  final String name;
  final String description;
  final String imageUrl;
  // (Ignoraremos created_at y updated_at por ahora)

  // El constructor normal (lo cambiamos para que acepte 'int')
  Business({
    required this.id,
    required this.name,
    required this.description,
    required this.imageUrl,
  });

  // --- ¡CAMBIO 2: ESTA ES LA PARTE NUEVA! ---
  // Un "constructor de fábrica" que sabe cómo leer un mapa (JSON)
  // y convertirlo en un objeto Business.
  factory Business.fromJson(Map<String, dynamic> json) {
    return Business(
      id: json['id'],
      name: json['name'],
      description: json['description'],
      imageUrl: json['imageUrl'],
    );
  }
}