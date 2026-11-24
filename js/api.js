/**
 * Cliente API y Utilidades de Frontend para APAC Fresnillo
 */
const API = {
    csrfToken: '',
    usuarioActual: null,

    /**
     * Inicializa la sesión y recupera el estado de autenticación y token CSRF
     */
    async iniciar() {
        try {
            const res = await this.get('api/auth.php?accion=estado');
            if (res.ok) {
                this.csrfToken = res.token_csrf || '';
                this.usuarioActual = res.autenticado ? res.usuario : null;
            }
        } catch (e) {
            console.error('Error al inicializar sesión:', e);
        }
        return { autenticado: !!this.usuarioActual, usuario: this.usuarioActual, tokenCsrf: this.csrfToken };
    },

    /**
     * Realiza peticiones fetch centralizadas
     */
    async solicitar(url, opciones = {}) {
        opciones.credentials = 'same-origin';
        opciones.headers = opciones.headers || {};

        // Si aún no tenemos token CSRF y no es la consulta de estado, inicializar primero
        if (!this.csrfToken && !url.includes('auth.php?accion=estado')) {
            await this.iniciar();
        }

        if (this.csrfToken) {
            opciones.headers['X-CSRF-Token'] = this.csrfToken;
        }

        try {
            const respuesta = await fetch(url, opciones);
            const datos = await respuesta.json().catch(() => null);

            if (!respuesta.ok) {
                const mensaje = (datos && datos.error) ? datos.error : `Error en la solicitud (${respuesta.status})`;
                throw new Error(mensaje);
            }

            // Si la respuesta renueva o provee token_csrf
            if (datos && datos.token_csrf) {
                this.csrfToken = datos.token_csrf;
            }

            return datos;
        } catch (error) {
            console.error(`Error en API (${url}):`, error);
            throw error;
        }
    },

    /**
     * Petición GET
     */
    async get(url) {
        return this.solicitar(url, { method: 'GET' });
    },

    /**
     * Petición POST con datos JSON
     */
    async post(url, datos = {}) {
        return this.solicitar(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(datos)
        });
    },

    /**
     * Petición DELETE
     */
    async delete(url) {
        return this.solicitar(url, { method: 'DELETE' });
    },

    /**
     * Cierra la sesión activa
     */
    async logout() {
        try {
            await this.post('api/auth.php?accion=logout', {});
            this.usuarioActual = null;
            this.guardarMensajeFlash('ok', 'Sesión cerrada correctamente.');
            window.location.href = 'index.html';
        } catch (e) {
            alert('Error al cerrar sesión: ' + e.message);
        }
    },

    /**
     * Escapa texto para prevenir inyecciones HTML (XSS)
     */
    escapar(str) {
        if (str === null || str === undefined) return '';
        const p = document.createElement('p');
        p.textContent = String(str);
        return p.innerHTML;
    },

    /**
     * Guarda un mensaje temporal para mostrar en la siguiente página
     */
    guardarMensajeFlash(tipo, texto) {
        sessionStorage.setItem('apac_flash', JSON.stringify({ tipo, texto }));
    },

    /**
     * Lee y consume el mensaje temporal
     */
    obtenerMensajeFlash() {
        const item = sessionStorage.getItem('apac_flash');
        if (item) {
            sessionStorage.removeItem('apac_flash');
            try {
                return JSON.parse(item);
            } catch (e) {
                return null;
            }
        }
        return null;
    },

    /**
     * Renderiza un contenedor de mensaje (alerta/notificación)
     */
    mostrarMensaje(contenedor, tipo, texto) {
        if (!contenedor) return;
        contenedor.innerHTML = `<div class="mensaje ${this.escapar(tipo)}" role="alert">${this.escapar(texto)}</div>`;
    }
};
