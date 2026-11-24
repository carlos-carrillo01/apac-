/**
 * Controlador de la vista de Detalle de Actividad y Registro de Prácticas
 */
document.addEventListener('DOMContentLoaded', async () => {
    const params = new URLSearchParams(window.location.search);
    const slugPrograma = params.get('p') || '';
    const slugActividad = params.get('a') || '';

    const divCargando = document.getElementById('actividad-cargando');
    const divContenido = document.getElementById('actividad-contenido');

    if (!slugPrograma || !slugActividad) {
        window.location.href = 'index.html#programas';
        return;
    }

    try {
        // Cargar detalle de la actividad
        const res = await API.get(`api/programas.php?p=${encodeURIComponent(slugPrograma)}&a=${encodeURIComponent(slugActividad)}`);
        if (!res.ok || !res.actividad) {
            divCargando.innerHTML = '<p class="mensaje error">No encontramos la actividad solicitada. <a href="index.html#programas">Volver a programas</a></p>';
            return;
        }

        const programa = res.programa;
        const act = res.actividad;

        document.title = `${act.nombre} | apac fresnillo`;

        // Configurar enlace volver
        const linkVolver = document.getElementById('link-volver-programa');
        linkVolver.href = `programa.html?p=${encodeURIComponent(slugPrograma)}`;
        linkVolver.textContent = `volver al programa: ${programa.nombre}`;

        // Textos y etiquetas principales
        document.getElementById('actividad-etiqueta').textContent = `actividad ${act.posicion} de ${act.total} | ${act.duracion}`;
        document.getElementById('actividad-titulo').textContent = act.nombre;
        document.getElementById('actividad-objetivo').textContent = act.objetivo;

        // Foto o ilustración
        const foto = document.getElementById('actividad-foto');
        foto.src = act.imagen;
        foto.alt = `ilustracion de ${act.nombre}`;

        // Cuidado especial
        const divCuidado = document.getElementById('actividad-cuidado');
        if (act.cuidado && act.cuidado.trim() !== '') {
            divCuidado.innerHTML = `<strong>cuidado:</strong> ${API.escapar(act.cuidado)}`;
            divCuidado.style.display = 'block';
        }

        // Materiales
        document.getElementById('actividad-materiales').innerHTML = act.materiales
            .map(m => `<li>${API.escapar(m)}</li>`).join('');

        // Pasos (línea de tiempo)
        document.getElementById('actividad-pasos').innerHTML = act.pasos
            .map(p => `<li><div><p>${API.escapar(p)}</p></div></li>`).join('');

        // Consejos y versión más fácil
        document.getElementById('actividad-consejos').innerHTML = act.consejos
            .map(c => `<li>${API.escapar(c)}</li>`).join('');
        document.getElementById('actividad-facil').textContent = act.mas_facil;

        // Estado de autenticación y registros de práctica previos
        const sesion = await API.iniciar();
        const contenedorForm = document.getElementById('contenedor-formulario-practica');
        const urlActual = `actividad.html?p=${encodeURIComponent(slugPrograma)}&a=${encodeURIComponent(slugActividad)}`;

        if (sesion.autenticado) {
            // Consultar cuántas veces se ha registrado esta actividad
            let miRegistro = null;
            try {
                const resPracticas = await API.get(`api/practicas.php?programa=${encodeURIComponent(slugPrograma)}`);
                if (resPracticas.ok && resPracticas.hechas && resPracticas.hechas[slugActividad]) {
                    miRegistro = resPracticas.hechas[slugActividad];
                }
            } catch (err) {
                console.error(err);
            }

            let infoVeces = '';
            if (miRegistro) {
                const fecha = new Date(miRegistro.ultima).toLocaleDateString('es-MX');
                infoVeces = `<p class="hecha" id="info-veces">esta actividad tiene ${miRegistro.veces} registros. el ultimo fue el ${fecha}.</p>`;
            } else {
                infoVeces = '<p class="hecha" id="info-veces" style="display:none;"></p>';
            }

            contenedorForm.innerHTML = `
                ${infoVeces}
                <form id="form-registrar-practica" class="formulario">
                    <fieldset class="opciones">
                        <legend>como te fue?</legend>
                        <label class="opcion"><input type="radio" name="como_fue" value="bien" required><span>bien</span></label>
                        <label class="opcion"><input type="radio" name="como_fue" value="regular"><span>mas o menos</span></label>
                        <label class="opcion"><input type="radio" name="como_fue" value="molestia"><span>con molestia</span></label>
                    </fieldset>
                    <label for="nota">nota opcional</label>
                    <input id="nota" name="nota" maxlength="200" placeholder="no escribas diagnosticos ni datos privados de salud">
                    <button class="boton boton-grande" type="submit">guardar practica</button>
                </form>
            `;

            // Event listener para enviar la práctica vía API
            const formPractica = document.getElementById('form-registrar-practica');
            const contMensaje = document.getElementById('mensaje-practica');

            formPractica.addEventListener('submit', async (e) => {
                e.preventDefault();
                contMensaje.innerHTML = '';
                const btnSubmit = formPractica.querySelector('button[type="submit"]');
                const comoFue = formPractica.como_fue.value;
                const nota = formPractica.nota.value.trim();

                btnSubmit.disabled = true;
                btnSubmit.textContent = 'Guardando...';

                try {
                    const resGuardar = await API.post('api/practicas.php', {
                        programa: slugPrograma,
                        actividad: slugActividad,
                        como_fue: comoFue,
                        nota: nota
                    });

                    API.mostrarMensaje(contMensaje, 'ok', resGuardar.mensaje || '¡Práctica guardada!');
                    formPractica.reset();

                    // Actualizar el conteo en pantalla
                    const infoP = document.getElementById('info-veces');
                    if (infoP) {
                        const hoy = new Date().toLocaleDateString('es-MX');
                        infoP.textContent = `¡Registro añadido hoy (${hoy})!`;
                        infoP.style.display = 'block';
                    }
                } catch (error) {
                    API.mostrarMensaje(contMensaje, 'error', error.message);
                } finally {
                    btnSubmit.disabled = false;
                    btnSubmit.textContent = 'guardar practica';
                }
            });

        } else {
            contenedorForm.innerHTML = `
                <p>crea una cuenta si quieres guardar una practica y consultar tu avance.</p>
                <div class="botones">
                    <a class="boton" href="registro.html?volver=${encodeURIComponent(urlActual)}">crear cuenta</a>
                    <a class="boton boton-claro" href="login.html?volver=${encodeURIComponent(urlActual)}">ya tengo cuenta</a>
                </div>
            `;
        }

        // Navegación (anterior / siguiente)
        const divNav = document.getElementById('actividad-navegacion');
        const btnAnterior = act.anterior
            ? `<a class="boton boton-claro" href="actividad.html?p=${encodeURIComponent(slugPrograma)}&a=${encodeURIComponent(act.anterior)}">actividad anterior</a>`
            : `<span></span>`;

        const btnSiguiente = act.siguiente
            ? `<a class="boton" href="actividad.html?p=${encodeURIComponent(slugPrograma)}&a=${encodeURIComponent(act.siguiente)}">siguiente actividad</a>`
            : `<a class="boton" href="programa.html?p=${encodeURIComponent(slugPrograma)}">volver al programa</a>`;

        divNav.innerHTML = `${btnAnterior}${btnSiguiente}`;

        // Mostrar contenido
        divCargando.style.display = 'none';
        divContenido.style.display = 'block';

    } catch (e) {
        console.error('Error al cargar actividad:', e);
        divCargando.innerHTML = '<p class="mensaje error">Ocurrió un error al cargar la actividad.</p>';
    }
});
