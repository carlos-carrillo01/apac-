/**
 * Lógica para la vista de selección de Guías
 */
document.addEventListener('DOMContentLoaded', async () => {
    const contenedor = document.getElementById('contenedor-guias');
    if (!contenedor) return;

    try {
        const res = await API.get('api/programas.php');
        if (!res.ok || !res.programas) {
            contenedor.innerHTML = '<p class="mensaje error">No se pudieron cargar las guías en este momento.</p>';
            return;
        }

        const html = res.programas.map(prog => `
            <a class="situacion" href="programa.html?p=${encodeURIComponent(prog.slug)}">
                <small class="situacion-para">${API.escapar(prog.para || 'actividades guiadas')}</small>
                <strong>${API.escapar(prog.nombre)}</strong>
                <small>${API.escapar(prog.resumen)}</small>
                <span class="enlace-flecha">Ver esta guía</span>
            </a>
        `).join('');

        contenedor.innerHTML = html;
    } catch (e) {
        console.error('Error al cargar guías:', e);
        contenedor.innerHTML = '<p class="mensaje error">Ocurrió un error de conexión al cargar las guías.</p>';
    }
});
