
const passwordInput = document.getElementById('password');
const togglePassword = document.getElementById('togglePassword');

if (passwordInput && togglePassword) {
    togglePassword.addEventListener('click', () => {
        const isPassword = passwordInput.type === 'password';

        passwordInput.type = isPassword ? 'text' : 'password';
        togglePassword.setAttribute(
            'aria-label',
            isPassword ? 'Hide password' : 'Show password'
        );
    });
}
