var idIdentif;

$(document).ready(function () {
    $('#loading').removeClass('d-none');
    var url = route('identifier.data');

    axios.get(url).then(function (response) {
        var data = response.data;

        if (data.error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.errorMessage,
            });
        } else {
            var identifiers = JSON.parse(data.identifiers);
            loadIdentifierTable(identifiers);

            $('#loading').addClass('d-none');
        }
    }).catch(function (error) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Ocorreu um erro inesperado'
        });
        console.log(error);
    });
});

jscolor.presets.default = {
    format: 'hex'
};

$('#ModalCreate').on('hidden.bs.modal', function () {
    cleanFields();
});

// envia o formulario de criacao
$('#formCrt').on('submit', function (e) {
    e.preventDefault();
    var url = route('identifier.create');

    var data = {
        identif: $('#identif').val(),
        descricao: $('#descricao').val(),
        ativo: $('#ativo').val(),
        id_hex: $('#id_hex').val(),
    };

    axios.post(url, data).then(function (response) {
        var data = response.data;

        if (data.error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.errorMessage
            });
        } else {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: data.message,
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                reloadTable();
                $('#ModalCreate').modal('hide');
            });
        }
    }).catch(function (error) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Ocorreu um erro inesperado'
        });
        console.log(error);
    });
});

// envia o formulario de edicao
$('#formEdt').on('submit', function (e) {
    e.preventDefault();
    var url = route('identifier.update', { 'id': idIdentif });

    var data = {
        identifEdt: $('#identifEdt').val(),
        descricaoEdt: $('#descricaoEdt').val(),
        ativoEdt: $('#ativoEdt').val(),
        id_hexEdt: $('#id_hexEdt').val(),
    };

    axios.put(url, data).then(function (response) {
        var data = response.data;

        if (data.error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.errorMessage
            });
        } else {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: data.message,
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                reloadTable();
                $('#ModalEdit').modal('hide');
            });
        }
    }).catch(function (error) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Ocorreu um erro inesperado'
        });
        console.log(error);
    });
});

//pega os dados da edicao e passa para a model
$('#btnEdit').on('click', function (e) {
    e.preventDefault();
    var url = route('identifier.edit', { 'id': idIdentif });

    axios.get(url).then(function (response) {
        var data = response.data;

        if (data.error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.errorMessage,
            });
        } else {
            $('#identifEdt option').each(function () {
                if ($(this).data('tipo') === data.IDENTIF) {
                    $(this).prop('selected', true);
                }
            });

            $('#descricaoEdt').val(data.DESCRICAO);

            $('#id_hexEdt').val(data.ID_HEX);
            $('#id_hexEdt')[0].jscolor.fromString('#' + data.ID_HEX);
            $('#ativoEdt option').each(function () {
                if ($(this).data('ativo') === data.ATIVO) {
                    $(this).prop('selected', true);
                }
            });
            $('#ModalEdit').modal('show');
        }
    }).catch(function (error) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Ocorreu um erro inesperado'
        });
        console.log(error);
    });
});

// abre caixa de confirmação para exclusao
$('#btnDelete').on('click', function (e) {
    e.preventDefault();
    Swal.fire({
        title: 'Você tem certeza?',
        text: 'Não será possível reverter essa ação!',
        icon: 'warning',
        cancelButtonText: 'Cancelar',
        showCancelButton: true,
        preConfirm: () => {
            Delete();
        }
    });
});

// exclui o item selecionado
function Delete() {
    var url = route('identifier.destroy', { 'id': idIdentif });

    axios.delete(url).then(function (response) {
        data = response.data;

        if (data.error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.errorMessage
            });
        } else {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: data.message,
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                reloadTable();
            });
        }
    }).catch(function (error) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Ocorreu um erro inesperado'
        });
        console.log(error);
    });
}

// recarrega a tabela após alterações
function reloadTable() {
    var url = route('identifier.data');

    axios.get(url).then(function (response) {
        var data = response.data;

        if (data.error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.errorMessage
            });
        } else {
            var identifiers = JSON.parse(data.identifiers);

            destroyIdentifiers();
            loadIdentifierTable(identifiers);
        }
    }).catch(function (error) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Ocorreu um erro inesperado'
        });
        console.log(error);
    });
}

function cleanFields() {
    $('#identif').val('').change();
    $('#descricao').val('');
    $('#ativo').val('Sim').change();
    $('#id_hex').val('#FFFFFF');
    $('#id_hex')[0].jscolor.fromString('#FFFFFF');
}
