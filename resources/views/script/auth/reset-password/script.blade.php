<script>
    function checkPasswordMatch() {
        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('password_confirmation').value;
        var matchMessage = document.getElementById('password-match');

        if (password !== confirmPassword) {
            matchMessage.innerHTML = '<p class="text-danger">As senhas não coincidem.</p>';
        } else {
            matchMessage.innerHTML = ' ';
        }
    }
</script>
