document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registroAdminForm');
    const nombreInput = document.getElementById('nombre');
    const emailInput = document.getElementById('email');
    const telefonoInput = document.getElementById('telefono');
    const passwordInput = document.getElementById('password');

    // Limpiar errores
    [nombreInput, emailInput, telefonoInput, passwordInput].forEach(input => {
        if (input) {
            input.addEventListener('input', function() {
                clearError(this);
            });
        }
    });

    // Validación antes de enviar
    form.addEventListener('submit', function(e) {
        let valid = true;

        // Validar nombre
        if (!nombreInput.value.trim() || nombreInput.value.trim().length < 2) {
            showError(nombreInput, 'El nombre debe tener al menos 2 caracteres');
            valid = false;
        }

        // Validar email
        if (!emailInput.value.trim()) {
            showError(emailInput, 'El email es obligatorio');
            valid = false;
        } else if (!isValidEmail(emailInput.value)) {
            showError(emailInput, 'Email no válido');
            valid = false;
        }

        // Validar teléfono
        if (!telefonoInput.value.trim()) {
            showError(telefonoInput, 'El teléfono es obligatorio');
            valid = false;
        }

        // Validar contraseña
        if (!passwordInput.value) {
            showError(passwordInput, 'La contraseña es obligatoria');
            valid = false;
        } else if (passwordInput.value.length < 6) {
            showError(passwordInput, 'La contraseña debe tener al menos 6 caracteres');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
        }
    });

    function showError(input, message) {
        const formGroup = input.parentElement;
        let errorDiv = formGroup.querySelector('.error-text');
        
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'error-text';
            formGroup.appendChild(errorDiv);
        }
        
        errorDiv.textContent = message;
        input.style.borderColor = '#dc2626';
    }

    function clearError(input) {
        const formGroup = input.parentElement;
        const errorDiv = formGroup.querySelector('.error-text');
        
        if (errorDiv) {
            errorDiv.remove();
        }
        input.style.borderColor = '#e5e7eb';
    }

    function isValidEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
});