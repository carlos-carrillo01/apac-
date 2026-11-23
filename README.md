# apac fresnillo: guias para practicar en casa

Aplicacion web sencilla con php, mysql, css y phpmailer. Incluye guias generales de actividades, cuentas para guardar practicas y envio del programa completo por correo.

## funciones

1. `index.php`: portada responsiva con tarjetas html escritas de forma explicita.
2. `guia.php`: selector con una tarjeta por cada programa del catalogo php.
3. `programa.php`: materiales, cuidados, frecuencia y actividades del tema elegido.
4. `actividad.php`: guia paso a paso y formulario opcional para registrar una practica.
5. `mi_avance.php`: resumen de practicas guardadas en la cuenta.
6. `enviar_programa.php`: envia los materiales, cuidados y actividades del programa a la direccion de correo de la cuenta.
7. `includes/catalogo.php`: catalogo fijo de programas y actividades en php.

El catalogo ofrece ideas generales de apoyo para movimiento, comunicacion, preferencias sensoriales, rutinas, bienestar y busqueda de orientacion profesional. No diagnostica ni trata condiciones. apac debe revisar el contenido con profesionales antes de publicarlo.

## instalacion

1. copia la carpeta a la raiz de tu servidor php, por ejemplo `htdocs/apac`.
2. copia `config.local.example.php` como `config.local.php`. completa ahi los datos privados de mysql y correo. ese archivo esta excluido de git.
3. importa `sql/apac.sql` desde phpmyadmin y confirma el nombre de la base configurada.
4. abre `http://localhost/apac/`.

Tambien se pueden definir variables de entorno con el prefijo `APAC_`: `APAC_DB_HOST`, `APAC_DB_USER`, `APAC_DB_PASS`, `APAC_DB_NAME`, `APAC_SMTP_HOST`, `APAC_SMTP_PUERTO`, `APAC_SMTP_USUARIO`, `APAC_SMTP_CLAVE` y `APAC_CORREO_NOMBRE`. Las variables del servidor tienen prioridad sobre el archivo local.

Para gmail, usa una contrasena de aplicacion nueva y no la agregues al repositorio. El envio de correo no funcionara hasta que se configuren `smtp_usuario` y `smtp_clave`. En un servidor diferente de apache, protege `config.local.php` y alojalo fuera de la carpeta publica cuando sea posible.

## seguridad de credenciales

El archivo `config.php` no incluye contrasenas. No subas `config.local.php`, `.env` ni credenciales al control de versiones. Si alguna contrasena estuvo en un repositorio publico, revocala, crea una nueva y elimina el valor antiguo del historial del repositorio.

## contenido y limites

Los programas y las situaciones ya no se cargan desde `datos/*.json`. Las tarjetas principales se escriben directamente en `index.php`. El selector de `guia.php` lee el catalogo php para mostrar una tarjeta unica por guia; el contenido detallado de programas y actividades esta definido en `includes/catalogo.php`.

Las actividades son orientativas. No suspendas medicamentos ni sustancias, no cambies tratamientos, no fuerces ejercicios y consulta a un profesional para adaptar los movimientos. Si existe peligro inmediato, contacta los servicios de emergencia locales.

## archivos principales

- `index.php`, `guia.php`: portada y selector de temas.
- `programa.php`, `actividad.php`: programas y actividades.
- `login.php`, `registro.php`, `salir.php`: acceso a la cuenta.
- `registrar_practica.php`, `mi_avance.php`: seguimiento personal.
- `enviar_programa.php`, `includes/correo.php`: envio de mensajes con phpmailer.
- `includes/catalogo.php`, `includes/datos.php`: programas definidos en php.
- `includes/funciones.php`, `includes/avance.php`: sesion, seguridad y consultas.
- `css/estilos.css`: estilos de escritorio y telefonos.
- `config.local.example.php`: plantilla sin credenciales reales.
