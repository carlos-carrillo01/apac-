/**
 * Controlador de la vista de Mi Avance
 */
document.addEventListener('DOMContentLoaded', async () => {
    const divCargando = document.getElementById('avance-cargando');
    const divContenido = document.getElementById('avance-contenido');
    const divVacio = document.getElementById('avance-vacio');
    const divDatos = document.getElementById('avance-datos');
    const contMensaje = document.getElementById('mensaje-avance');

    // Verificar sesión primero
    const sesion = await API.iniciar();
    if (!sesion.autenticado) {
        window.location.href = 'login.html?volver=' + encodeURIComponent('mi_avance.html');
        return;
    }

    document.getElementById('saludo-usuario').textContent = `hola, ${sesion.usuario.nombre}`;

    async function cargarResumen() {
        try {
            const res = await API.get('api/practicas.php');
            if (!res.ok) {
                divCargando.innerHTML = '<p class="mensaje error">No se pudo cargar el avance.</p>';
                return;
            }

            const general = res.general;
            const programas = res.programas || [];
            const historial = res.historial || [];

            divCargando.style.display = 'none';
            divContenido.style.display = 'block';

            if (general.sesiones === 0) {
                divVacio.style.display = 'block';
                divDatos.style.display = 'none';
                return;
            }

            divVacio.style.display = 'none';
            divDatos.style.display = 'block';

            // Estadísticas numéricas
            document.getElementById('stat-sesiones').textContent = general.sesiones;
            document.getElementById('stat-dias').textContent = general.dias;
            document.getElementById('stat-racha').textContent = general.racha;

            // Grilla de los últimos 7 días
            const gridSemana = document.getElementById('grid-semana');
            gridSemana.innerHTML = general.semana.map(d => `
                <div class="dia ${d.hecho ? 'hecho' : ''} ${d.hoy ? 'hoy' : ''}" role="group" aria-label="${API.escapar(d.letra)}: ${d.hecho ? 'Hecho' : 'Pendiente'}">
                    <span>${API.escapar(d.letra)}</span>
                    <strong class="dia-estado">
                        <span class="estado-completo">${d.hecho ? 'Hecho' : 'Pendiente'}</span>
                        <span class="estado-corto" aria-hidden="true">${d.hecho ? 'Hecho' : 'Pend.'}</span>
                    </strong>
                </div>
            `).join('');

            // Programas en curso
            const contProgramas = document.getElementById('lista-programas-curso');
            contProgramas.innerHTML = programas.map(p => `
                <article class="caja">
                    <h3>${API.escapar(p.nombre)}</h3>
                    <div class="barra"><span style="width: ${p.porcentaje}%"></span></div>
                    <p>
                        <strong>${p.distintas} de ${p.total_actividades}</strong> actividades registradas | ${p.sesiones} practicas<br>
                        <small>ultima practica: ${API.escapar(p.fecha_formateada)}</small>
                    </p>
                    <a class="boton boton-chico" href="programa.html?p=${encodeURIComponent(p.programa)}">continuar</a>
                </article>
            `).join('');

            // Historial de prácticas
            const contHistorial = document.getElementById('lista-historial');
            if (historial.length === 0) {
                contHistorial.innerHTML = '<p class="chico">No hay registros recientes.</p>';
            } else {
                contHistorial.innerHTML = historial.map(reg => `
                    <article class="registro" id="reg-${reg.id}">
                        <img src="${API.escapar(reg.imagen)}" alt="">
                        <div class="registro-texto">
                            <strong><a href="actividad.html?p=${encodeURIComponent(reg.programa)}&a=${encodeURIComponent(reg.actividad)}">${API.escapar(reg.nombre_actividad)}</a></strong>
                            <span class="chico">${API.escapar(reg.fecha_formateada)} | ${API.escapar(reg.como_fue_texto)}</span>
                            ${reg.nota ? `<span class="nota">${API.escapar(reg.nota)}</span>` : ''}
                        </div>
                        <button type="button" class="boton boton-chico boton-peligro btn-eliminar-practica" data-id="${reg.id}">quitar</button>
                    </article>
                `).join('');

                // Asignar eventos de eliminación
                document.querySelectorAll('.btn-eliminar-practica').forEach(btn => {
                    btn.addEventListener('click', async (e) => {
                        const id = e.target.getAttribute('data-id');
                        if (!confirm('¿Deseas eliminar este registro de práctica?')) return;

                        e.target.disabled = true;
                        e.target.textContent = '...';

                        try {
                            const resEliminar = await API.post('api/practicas.php', {
                                accion: 'eliminar',
                                id: id
                            });
                            API.mostrarMensaje(contMensaje, 'ok', resEliminar.mensaje || 'Práctica eliminada.');
                            await cargarResumen();
                        } catch (err) {
                            API.mostrarMensaje(contMensaje, 'error', err.message);
                            e.target.disabled = false;
                            e.target.textContent = 'quitar';
                        }
                    });
                });
            }

        } catch (e) {
            console.error('Error al cargar resumen:', e);
            divCargando.innerHTML = '<p class="mensaje error">Ocurrió un error al cargar el avance.</p>';
        }
    }

    await cargarResumen();
});
