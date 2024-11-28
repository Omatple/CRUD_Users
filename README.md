
# CRUD Users

Bienvenido a **CRUD_Users**, un proyecto que implementa un sistema básico de gestión de usuarios utilizando [PHP](https://www.php.net/), [MySQL](https://www.mysql.com/), y una interfaz responsive desarrollada con [Tailwind CSS](https://tailwindcss.com/).

## Características principales

- **Crear**: Permite agregar nuevos usuarios con nombre de usuario, correo electrónico y estado.
- **Leer**: Visualiza una lista de usuarios con detalles completos, incluyendo imágenes de perfil.
- **Actualizar**: Edita los datos de un usuario existente, incluido su estado y su imagen de perfil.
- **Eliminar**: Borra usuarios que ya no son necesarios.
- **Imagen de perfil**: Manejo de imágenes con opción de restablecer a la predeterminada.
- **Interfaz amigable**: UI moderna y responsiva gracias a Tailwind CSS.

## Tecnologías utilizadas

- **Backend**: PHP 8+.
- **Frontend**: HTML5, Tailwind CSS.
- **Base de datos**: MySQL.
- **Servidor local**: XAMPP.
- **Herramientas adicionales**: Visual Studio Code, SweetAlert2.

## Estructura del proyecto

```
CRUD_Users/
├── database/
│   ├── tables.sql           # Esquema de las tablas de la base de datos
├── public/
│   ├── img/                 # Directorio donde se almacenan las imágenes de los usuarios
│   ├── screenshots/         # Capturas de pantalla del proyecto
│   ├── delete.php           # Página para eliminar un usuario
│   ├── new.php              # Página para crear un nuevo usuario
│   ├── users.php            # Página con la lista de usuarios
│   ├── update.php           # Página para actualizar un usuario
├── scripts/
│   ├── imagePreview.js      # Script para la previsualización de imágenes
│   ├── generateFakeData.php         # Script para generar datos de prueba
├── src/
│   ├── Database/
│   │   ├── Connection.php    # Clase para manejar la conexión a la base de datos
│   │   ├── UserEntity.php    # Clase para gestionar usuarios
│   │   ├── StateEntity.php   # Clase para gestionar estados
│   │   ├── DatabaseQueryHandler.php # Clase para ejecutar consultas SQL
│   ├── Utils/
│       ├── AlertManager.php        # Clase para mostrar mensajes con SweetAlert2
│       ├── ErrorDisplay.php        # Clase para mostrar errores en los formularios
│       ├── ImageConfig.php         # Constantes relacionadas con imágenes
│       ├── ImageHandler.php        # Clase para procesar imágenes
│       ├── InputValidator.php      # Clase para validar entradas
│       ├── Navigator.php           # Clase para gestionar la navegación
│       ├── UserValidator.php       # Clase para validar datos de usuarios
│       ├── UserManager.php         # Clase para manejar acciones avanzadas de usuarios
├── .env                     # Variables de entorno del proyecto
├── README.md                # Documentación del proyecto
├── LICENSE                  # Licencia MIT
```

## Instalación y configuración

1. Clona este repositorio:
   ```bash
   git clone https://github.com/Omatple/CRUD_Users.git
   ```

2. Configura tu entorno local:
   - Descarga e instala [XAMPP](https://www.apachefriends.org/).
   - Crea una base de datos en MySQL e importa el archivo `tables.sql`.

3. Configura el archivo `.env` con tus credenciales de base de datos:
   ```env
   PORT=3306
   HOST=localhost
   DBNAME=crud_users
   USER=root
   PASSWORD=tu_password
   ```

4. Inicia el servidor local:
   - Coloca el proyecto en la carpeta `htdocs` de XAMPP.
   - Accede a [http://localhost/CRUD_Users](http://localhost/CRUD_Users).

## Capturas de pantalla

### Página principal y formulario de creación
| Página principal               | Formulario de creación           |
|--------------------------------|-----------------------------------|
| ![Página principal](public/screenshots/users.png) | ![Formulario de creación](public/screenshots/new.png) |

### Formulario de actualización y mensajes de estado
| Formulario de actualización     | Mensajes de estado              |
|---------------------------------|----------------------------------|
| ![Formulario de actualización](public/screenshots/update.png) | ![Mensajes de estado](public/screenshots/status-messages.png) |

## Cómo contribuir

1. Haz un fork del repositorio.
2. Crea una nueva rama:
   ```bash
   git checkout -b feature/new-feature
   ```
3. Realiza los cambios y haz un commit:
   ```bash
   git commit -m "Agregada nueva funcionalidad"
   ```
4. Envía un pull request.

## Licencia

Este proyecto está licenciado bajo la [MIT License](LICENSE).

## Contacto

¿Tienes preguntas o sugerencias? ¡Házmelo saber!  
**Ángel Martínez Otero** - [LinkedIn](https://linkedin.com/in/Omatple)  
