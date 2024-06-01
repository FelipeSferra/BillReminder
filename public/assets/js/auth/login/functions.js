document.addEventListener('DOMContentLoaded', function () {
    var navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(function (link) {
        link.addEventListener('click', function () {
            navLinks.forEach(function (otherLink) {
                otherLink.classList.remove('active');
            });

            link.classList.add('active');
        });
    });
});

function checkPasswordMatch() {
    var password = document.getElementById('password-signup').value;
    var confirmPassword = document.getElementById('password_confirmation').value;
    var matchMessage = document.getElementById('password-match');

    if (password !== confirmPassword) {
        matchMessage.innerHTML = '<p class="text-danger">As senhas não coincidem.</p>';
    } else {
        matchMessage.innerHTML = ' ';
    }
}
