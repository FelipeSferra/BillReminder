<script>
    $('#formUser').on('submit', function(e) {
        e.preventDefault();
        var data = {
            name: $('#name').val(),
            email: $('#email').val(),
            emailSec: $('#emailSec').val(),
        };

        console.log(email)
        var url = "{{ secure_url(route('user.change-info', ['id' => 0])) }}";
        url = url.replace(0, userInfo.id);

        axios.put(url, data).then(function(response) {
            let data = response.data;
            console.log(data)
            if (data.error) {
                @if (session()->has('errors'))
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: {{ $errors }}
                    });
                @endif
            } else {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: data.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.reload();
                });
            }
        }).catch(function(error) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: error
            });
        });
    })
</script>
