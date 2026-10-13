# Documentación técnica: Gestión de Liga de Básquetbol

## 1. Descripción del proyecto

Gestión de Liga de Básquetbol es una aplicación web académica destinada a centralizar la información básica de una competición deportiva. El sistema presenta dos espacios relacionados: un panel público, desde el que cualquier visitante puede consultar la información publicada, y un panel administrativo protegido por autenticación, desde el que se mantienen los datos. Ambos espacios trabajan con una misma base de datos MySQL, por lo que los cambios realizados por el administrador se reflejan en las páginas públicas.

## 2. Objetivo

Desarrollar una aplicación web que permita administrar equipos, jugadores, encuentros y marcadores, además de mostrar el calendario, los resultados y una tabla de posiciones calculada a partir de los partidos finalizados. La solución busca reducir la duplicación de información y facilitar su consulta desde un navegador.

## 3. Tecnologías utilizadas

- **PHP:** lenguaje del lado del servidor para procesar formularios, validar entradas, controlar sesiones y consultar la base de datos.
- **MySQL/MariaDB:** sistema de gestión de base de datos para almacenar usuarios, equipos, jugadores y partidos.
- **PDO:** interfaz de acceso a datos que permite utilizar consultas preparadas y manejar errores de conexión.
- **HTML y CSS:** estructura y presentación adaptable a computadoras y dispositivos móviles.
- **Apache mediante XAMPP:** servidor local utilizado para ejecutar y probar la aplicación.
- **Git y GitHub:** control de versiones y alojamiento remoto del código fuente.

## 4. Módulos funcionales

El panel público incluye una página de inicio con indicadores generales, un catálogo de equipos, el calendario y los resultados de los encuentros, y una tabla de posiciones. Esta última toma en cuenta los partidos finalizados y ordena a los equipos por victorias, diferencia de puntos y puntos anotados. La regla de puntuación empleada es configurable según las normas de la competición; en esta versión de práctica se asignan dos puntos por victoria y uno por derrota.

El panel administrativo requiere correo y contraseña. Una vez autenticado, permite registrar, modificar y eliminar equipos; administrar jugadores y su equipo, dorsal y posición; y programar partidos o registrar sus marcadores. Se aplican restricciones para evitar eliminar equipos que todavía estén relacionados con jugadores o partidos, y se impide registrar un partido entre el mismo equipo.

## 5. Modelo de datos

La tabla `usuarios` contiene las cuentas autorizadas para administrar el sistema. `equipos` almacena nombre, ciudad y entrenador. `jugadores` incluye sus nombres, apellidos, dorsal y posición, y se relaciona con un equipo mediante una clave foránea. La tabla `partidos` enlaza un equipo local con uno visitante y registra fecha, hora, estado y puntuaciones. Las relaciones y restricciones de integridad ayudan a mantener datos consistentes.

## 6. Seguridad y validación

El sistema utiliza `password_hash` y `password_verify` para almacenar y comprobar contraseñas, sesiones para restringir las rutas administrativas, consultas preparadas de PDO para reducir el riesgo de inyección SQL y escape de texto al generar HTML. Los formularios de modificación incluyen un token CSRF. Las validaciones del servidor comprueban campos obligatorios, equipos distintos y marcadores válidos. La cuenta inicial es solo para instalación local; se debe cambiar su contraseña y eliminar el archivo `setup_admin.php` después de crearla.

## 7. Instalación y pruebas

Se debe iniciar Apache y MySQL desde XAMPP, copiar la carpeta del proyecto en `htdocs`, importar `database/schema.sql` mediante phpMyAdmin y revisar los parámetros de `config/database.php`. Después se abre una sola vez `setup_admin.php` para crear la cuenta inicial y se elimina ese archivo. La aplicación pública se consulta en `http://localhost/liga-basquetbol/` y el acceso administrativo en `http://localhost/liga-basquetbol/admin/login.php`.

Las pruebas mínimas consisten en iniciar sesión, registrar un equipo, añadir jugadores, programar un partido, registrar el resultado y verificar que el calendario y la tabla pública se actualicen. También debe comprobarse que un usuario no autenticado no pueda acceder a los módulos administrativos y que los formularios rechacen datos incompletos.

## 8. Control de versiones

El proyecto debe mantenerse bajo Git. Se recomienda realizar commits pequeños por módulo —estructura inicial, conexión de base de datos, autenticación, equipos, jugadores, partidos y documentación— y sincronizarlos con el repositorio remoto de GitHub. Para la entrega se debe incluir el código fuente, la carpeta `.git` si la consigna exige el historial, la URL del repositorio y esta documentación técnica.
