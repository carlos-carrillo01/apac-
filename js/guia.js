/**
 * Lógica para la vista de selección de Guías con imágenes y filtros por categoría
 */
document.addEventListener('DOMContentLoaded', async () => {
    const contenedor = document.getElementById('contenedor-guias');
    const botonesFiltro = document.querySelectorAll('.btn-filtro');
    if (!contenedor) return;

    let todosLosProgramas = [];

    const renderizarProgramas = (categoriaSeleccionada = 'todos') => {
        const filtrados = categoriaSeleccionada === 'todos'
            ? todosLosProgramas
            : todosLosProgramas.filter(p => p.categoria === categoriaSeleccionada);

        if (filtrados.length === 0) {
            contenedor.innerHTML = '<p class="mensaje" style="grid-column: 1 / -1; text-align: center;">No hay guías disponibles en esta categoría.</p>';
            return;
        }

        contenedor.innerHTML = filtrados.map(prog => {
            const img = prog.imagen || 'img/estimulacion-temprana.jpg';
            const catNombre = prog.categoria_nombre || 'Práctica en casa';
            const actividadesTexto = prog.total_actividades === 1 ? '1 actividad' : `${prog.total_actividades} actividades`;

            return `
                <a class="tarjeta-guia" href="programa.html?p=${encodeURIComponent(prog.slug)}" aria-label="Abrir guía: ${API.escapar(prog.nombre)}">
                    <div class="tarjeta-guia-foto">
                        <img src="${API.escapar(img)}" alt="${API.escapar(prog.nombre)}" loading="lazy">
                    </div>
                    <div class="tarjeta-guia-cuerpo">
                        <span class="etiqueta etiqueta-verde" style="align-self: flex-start; margin-bottom: 6px;">${API.escapar(catNombre)}</span>
                        <h3>${API.escapar(prog.nombre)}</h3>
                        ${prog.para ? `<small style="color: var(--verde); font-weight: 700; margin-bottom: 8px; display: block;">🎯 Para: ${API.escapar(prog.para)}</small>` : ''}
                        <p>${API.escapar(prog.resumen)}</p>
                        <div class="tarjeta-guia-pie">
                            <span class="actividades-badge">📋 ${actividadesTexto}</span>
                            <span class="flecha">Ver guía →</span>
                        </div>
                    </div>
                </a>
            `;
        }).join('');
    };

    // Eventos en botones de filtro
    botonesFiltro.forEach(boton => {
        boton.addEventListener('click', () => {
            botonesFiltro.forEach(b => {
                b.classList.remove('activo');
                b.setAttribute('aria-selected', 'false');
            });
            boton.classList.add('activo');
            boton.setAttribute('aria-selected', 'true');

            const categoria = boton.dataset.categoria || 'todos';
            renderizarProgramas(categoria);
        });
    });

    try {
        const res = await API.get('api/programas.php');
        if (!res.ok || !res.programas) {
            contenedor.innerHTML = '<p class="mensaje error">No se pudieron cargar las guías en este momento.</p>';
            return;
        }

        todosLosProgramas = res.programas;
        renderizarProgramas('todos');
    } catch (e) {
        console.error('Error al cargar guías:', e);
        contenedor.innerHTML = '<p class="mensaje error">Ocurrió un error de conexión al cargar las guías.</p>';
    }
});
