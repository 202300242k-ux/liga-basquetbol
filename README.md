
# Documentación técnica: Gestión de Liga de Básquetbol

## 1. Descripción general y arquitectura del sistema

El sistema web de Gestión de Liga de Básquetbol tiene como finalidad administrar la información de una competición deportiva mediante una aplicación centralizada. Permitirá registrar usuarios, gestionar equipos y jugadores, controlar partidos y consultar resultados y estadísticas básicas. Su desarrollo se orienta a mantener la información organizada, facilitar su consulta y reducir los errores producidos por el registro manual.

Se propone utilizar PHP para la lógica del servidor, MySQL como sistema gestor de base de datos y HTML, CSS y JavaScript para la interfaz de usuario. Laravel podrá utilizarse como framework para organizar el código, administrar las rutas y facilitar la interacción con la base de datos.

La arquitectura seguirá el patrón Modelo-Vista-Controlador (MVC). Los modelos representarán los datos y sus relaciones; las vistas mostrarán la información al usuario; y los controladores procesarán las solicitudes, validarán los datos y coordinarán las operaciones del sistema.

## 2. Modelos, clases y controladores

Se proponen los siguientes modelos principales:

- **User:** almacenará los datos de los usuarios y la información necesaria para la autenticación.
- **Equipo:** representará a los equipos participantes en la liga.
- **Jugador:** almacenará los datos de cada jugador y su relación con un equipo.
- **Partido:** registrará los equipos participantes, la fecha, el estado del encuentro y los puntos obtenidos.

Los controladores previstos son EquipoController, JugadorController y PartidoController. Estos gestionarán las operaciones de registro, consulta, actualización y eliminación correspondientes a cada módulo. La autenticación podrá administrarse mediante las funcionalidades propias de Laravel o un controlador específico, según la implementación elegida.

Las entidades deberán relacionarse de forma coherente. Un equipo podrá tener varios jugadores y participar en diferentes partidos. Cada partido deberá identificar a los equipos participantes y almacenar sus resultados, aplicando validaciones para evitar registros inconsistentes.

## 3. Principales funcionalidades

El módulo de autenticación permitirá registrar usuarios, iniciar y cerrar sesión, y restringir el acceso a las funciones privadas.

El módulo de equipos permitirá crear, consultar, editar y eliminar equipos. El módulo de jugadores permitirá registrar deportistas, modificar sus datos y asociarlos con el equipo correspondiente.

El módulo de partidos permitirá programar encuentros, registrar sus resultados y consultar el historial de partidos. Finalmente, el módulo de estadísticas mostrará información básica, como partidos jugados, victorias, derrotas, puntos anotados y puntos recibidos. También podrá incluir una tabla de posiciones calculada a partir de los resultados registrados.

## 4. Fragmentos de código relevantes

En Laravel, las rutas permiten asociar una solicitud con la operación que debe ejecutarse. Por ejemplo, una ruta de consulta de equipos podría declararse de la siguiente manera:

```php
Route::get('/equipos', [EquipoController::class, 'index']);
```

Este fragmento define una ruta HTTP GET que dirige la solicitud al método index del controlador EquipoController. Dicho método será responsable de recuperar y presentar los equipos registrados. El ejemplo deberá incorporarse y verificarse cuando se implemente el proyecto con Laravel.

## 5. Problemas encontrados y soluciones aplicadas

Durante la implementación se documentarán los problemas efectivamente encontrados, como errores de conexión con MySQL, validaciones incompletas, relaciones incorrectas entre equipos y jugadores o fallos al registrar los resultados de los partidos.

Cada incidencia deberá acompañarse de su causa identificada, la solución aplicada y las pruebas realizadas para verificar la corrección. Si se utiliza Laravel, también se revisarán las migraciones, las rutas y los mensajes de error para facilitar el diagnóstico.

## 6. Decisiones técnicas y justificación

Se propone utilizar MySQL por su capacidad para gestionar datos relacionados y mantener la integridad de la información. PHP y Laravel permitirán estructurar la aplicación y separar las responsabilidades de sus componentes. El uso de Git facilitará el seguimiento de los cambios mediante commits descriptivos y ramas para funcionalidades específicas, mientras que GitHub permitirá alojar el repositorio remoto y gestionar las integraciones mediante pull requests.

Estas decisiones deberán confirmarse según las herramientas finalmente utilizadas. Antes de la entrega, se actualizará este documento para reflejar las clases, los controladores, las funcionalidades, los problemas y las soluciones realmente implementados y probados.
