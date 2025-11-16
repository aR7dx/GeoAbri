const form = document.getElementById('form');

form.addEventListener('submit', function (e) {
    e.preventDefault();
    let form = e.target;
    let valid = true;

    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

    form.querySelectorAll('input').forEach(input => {
        if (!input.checkValidity()) {
            input.classList.add('is-invalid');
            valid = false;
        }
    });

    const password = form.querySelector('#password');
    const confirm = form.querySelector('#confirmPassword');
    if (password.value !== confirm.value) {
        confirm.classList.add('is-invalid');
        valid = false;
    }

    if (valid) {
        form.submit();
    }
});