document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registroForm');
    const nombreInput = document.getElementById('nombre');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const fechaInput = document.getElementById('fecha_nacimiento');

    // Limpiar errores al escribir
    [nombreInput, emailInput, passwordInput, fechaInput].forEach(input => {
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
        if (!nombreInput.value.trim()) {
            showError(nombreInput, 'El nombre es obligatorio');
            valid = false;
        } else if (nombreInput.value.trim().length < 2) {
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

        // Validar password
        if (!passwordInput.value) {
            showError(passwordInput, 'La contraseña es obligatoria');
            valid = false;
        } else if (passwordInput.value.length < 6) {
            showError(passwordInput, 'La contraseña debe tener al menos 6 caracteres');
            valid = false;
        }

        // Validar fecha de nacimiento
        if (!fechaInput.value) {
            showError(fechaInput, 'La fecha de nacimiento es obligatoria');
            valid = false;
        } else {
            const edad = calcularEdad(fechaInput.value);
            if (edad < 13) {
                showError(fechaInput, 'Debes tener al menos 13 años');
                valid = false;
            }
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

    function calcularEdad(fechaNacimiento) {
        const hoy = new Date();
        const nacimiento = new Date(fechaNacimiento);
        let edad = hoy.getFullYear() - nacimiento.getFullYear();
        const mes = hoy.getMonth() - nacimiento.getMonth();
        
        if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
            edad--;
        }
        
        return edad;
    }
});