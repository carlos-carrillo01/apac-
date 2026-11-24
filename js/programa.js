/**
 * Controlador de la vista de Detalle de Programa
 */
document.addEventListener('DOMContentLoaded', async () => {
    const params = new URLSearchParams(window.location.search);
    const slug = params.get('p') || '';

    const divCargando = document.getElementById('programa-cargando');
    const divContenido = document.getElementById('programa-contenido');

    if (!slug) {
        window.location.href = 'index.html#programas';
        return;
    }

    try {
        // Cargar detalles del programa desde la API
        const resProg = await API.get(`api/programas.php?p=${encodeURIComponent(slug)}`);
        if (!resProg.ok || !resProg.programa) {
            divCargando.innerHTML = '<p class="mensaje error">No encontramos ese programa. <a href="index.html#programas">Volver a programas</a></p>';
            return;
        }

        const programa = resProg.programa;
        document.title = `${programa.nombre} | apac fresnillo`;

        const migaPrograma = document.getElementById('miga-programa');
        if (migaPrograma) {
            migaPrograma.textContent = programa.nombre;
        }

        // Obtener estado de autenticación y prácticas realizadas
        const sesion = await API.iniciar();
        let hechas = {};

        if (sesion.autenticado) {
            try {
                const resHechas = await API.get(`api/practicas.php?programa=${encodeURIComponent(slug)}`);
                if (resHechas.ok && resHechas.hechas) {
                    hechas = resHechas.hechas;
                }
            } catch (e) {
                console.error('Error al consultar prácticas del programa:', e);
            }
        }

        // Renderizar encabezado principal del programa
        const totalActividades = programa.actividades.length;
        const totalPracticadas = Object.keys(hechas).length;
        const porcentaje = totalActividades > 0 ? Math.round((totalPracticadas * 100) / totalActividades) : 0;

        let progresoHtml = '';
        if (sesion.autenticado) {
            progresoHtml = `
                <div class="progreso-bloque">
                    <strong>tu avance: ${totalPracticadas} de ${totalActividades} actividades registradas</strong>
                    <div class="barra"><span style="width: ${porcentaje}%"></span></div>
                </div>
            `;
        } else {
            progresoHtml = `
                <p class="chico"><a href="registro.html?volver=${encodeURIComponent('programa.html?p=' + slug)}">crea una cuenta</a> si quieres guardar tus practicas.</p>
            `;
        }

        document.getElementById('programa-principal').innerHTML = `
            <img src="${API.escapar(programa.imagen)}" alt="">
            <div>
                <span class="etiqueta etiqueta-verde">${API.escapar(programa.para || 'programa en casa')}</span>
                <h1>${API.escapar(programa.nombre)}</h1>
                <p>${API.escapar(programa.resumen)}</p>
                ${progresoHtml}
            </div>
        `;

        // Detente si
        document.getElementById('programa-detente').innerHTML = programa.detente_si
            .map(item => `<li>${API.escapar(item)}</li>`).join('');

        // Materiales y frecuencia
        document.getElementById('programa-materiales').innerHTML = programa.materiales
            .map(item => `<li>${API.escapar(item)}</li>`).join('');
        document.getElementById('programa-frecuencia').textContent = programa.frecuencia;

        // Antes de empezar
        document.getElementById('programa-antes').innerHTML = programa.antes_de_empezar
            .map(item => `<li>${API.escapar(item)}</li>`).join('');

        // Actividades
        const contenedorActividades = document.getElementById('programa-actividades');
        contenedorActividades.innerHTML = programa.actividades.map((act, idx) => {
            const pract = hechas[act.slug];
            const hechaHtml = pract ? `<p class="hecha">la has hecho ${pract.veces} veces</p>` : '';
            return `
                <article class="tarjeta">
                    <a class="tarjeta-foto" href="actividad.html?p=${encodeURIComponent(slug)}&a=${encodeURIComponent(act.slug)}">
                        <img src="${API.escapar(act.imagen)}" alt="ilustracion de ${API.escapar(act.nombre)}" loading="lazy">
                    </a>
                    <div class="tarjeta-texto">
                        <span class="etiqueta">actividad ${idx + 1} | ${API.escapar(act.duracion)}</span>
                        <h3>${API.escapar(act.nombre)}</h3>
                        <p>${API.escapar(act.objetivo)}</p>
                        ${hechaHtml}
                        <a class="enlace-flecha" href="actividad.html?p=${encodeURIComponent(slug)}&a=${encodeURIComponent(act.slug)}">ver la guia</a>
                    </div>
                </article>
            `;
        }).join('');

        // Bloque de Correo
        const bloqueCorreo = document.getElementById('bloque-correo');
        if (sesion.autenticado) {
            bloqueCorreo.innerHTML = `
                <div>
                    <h2>recibe esta guia por correo</h2>
                    <p>enviaremos el programa completo a <strong>${API.escapar(sesion.usuario.correo)}</strong>.</p>
                </div>
                <div>
                    <button type="button" id="btn-enviar-correo" class="boton boton-grande">enviarme la guia por correo</button>
                    <div id="mensaje-correo" style="margin-top:10px;"></div>
                </div>
            `;

            document.getElementById('btn-enviar-correo').addEventListener('click', async (e) => {
                const btn = e.target;
                const mensajeContenedor = document.getElementById('mensaje-correo');
                btn.disabled = true;
                btn.textContent = 'Enviando guía...';
                mensajeContenedor.innerHTML = '';

                try {
                    const res = await API.post('api/correo.php', { programa: slug });
                    API.mostrarMensaje(mensajeContenedor, 'ok', res.mensaje || 'Guía enviada correctamente.');
                    btn.textContent = 'Guía enviada';
                } catch (err) {
                    API.mostrarMensaje(mensajeContenedor, 'error', err.message);
                    btn.disabled = false;
                    btn.textContent = 'enviarme la guia por correo';
                }
            });
        } else {
            bloqueCorreo.innerHTML = `
                <div>
                    <h2>recibe esta guia por correo</h2>
                    <p>crea una cuenta para enviar el programa a tu correo y guardar tus practicas.</p>
                </div>
                <a class="boton" href="registro.html?volver=${encodeURIComponent('programa.html?p=' + slug)}">crear cuenta</a>
            `;
        }

        // Mostrar sección de contenido
        divCargando.style.display = 'none';
        divContenido.style.display = 'block';

    } catch (e) {
        console.error('Error al inicializar programa:', e);
        divCargando.innerHTML = '<p class="mensaje error">Ocurrió un error al cargar el programa.</p>';
    }
});
