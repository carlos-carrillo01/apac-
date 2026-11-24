/**
 * Controlador de Autenticación en Frontend (Login y Registro)
 */
document.addEventListener('DOMContentLoaded', async () => {
    // Inicializar UI compartida (detectará sesión y renderizará header/footer)
    const urlParams = new URLSearchParams(window.location.search);
    const volver = urlParams.get('volver') || 'index.html';

    const sesion = await API.iniciar();

    // Si ya está autenticado, redirigir
    if (sesion.autenticado) {
        window.location.href = volver;
        return;
    }

    // Manejar formulario de Login
    const formLogin = document.getElementById('form-login');
    if (formLogin) {
        const contenedorMensaje = document.getElementById('mensaje-login');

        // Configurar enlace de registro con el parámetro volver
        const linkRegistro = document.getElementById('link-registro');
        if (linkRegistro) {
            linkRegistro.href = `registro.html?volver=${encodeURIComponent(volver)}`;
        }

        formLogin.addEventListener('submit', async (e) => {
            e.preventDefault();
            contenedorMensaje.innerHTML = '';

            const btnSubmit = formLogin.querySelector('button[type="submit"]');
            const correo = formLogin.correo.value.trim();
            const clave = formLogin.clave.value;

            btnSubmit.disabled = true;
            btnSubmit.textContent = 'Entrando...';

            try {
                const res = await API.post('api/auth.php?accion=login', { correo, clave });
                if (res.ok) {
                    API.guardarMensajeFlash('ok', res.mensaje || '¡Bienvenido!');
                    window.location.href = volver;
                }
            } catch (error) {
                API.mostrarMensaje(contenedorMensaje, 'error', error.message);
                btnSubmit.disabled = false;
                btnSubmit.textContent = 'Entrar';
            }
        });
    }

    // Manejar formulario de Registro
    const formRegistro = document.getElementById('form-registro');
    if (formRegistro) {
        const contenedorMensaje = document.getElementById('mensaje-registro');

        // Configurar enlace de login con el parámetro volver
        const linkLogin = document.getElementById('link-login');
        if (linkLogin) {
            linkLogin.href = `login.html?volver=${encodeURIComponent(volver)}`;
        }

        formRegistro.addEventListener('submit', async (e) => {
            e.preventDefault();
            contenedorMensaje.innerHTML = '';

            const btnSubmit = formRegistro.querySelector('button[type="submit"]');
            const nombre = formRegistro.nombre.value.trim();
            const correo = formRegistro.correo.value.trim();
            const clave = formRegistro.clave.value;
            const confirmacion = formRegistro.confirmacion.value;

            if (clave.length < 8) {
                API.mostrarMensaje(contenedorMensaje, 'error', 'La contraseña debe tener al menos 8 caracteres.');
                return;
            }

            if (clave !== confirmacion) {
                API.mostrarMensaje(contenedorMensaje, 'error', 'Las contraseñas no coinciden.');
                return;
            }

            btnSubmit.disabled = true;
            btnSubmit.textContent = 'Creando cuenta...';

            try {
                const res = await API.post('api/auth.php?accion=registro', {
                    nombre,
                    correo,
                    clave,
                    confirmacion
                });

                if (res.ok) {
                    API.guardarMensajeFlash('ok', 'Tu cuenta está lista y tu sesión ha iniciado.');
                    window.location.href = volver;
                }
            } catch (error) {
                API.mostrarMensaje(contenedorMensaje, 'error', error.message);
                btnSubmit.disabled = false;
                btnSubmit.textContent = 'Crear cuenta';
            }
        });
    }
});
