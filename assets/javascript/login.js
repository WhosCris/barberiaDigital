document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');

    // Limpiar mensajes de error al escribir
    emailInput.addEventListener('input', function() {
        clearError(this);
    });

    passwordInput.addEventListener('input', function() {
        clearError(this);
    });

    // Validación antes de enviar
    form.addEventListener('submit', function(e) {
        let valid = true;

        // Validar email
        if (!emailInput.value.trim()) {
            showError(emailInput, 'El email es obligatorio');
            valid = false;
        } else if (!isValidEmail(emailInput.value)) {
            showError(emailInput, 'Email no válido');
            valid = false;
        }

        // Validar password
        if (!passwordInput.value) {
            showError(passwordInput, 'La contraseña es obligatoria');
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
        input.style.borderColor = '#c33';
    }

    function clearError(input) {
        const formGroup = input.parentElement;
        const errorDiv = formGroup.querySelector('.error-text');
        
        if (errorDiv) {
            errorDiv.remove();
        }
        input.style.borderColor = '';
    }

    function isValidEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
});