<script>
    @if (session()->has('success'))
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 1500
        });
    @endif
    @if (session()->has('errors'))
        @foreach ($errors->all() as $error)
            var data = '{{ $error }}';
        @endforeach

        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: data
        });
    @endif
</script>
