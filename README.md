# APAC Fresnillo - Guías para Practicar en Casa

Aplicación web para familias y terapeutas con guías de actividades guiadas en casa, registro de avances y envío de programas por correo electrónico.

El proyecto ha sido refactorizado con una arquitectura **desacoplada y ordenada**, basada en **Modelo-Vista-Controlador (MVC)** ligero:
- **Frontend:** HTML5 semántico + JavaScript moderno (Vanilla, Fetch API, componentes dinámicos de UI).
- **Backend:** PHP nativo estructurado en capas (Modelos, Controladores y Endpoints de API en JSON).
- **Base de datos:** MySQL / MariaDB (MySQLi con consultas preparadas seguras).
- **Correo:** PHPMailer por SMTP.

---

## 📁 Estructura del Proyecto

```text
apac/
├── index.html                 # Portada principal
├── guia.html                  # Selector de guías de apoyo
├── programa.html              # Detalle del programa y lista de actividades
├── actividad.html             # Paso a paso y registro de práctica
├── mi_avance.html             # Panel de estadísticas, historial y racha
├── login.html                 # Inicio de sesión
├── registro.html              # Registro de nuevas cuentas
│
├── api/                       # Endpoints backend que responden en JSON
│   ├── auth.php               # Estado de sesión, login, registro y logout
│   ├── practicas.php          # Guardar, listar y eliminar prácticas del usuario
│   ├── programas.php          # Catálogo de programas y detalle de actividades
│   └── correo.php             # Envío de la guía completa por correo SMTP
│
├── backend/                   # Lógica de negocio y persistencia (PHP)
│   ├── config/
│   │   └── Database.php       # Conexión MySQLi centralizada (Singleton)
│   ├── controllers/
│   │   ├── AuthController.php      # Lógica de autenticación y sesiones
│   │   ├── CatalogoController.php  # Lógica del catálogo de actividades
│   │   ├── CorreoController.php    # Preparación y envío de correos
│   │   └── PracticaController.php  # Lógica de avance, métricas y prácticas
│   ├── models/
│   │   ├── Catalogo.php       # Acceso a los datos de programas y actividades
│   │   ├── Practica.php       # Consultas y persistencia de prácticas
│   │   └── Usuario.php        # Consultas y registro de usuarios
│   └── utils/
│       ├── Response.php       # Respuestas JSON estandarizadas
│       └── Session.php        # Manejo seguro de sesiones y protección CSRF
│
├── js/                        # Lógica del Frontend (JavaScript)
│   ├── api.js                 # Cliente Fetch centralizado con manejo de CSRF
│   ├── ui.js                  # Renderizado compartido de Navbar y Footer
│   ├── auth.js                # Validación y envío de formularios de login/registro
│   ├── inicio.js              # Carga dinámica de tarjetas en portada
│   ├── guia.js                # Carga de guías temáticas
│   ├── programa.js            # Detalle, progreso y solicitud de envío por email
│   ├── actividad.js           # Vista paso a paso y formulario de registro
│   └── avance.js              # Gráfica semanal, resumen de programas y eliminación
│
├── css/
│   └── estilos.css            # Estilos CSS responsivos
├── img/                       # Ilustraciones y fotografías
├── sql/
│   └── apac.sql               # Esquema de base de datos MySQL
└── config.php                 # Configuración general y constantes
```

---

## 🚀 Instalación y Puesta en Marcha

1. **Copiar el proyecto** a la raíz de tu servidor web local (por ejemplo en `htdocs/apac` de XAMPP o directorio equivalente).
2. **Configuración local:**
   - Copia `config.local.example.php` como `config.local.php`.
   - Ajusta tus credenciales de MySQL (`db_host`, `db_user`, `db_pass`, `db_name`) y de correo SMTP si deseas probar el envío.
3. **Base de Datos:**
   - Importa `sql/apac.sql` en tu gestor MySQL (como phpMyAdmin) para crear las tablas `usuarios` y `practicas`.
4. **Abrir en el navegador:**
   - Visita `http://localhost/apac/` (abrirá automáticamente `index.html`).

---

## 🔒 Seguridad Implementada

- **Contraseñas seguras:** Encriptadas mediante `password_hash()` con algoritmo `PASSWORD_DEFAULT`.
- **Inyecciones SQL evitadas:** Todas las consultas a la base de datos utilizan sentencias preparadas (`prepare` y `bind_param`).
- **Protección contra CSRF:** Generación y validación automática de tokens CSRF tanto en cabeceras HTTP (`X-CSRF-Token`) como en solicitudes POST.
- **Protección contra XSS:** Sanitización con `htmlspecialchars` en PHP y renderizado seguro por manipulación de nodos DOM / `API.escapar()` en JavaScript.
- **Cookies de sesión:** Configuradas con flags `HttpOnly` y `SameSite=Lax`.
