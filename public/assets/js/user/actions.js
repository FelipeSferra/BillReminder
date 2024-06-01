// inicializacao das variaveis gerais e botoes
var userInfo;
$(document).ready(function () {
    $('#loading').removeClass('d-none');
    var url = route('user.data');

    axios.get(url).then(function (response) {
        $('#loading').addClass('d-none');
        var data = response.data;

        if (data.error) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: data.errorMessage
            });
        } else {
            userInfo = JSON.parse(data.userInfo);

            $('#name').val(userInfo.name);
            $('#email').val(userInfo.email);
            $('#emailSec').val(userInfo.EMAIL_SECUNDARIO);

            if (userInfo.NOTIFICAR_GASTO === 'S') {
                $('#notifGasto').bootstrapToggle('on');
                $('#notifGasto').prop('checked', true).change();
                $('#emailGastos option').each(function () {
                    if ($(this).data('tipo') === userInfo.TIPO_NOTIF_GASTO) {
                        $(this).prop('selected', true);
                    }
                });
            }

            if (userInfo.NOTIFICAR_VENC === 'S') {
                $('#notifVenc').bootstrapToggle('on');
                $('#notifVenc').prop('checked', true).change();
                $('#emailVenc').val(userInfo.VENC_DIAS);
            }
        }
    }).catch(function (error) {
        console.log(error);
    });
    $('#notifGasto').bootstrapToggle({
        onlabel: 'Sim',
        offlabel: 'Não',
        size: 'sm',
        onstyle: 'success',
        offstyle: 'danger'
    });

    $('#notifVenc').bootstrapToggle({
        onlabel: 'Sim',
        offlabel: 'Não',
        size: 'sm',
        onstyle: 'success',
        offstyle: 'danger'
    });
});
// fim da inicializacao das variaveis gerais e botoes

// verificacao dos botoes de notificacao
$('#notifGasto').on('change', function () {
    if (this.checked) {
        $('#divGastos').css("display", "inline-block");

    } else {
        $('#divGastos').css("display", "none");
    }

    $('#saveNotify').attr('disabled', false);
});

$('#notifVenc').on('change', function () {
    if (this.checked) {
        $('#divVenc').css("display", "inline-block");

    } else {
        $('#divVenc').css("display", "none");
    }

    $('#saveNotify').attr('disabled', false);
});
// fim da verificacao dos botoes de notificacao

// envio do formulario de informacoes usuario
$('#formUser').on('submit', function (e) {
    e.preventDefault();
    var data = {
        name: $('#name').val(),
        email: $('#email').val(),
        emailSec: $('#emailSec').val(),
    };

    var url = route('user.change-info', {
        'id': userInfo.id
    });

    axios.put(url, data).then(function (response) {
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
                window.location.reload();
            });
        }
    }).catch(function (error) {
        console.log(error);
    });
})
// fim do envio do formulario de informacoes usuario

// envio do formulario de alteracao de senha
$('#formPass').on('submit', function (e) {
    e.preventDefault();
    var data = {
        password: $('#password').val(),
        new_password: $('#new_password').val(),
        new_password_confirmation: $('#new_password_confirmation').val(),
    };

    var url = route('user.change-pass', {
        'id': userInfo.id
    });

    axios.post(url, data).then(function (response) {
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
                window.location.reload();
            });
        }
    }).catch(function (error) {
        if (error.response && error.response.data && error.response.data.errors) {
            var errors = error.response.data.errors;
            var errorMessages = [];
            for (var key in errors) {
                if (errors.hasOwnProperty(key)) {
                    errors[key].forEach(message => {
                        errorMessages.push(message);
                    });
                }
            }
            Swal.fire({
                icon: "error",
                title: "Oops...",
                html: errorMessages.join('<br>')
            });
        } else {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ocorreu um erro inesperado."
            });
        }
    });
})
// fim do envio do formulario de alteracao de senha

// envio do formulario de ativacao da notificacao
$('#formNotif').on('submit', function (e) {
    e.preventDefault();

    if ($('#notifGasto').prop('checked')) {
        var notifGasto = $('#notifGasto').val();
    } else {
        var notifGasto = 'N';
    }

    if ($('#notifVenc').prop('checked')) {
        var notifVenc = $('#notifVenc').val();
    } else {
        var notifVenc = 'N';
    }

    var data = {
        notifGasto: notifGasto,
        emailGastos: $('#emailGastos').val(),
        notifVenc: notifVenc,
        emailVenc: $('#emailVenc').val(),
    };

    var url = route('user.notify', {
        'id': userInfo.id
    });

    axios.put(url, data).then(function (response) {
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
                window.location.reload();
            });
        }
    }).catch(function (error) {
        console.log(error);
    });
})
// fim do envio do formulario de ativacao da notificacao
