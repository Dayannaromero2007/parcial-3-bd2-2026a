// app.js

let pestanaOrigen = 'dashboard'; // Memoria para saber a dónde volver

// Función para ir al mapa recordando de dónde venimos
function irAlMapa(origen) {
    pestanaOrigen = origen;
    showPage('espacios'); 
}

// Función para cambiar de página
function showPage(pageId, event) {
    const pages = document.querySelectorAll('.page');
    pages.forEach(page => page.classList.remove('active'));

    const selectedPage = document.getElementById('page-' + pageId);
    if (selectedPage) selectedPage.classList.add('active');

    const links = document.querySelectorAll('.sidebar-link');
    links.forEach(link => link.classList.remove('active'));

    if (event && event.currentTarget) {
        event.currentTarget.classList.add('active');
    } else {
        // Pinta morado el botón del menú izquierdo automáticamente
        const btnMenu = document.querySelector(`.sidebar-link[onclick*="'${pageId}'"]`);
        if(btnMenu) btnMenu.classList.add('active');
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const todosLosEspacios = document.querySelectorAll('.space');
    const inputEspacio = document.getElementById('input-espacio');
    const inputMensual = document.getElementById('input-espacio-mensual');
    
    // Elementos de la tarjeta negra
    const detalleTitulo = document.getElementById('detalle-titulo');
    const detalleInfo = document.getElementById('detalle-info');

    // 1. PINTAR OCUPADOS Y CARGAR DETALLES
    if (typeof detallesOcupadosDB !== 'undefined') {
        todosLosEspacios.forEach(espacio => {
            const nombreEspacio = espacio.innerText.trim(); 
            
            if (detallesOcupadosDB[nombreEspacio]) {
                espacio.classList.remove('libre');
                espacio.classList.add('ocupado'); 
            } else {
                espacio.classList.add('libre'); 
            }
        });
    }

    // 2. ACTUALIZAR CONTADORES
    function actualizarContadores() {
        const cantOcupados = document.querySelectorAll('.space.ocupado').length;
        const totalEspacios = todosLosEspacios.length;
        
        const spanOcupados = document.getElementById('ocupados');
        const spanLibres = document.getElementById('libres');
        
        if(spanOcupados) spanOcupados.innerText = cantOcupados;
        if(spanLibres) spanLibres.innerText = totalEspacios - cantOcupados;
    }
    
    actualizarContadores();

    // 3. LÓGICA DE CLICS EN EL MAPA
    todosLosEspacios.forEach(espacio => {
        espacio.addEventListener('click', function() {
            const nombreEspacio = this.innerText.trim();

            // CASO OCUPADO
            if (this.classList.contains('ocupado')) {
                const info = detallesOcupadosDB[nombreEspacio];
                detalleTitulo.innerText = `Espacio — ${nombreEspacio}`;
                detalleInfo.innerHTML = `
                    <strong>Placa:</strong> ${info.placa} <br>
                    <strong>Tipo:</strong> ${info.nombre_tipo} <br>
                    <strong>Marca:</strong> ${info.marca} <br>
                    <strong>Color:</strong> ${info.color}
                `;
            } 
            // CASO LIBRE
            else if (this.classList.contains('libre')) {
                // Limpiar colores anteriores
                document.querySelectorAll('.space.libre').forEach(e => {
                    e.style.backgroundColor = ''; 
                    e.style.color = '';
                });
                
                // Pintar de morado el que acabas de elegir
                this.style.backgroundColor = '#6d28d9'; 
                this.style.color = 'white';
                
                // Llenar las dos cajas (Panel y Mensuales)
                if(inputEspacio) inputEspacio.value = nombreEspacio; 
                if(inputMensual) inputMensual.value = nombreEspacio; 
                
                detalleTitulo.innerText = `Espacio — ${nombreEspacio}`;
                detalleInfo.innerText = "Este espacio ha sido seleccionado.";

                // MAGIA: Devolver al usuario a la pestaña correcta después de medio segundo
                setTimeout(() => {
                    showPage(pestanaOrigen);
                    
                    // Hacer scroll suave hacia el formulario correcto
                    const formId = (pestanaOrigen === 'mensuales') ? 'form-mensual' : 'form-entrada';
                    const formDestino = document.getElementById(formId);
                    if(formDestino) formDestino.scrollIntoView({ behavior: 'smooth' });
                    
                }, 400); // 400 milisegundos de pausa visual
            }
        });
    });
});