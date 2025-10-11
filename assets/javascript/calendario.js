
document.addEventListener('DOMContentLoaded', function() {
    // Generar calendario
    generarCalendario();
    
    // Event listeners
    const barberoSelect = document.getElementById('barberoSelect');
    const fechaInput = document.getElementById('fechaInput');
    const horaSelect = document.getElementById('horaSelect');
    
    // Cuando cambia barbero o fecha, actualizar horas disponibles
    if (barberoSelect && fechaInput) {
        barberoSelect.addEventListener('change', actualizarHoras);
        fechaInput.addEventListener('change', actualizarHoras);
    }
});

function generarCalendario() {
    const calendarBody = document.getElementById('calendarBody');
    if (!calendarBody) return;
    
    const fecha = new Date(2025, 9, 1); // Octubre 2025
    const primerDia = new Date(fecha.getFullYear(), fecha.getMonth(), 1).getDay();
    const ultimoDia = new Date(fecha.getFullYear(), fecha.getMonth() + 1, 0).getDate();
    
    // Días vacíos al inicio
    for (let i = 0; i < primerDia; i++) {
        const emptyDiv = document.createElement('div');
        emptyDiv.className = 'calendar-day empty';
        calendarBody.appendChild(emptyDiv);
    }
    
    // Días del mes
    for (let dia = 1; dia <= ultimoDia; dia++) {
        const dayDiv = document.createElement('div');
        dayDiv.className = 'calendar-day';
        dayDiv.textContent = dia;
        
        if (dia === 15) {
            dayDiv.classList.add('selected');
        }
        
        dayDiv.addEventListener('click', function() {
            document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('selected'));
            this.classList.add('selected');
            
            // Actualizar input de fecha
            const fechaInput = document.getElementById('fechaInput');
            if (fechaInput) {
                const mes = String(fecha.getMonth() + 1).padStart(2, '0');
                const diaStr = String(dia).padStart(2, '0');
                fechaInput.value = `${fecha.getFullYear()}-${mes}-${diaStr}`;
                actualizarHoras();
            }
        });
        
        calendarBody.appendChild(dayDiv);
    }
}

async function actualizarHoras() {
    const barberoSelect = document.getElementById('barberoSelect');
    const fechaInput = document.getElementById('fechaInput');
    const horaSelect = document.getElementById('horaSelect');
    
    if (!barberoSelect.value || !fechaInput.value) {
        horaSelect.innerHTML = '<option value="">Select time</option>';
        return;
    }
    
    try {
        const response = await fetch(
            `index.php?action=obtenerHorasDisponibles&barbero_id=${barberoSelect.value}&fecha=${fechaInput.value}`
        );
        const data = await response.json();
        
        horaSelect.innerHTML = '<option value="">Select time</option>';
        
        if (data.success && data.horas.length > 0) {
            data.horas.forEach(hora => {
                const option = document.createElement('option');
                option.value = hora;
                option.textContent = hora;
                horaSelect.appendChild(option);
            });
        } else {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'No hay horarios disponibles';
            option.disabled = true;
            horaSelect.appendChild(option);
        }
    } catch (error) {
        console.error('Error al obtener horas:', error);
    }
}