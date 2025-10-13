// Toggle para mostrar/ocultar campos de contraseña
function togglePasswordFields() {
    const fields = document.getElementById('passwordFields');
    const btn = document.querySelector('.btn-toggle-password');
    
    if (fields.style.display === 'none') {
        fields.style.display = 'flex';
        btn.textContent = 'Cancelar cambio de contraseña';
    } else {
        fields.style.display = 'none';
        btn.textContent = 'Cambiar contraseña';
        // Limpiar campos
        document.getElementById('password_actual').value = '';
        document.getElementById('password_nueva').value = '';
    }
}

// Cancelar cita
function cancelarCita(id) {
    if (confirm('¿Estás seguro de que deseas cancelar esta cita?')) {
        window.location.href = `index.php?action=cancelarReserva&id=${id}&redirect=perfil`;
    }
}

// Reprogramar cita
function reprogramarCita(id) {
    window.location.href = `index.php?action=reprogramarCita&id=${id}`;
}

// Validación del formulario
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.perfil-form');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            const passwordNueva = document.getElementById('password_nueva').value;
            const passwordActual = document.getElementById('password_actual').value;
            
            // Si intenta cambiar contraseña, validar que ingresó la actual
            if (passwordNueva && !passwordActual) {
                e.preventDefault();
                alert('Debes ingresar tu contraseña actual para cambiarla');
                document.getElementById('password_actual').focus();
            }
        });
    }
});