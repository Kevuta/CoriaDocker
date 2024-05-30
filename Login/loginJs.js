document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const switchForm = document.getElementById('switch-form');
    const cardTitle = document.querySelector('.card-title');

    switchForm.addEventListener('click', function () {
        loginForm.style.display = loginForm.style.display === 'none' ? 'block' : 'none';
        registerForm.style.display = registerForm.style.display === 'none' ? 'block' : 'none';

        // Cambiar el texto del enlace alternativamente
        switchForm.innerHTML = (loginForm.style.display === 'none') ? '¿Ya tienes cuenta? Inicia Sesión aquí.' : '¿No tienes cuenta? Regístrate aquí.';

        // Ocultar el título "Iniciar Sesión" cuando se cambia al formulario de registro
        cardTitle.style.display = (loginForm.style.display === 'none') ? 'none' : 'block';
    });
});
