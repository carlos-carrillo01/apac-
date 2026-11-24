/**
 * Manejador de interfaz de usuario compartida (Header, Footer, Mensajes)
 */
const UI = {
    async init(paginaActiva = '') {
        // Inicializar cliente y sesión
        const sesion = await API.iniciar();

        // Renderizar Encabezado
        this.renderEncabezado(paginaActiva, sesion.autenticado, sesion.usuario);

        // Renderizar Pie de página
        this.renderPie();

        // Mostrar mensajes flash si existen
        this.renderMensajeFlash();
    },

    renderEncabezado(paginaActiva, autenticado, usuario) {
        const contenedor = document.getElementById('app-header');
        if (!contenedor) return;

        const navAutenticado = autenticado
            ? `
                <a href="mi_avance.html" ${paginaActiva === 'mi_avance' ? 'class="activo"' : ''}>mi avance</a>
                <button type="button" id="btn-logout" class="boton-enlace">salir</button>
              `
            : `
                <a href="login.html" ${paginaActiva === 'login' ? 'class="activo"' : ''}>entrar</a>
                <a class="boton boton-chico" href="registro.html">crear cuenta</a>
              `;

        contenedor.innerHTML = `
            <a class="saltar" href="#contenido">saltar al contenido</a>
            <div class="barra-superior">lunes a viernes, de 8:00 a.m. a 4:00 p.m. | tel. 4939357179 y 4939357809</div>
            <header class="encabezado">
                <div class="contenedor encabezado-interno">
                    <a class="logo" href="index.html">
                        <img src="img/logo.png" alt="Logotipo APAC">
                        <span><b>apac</b> fresnillo<small>terapia guiada en casa</small></span>
                    </a>
                    <nav aria-label="menu principal">
                        <a href="index.html" ${paginaActiva === 'inicio' ? 'class="activo"' : ''}>inicio</a>
                        <a href="guia.html" ${paginaActiva === 'guia' ? 'class="activo"' : ''}>elige una guia</a>
                        <a href="index.html#programas" ${paginaActiva === 'programas' ? 'class="activo"' : ''}>programas</a>
                        ${navAutenticado}
                    </nav>
                </div>
            </header>
        `;

        const btnLogout = document.getElementById('btn-logout');
        if (btnLogout) {
            btnLogout.addEventListener('click', async (e) => {
                e.preventDefault();
                await API.logout();
            });
        }
    },

    renderPie() {
        const contenedor = document.getElementById('app-footer');
        if (!contenedor) return;

        contenedor.innerHTML = `
            <footer class="pie">
                <div class="contenedor pie-interno">
                    <div>
                        <strong>apac fresnillo</strong>
                        <p>asociacion pro personas con paralisis cerebral<br>reg. cofepris 2432035036X00130</p>
                    </div>
                    <div>
                        <strong>contacto</strong>
                        <p>lunes a viernes, de 8:00 a.m. a 4:00 p.m.<br>tel. 4939357179 y 4939357809<br>facebook: apac fresnillo | instagram y x: @apacfresnillo</p>
                    </div>
                    <div>
                        <strong>importante</strong>
                        <p>estas guias son un apoyo para practicar en casa. no son un diagnostico ni sustituyen la valoracion o el tratamiento de profesionales de salud.</p>
                    </div>
                </div>
            </footer>
        `;
    },

    renderMensajeFlash() {
        const flash = API.obtenerMensajeFlash();
        const contenedor = document.getElementById('app-flash');
        if (flash && contenedor) {
            API.mostrarMensaje(contenedor, flash.tipo, flash.texto);
        }
    }
};
