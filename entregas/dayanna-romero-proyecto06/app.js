// app.js

// Función para cambiar de página en el menú lateral
function showPage(pageId, event) {
    const pages = document.querySelectorAll('.page');
    pages.forEach(page => page.classList.remove('active'));

    const selectedPage = document.getElementById('page-' + pageId);
    if (selectedPage) selectedPage.classList.add('active');

    const links = document.querySelectorAll('.sidebar-link');
    links.forEach(link => link.classList.remove('active'));

    if (event && event.currentTarget) event.currentTarget.classList.add('active');
}

document.addEventListener("DOMContentLoaded", function() {
    const todosLosEspacios = document.querySelectorAll('.space');
    const inputEspacio = document.getElementById('input-espacio');
    
    // Elementos de la tarjeta negra
    const detalleTitulo = document.getElementById('detalle-titulo');
    const detalleInfo = document.getElementById('detalle-info');

    // 1. PINTAR OCUPADOS Y CARGAR DETALLES
    // Usamos 'detallesOcupadosDB' que viene de index.php
    if (typeof detallesOcupadosDB !== 'undefined') {
        todosLosEspacios.forEach(espacio => {
            const nombreEspacio = espacio.innerText.trim(); // Aseguramos limpieza de espacios
            
            if (detallesOcupadosDB[nombreEspacio]) {
                espacio.classList.remove('libre');
                espacio.classList.add('ocupado'); 
            } else {
                espacio.classList.add('libre'); // Aseguramos la clase libre
            }
        });
    }

    // 2. ACTUALIZAR CONTADORES AUTOMÁTICAMENTE
    function actualizarContadores() {
        const cantOcupados = document.querySelectorAll('.space.ocupado').length;
        const totalEspacios = todosLosEspacios.length;
        
        const spanOcupados = document.getElementById('ocupados');
        const spanLibres = document.getElementById('libres');
        
        if(spanOcupados) spanOcupados.innerText = cantOcupados;
        if(spanLibres) spanLibres.innerText = totalEspacios - cantOcupados;
    }
    
    // Ejecutamos al iniciar
    actualizarContadores();

    // 3. LÓGICA DE CLICS
    todosLosEspacios.forEach(espacio => {
        espacio.addEventListener('click', function() {
            const nombreEspacio = this.innerText.trim();

            // CASO OCUPADO: Mostrar detalles en tarjeta negra
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
            // CASO LIBRE: Asignar al formulario
            else if (this.classList.contains('libre')) {
                // Reset de selección visual
                document.querySelectorAll('.space.libre').forEach(e => {
                    e.style.backgroundColor = ''; // Vuelve al color original de CSS
                    e.style.color = '';
                });
                
                this.style.backgroundColor = '#6d28d9'; 
                this.style.color = 'white';
                
                if(inputEspacio) {
                    inputEspacio.value = nombreEspacio; 
                    // Scroll suave hacia el formulario
                    const formEntrada = document.getElementById('form-entrada');
                    if(formEntrada) formEntrada.scrollIntoView({ behavior: 'smooth' });
                }
                
                detalleTitulo.innerText = `Espacio — ${nombreEspacio}`;
                detalleInfo.innerText = "Este espacio está disponible para asignar.";
            }
        });
    });
});