/**
 * Lógica de la página de Inicio (Carga dinámica de programas)
 */
document.addEventListener('DOMContentLoaded', async () => {
    const contenedor = document.getElementById('contenedor-programas');
    if (!contenedor) return;

    const imagenesProgramas = {
        'bebes': 'img/estimulacion-temprana.jpg',
        'movilidad': 'img/terapia-fisica.jpg',
        'lenguaje': 'img/lenguaje.jpg',
        'comunicacion-alternativa': 'img/lenguaje.jpg',
        'sentidos': 'img/actividades/musica-ritmo.svg',
        'autismo': 'img/estimulacion-temprana.jpg',
        'bienestar-emocional': 'img/hero.jpg',
        'atencion-rutinas': 'img/lenguaje.jpg',
        'memoria-rutinas': 'img/nosotros.jpg',
        'bienestar-corporal': 'img/terapia-fisica.jpg',
        'apoyo-profesional': 'img/nosotros.jpg'
    };

    try {
        const respuesta = await API.get('api/programas.php');
        if (!respuesta.ok || !respuesta.programas) {
            contenedor.innerHTML = '<p>No se pudieron cargar los programas.</p>';
            return;
        }

        const html = respuesta.programas.map(prog => {
            const imagen = imagenesProgramas[prog.slug] || 'img/estimulacion-temprana.jpg';
            return `
                <article class="tarjeta">
                    <a class="tarjeta-foto" href="programa.html?p=${encodeURIComponent(prog.slug)}">
                        <img class="foto-fija" src="${API.escapar(imagen)}" alt="${API.escapar(prog.nombre)}" loading="lazy">
                    </a>
                    <div class="tarjeta-texto">
                        <span class="etiqueta">${API.escapar(prog.total_actividades)} actividades</span>
                        <h3>${API.escapar(prog.nombre)}</h3>
                        <p>${API.escapar(prog.resumen)}</p>
                        <a class="enlace-flecha" href="programa.html?p=${encodeURIComponent(prog.slug)}">ver la guia</a>
                    </div>
                </article>
            `;
        }).join('');

        contenedor.innerHTML = html;
    } catch (e) {
        console.error('Error al cargar programas:', e);
        contenedor.innerHTML = '<p class="mensaje error">Ocurrió un error al cargar los programas desde el servidor.</p>';
    }
});
