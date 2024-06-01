var idDebt;
var debt;

$(document).ready(function () {
    $('#loading').removeClass('d-none');
    var url = route('debt.data');

    axios.get(url).then(function (response) {
        var data = response.data;

        if (data.error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.errorMessage
            });
        } else {
            debt = JSON.parse(data.debt);

            debt.forEach(function (email) {
                if (email.EMAIL === null) {
                    email.EMAIL = 'Não cadastrado';
                }
            });

            loadDebtTable(debt);

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

$('#ModalCreate').on('hidden.bs.modal', function () {
    cleanFields();
});

//envia o formulario de criação
$('#formCrt').on('submit', function (e) {
    e.preventDefault();

    var url = route('debt.create');

    var data = {
        nome: $('#nome').val(),
        email: $('#email').val(),
        ativo: $('#ativo').val(),
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

//envia o formulario de edição
$('#formEdt').on('submit', function (e) {
    e.preventDefault();
    var url = route('debt.update', { 'id': idDebt });

    var data = {
        nomeEdt: $('#nomeEdt').val(),
        emailEdt: $('#emailEdt').val(),
        ativoEdt: $('#ativoEdt').val(),
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
    var url = route('debt.edit', { 'id': idDebt });

    axios.get(url).then(function (response) {
        var data = response.data;

        if (data.error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.errorMessage
            });
        } else {
            $('#nomeEdt').val(data.NOME);
            $('#emailEdt').val(data.EMAIL);
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


function Delete() {
    var url = route('debt.destroy', { 'id': idDebt });

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
    var url = route('debt.data');

    axios.get(url).then(function (response) {
        var data = response.data;

        if (data.error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.errorMessage
            });
        } else {
            debt = JSON.parse(data.debt);

            debt.forEach(function (email) {
                if (email.EMAIL === null) {
                    email.EMAIL = 'Não cadastrado';
                }
            });

            $('#btnEdit').prop('disabled', true);
            $('#btnDelete').prop('disabled', true);

            destroyDebt();
            loadDebtTable(debt);
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
    $('#nome').val('');
    $('#email').val('');
    $('#ativo').val('Sim').change();
}
