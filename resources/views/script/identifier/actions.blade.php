<script>
    let idIdentif;

    $(document).ready(function() {
        loadIdentifierTable(@json($identifiers))
    })

    //pega os dados da edicao e passa para a model
    $('#btnEdit').on('click', function(e) {
        e.preventDefault();
        let url = "{{ secure_url(route('identifier.edit', ['id' => 0])) }}";
        url = url.replace(0, idIdentif);

        axios.get(url)
            .then(function(response) {
                let identifier = response.data;

                if (identifier.notFound) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'O identificador não foi encontrado, tente novamente.',
                    });
                } else {
                    $('#identifEdt option').each(function() {
                        if ($(this).data('tipo') === identifier.IDENTIF) {
                            $(this).prop('selected', true);
                        }
                    });

                    $('#descricaoEdt').val(identifier.DESCRICAO);

                    $('#id_hexEdt').val(identifier.ID_HEX);
                    $('#ativoEdt option').each(function() {
                        if ($(this).data('ativo') === identifier.ATIVO) {
                            $(this).prop('selected', true);
                        }
                    });
                    $('#ModalEdit').modal('show');
                }
            })
            .catch(function(error) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: error
                });
            });
    });

    // envia o formulario de criacao
    $('#formCrt').on('submit', function(e) {
        e.preventDefault();

        var url = "{{ secure_url(route('identifier.create')) }}";
        var data = {
            identif: $('#identif').val(),
            descricao: $('#descricao').val(),
            ativo: $('#ativo').val(),
            id_hex: $('#id_hex').val(),
        };

        axios.post(url, data).then(function(response) {
            var data = response.data;

            if (data.error) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: data.errorMessage
                });
            } else {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: data.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    reloadTable();
                    $("#ModalCreate").modal('hide');
                });
            }
        }).catch(function(error) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: error
            });
        });
    });

    // envia o formulario de edicao
    $('#formEdt').on('submit', function(e) {
        e.preventDefault();

        var url = "{{ secure_url(route('identifier.update', ['id' => 0])) }}";
        url = url.replace(0, idIdentif);
        var data = {
            identifEdt: $('#identifEdt').val(),
            descricaoEdt: $('#descricaoEdt').val(),
            ativoEdt: $('#ativoEdt').val(),
            id_hexEdt: $('#id_hexEdt').val(),
        };

        axios.put(url, data).then(function(response) {
            var data = response.data;

            if (data.error) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: data.errorMessage
                });
            } else {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: data.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    reloadTable();
                    $("#ModalEdit").modal('hide');
                });
            }
        }).catch(function(error) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: error
            });
        });
    });

    $('#btnDelete').on('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Você tem certeza?',
            text: "Não será possível reverter essa ação!",
            icon: 'warning',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            preConfirm: () => {
                Delete();
            }
        });
    });

    function Delete() {
        let url = "{{ secure_url(route('identifier.destroy', ['id' => 0])) }}";
        url = url.replace(0, idIdentif);

        axios.delete(url).then(function(response) {
            data = response.data;

            if (data.exists) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: 'Não é possível excluir o item, pois ele está sendo utilizado.Neste caso você deve desabilitar o item.'
                });
            } else {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: data.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    reloadTable();
                });
            }
        }).catch(function(error) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: error
            });
        });
    }

    function reloadTable() {
        var url = "{{ secure_url(route('identifier.reload')) }}";

        axios.get(url).then(function(response) {
            var data = JSON.parse(response.data);

            $('#identif').val('').change();
            $('#descricao').val('');
            $('#ativo').val('Sim').change();
            $('#id_hex').val('#FFFFFF');

            destroyIdentifiers();
            loadIdentifierTable(data);
        }).catch(function(error) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: error
            });
        });
    }
</script>
