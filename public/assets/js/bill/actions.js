var idBill;
var idColors = {};
var identifiers;
var idColors;
var bills;
var tipo = $('#tipoVisu').data('checked');

$(document).ready(function () {
    $('#loading').removeClass('d-none');
    var url = route('bill.data');

    axios.get(url).then(function (response) {
        var data = response.data;
        if (data.error) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: data.errorMessage
            });
        } else {
            bills = JSON.parse(data.bills);
            identifiers = JSON.parse(data.identifiers);
            const billsPerType = JSON.parse(data.billsPerType);

            bills.forEach(function (color) {
                idColors[color.TIPO_CONTA] = color.ID_HEX;
            });

            loadCompleteTable(bills, idColors);
            loadSimpleTable(billsPerType, idColors);
            $('#loading').addClass('d-none');
        }

    }).catch(function (error) {
        console.log(error);
    });
});

$('#ModalCreate').on('hidden.bs.modal', function () {
    cleanFields();
});

$('#tipoVisu').bootstrapToggle({
    onlabel: 'Completo',
    offlabel: 'Simplificado',
    onstyle: 'success',
    offstyle: 'secondary'
});

jscolor.presets.default = {
    format: 'hex'
};

$('#formCrt').on('shown.bs.modal', function () {
    loadOptions(identifiers, 'Crt');
});

$('#formEdt').on('show.bs.modal', function () {
    loadOptions(identifiers, 'Edt');
})

//envia o formulario de criação
$('#formCrt').on('submit', function (e) {
    e.preventDefault();

    var url = route('bill.create');
    var data = {
        tipo_conta: $('#tipo_conta').val(),
        descricao: $('#descricao').val(),
        valor: $('#valor').val(),
        parcelas: $('#parcelas').val(),
        vencimento: $('#vencimento').val(),
        recriar: $('#recriar').val(),
        status: $('#status').val(),
    };

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
                cleanFields();
                changeFilter('Completo');
                $("#ModalCreate").modal('hide');
            });
        }
    }).catch(function (error) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: error
        });
    });
});

//envia o formulario de edição
$('#formEdt').on('submit', function (e) {
    e.preventDefault();

    var url = route('bill.update', { 'id': idBill });

    var data = {
        tipo_contaEdt: $('#tipo_contaEdt').val(),
        descricaoEdt: $('#descricaoEdt').val(),
        valorEdt: $('#valorEdt').val(),
        parcelasEdt: $('#parcelasEdt').val(),
        vencimentoEdt: $('#vencimentoEdt').val(),
        recriarEdt: $('#recriarEdt').val(),
        statusEdt: $('#statusEdt').val(),
    };

    if (data.tipo_contaEdt !== '') {
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
                    changeFilter('Completo');
                    $("#ModalEdit").modal('hide');
                });
            }
        }).catch(function (error) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: error
            });
        });
    } else {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            html: 'Não é possível enviar um tipo de conta vazio ou desativado!<br>Altere o tipo de conta para uma opção válida.',
        });
    }
});

//pega os dados da edicao e passa para a model
$('#btnEdit').on('click', function (e) {
    e.preventDefault();

    var url = route('bill.edit', { 'id': idBill });

    axios.get(url).then(function (response) {
        var data = response.data;

        if (data.error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.message,
            });
        } else {
            $('#tipo_contaEdt option').each(function () {
                if ($(this).data('tipo') === data.TIPO_CONTA) {
                    $(this).prop('selected', true);
                }
            });

            $('#descricaoEdt').val(data.DESCRICAO);

            $('#valorEdt').val(data.VALOR);

            $('#parcelasEdt').val(data.PARCELAS);

            $('#vencimentoEdt').val(data.VENCIMENTO);

            $('#recriarEdt option').each(function () {
                if ($(this).data('rec') === data.RECRIAR)
                    $(this).prop('selected', true);
            });

            $('#statusEdt').val(data.STATUS);

            $('#ModalEdit').modal('show');
        }
    }).catch(function (error) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: error
        });
    });
});

$('#btnDelete').on('click', function (e) {
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

$('#btnConcluded').on('click', function (e) {
    e.preventDefault();
    Swal.fire({
        title: 'Você tem certeza?',
        text: "Ao fazer isso será alterado o status da conta.",
        icon: 'warning',
        cancelButtonText: 'Cancelar',
        showCancelButton: true,
        preConfirm: () => {
            Concluded();
        }
    });
});

function Concluded() {
    var url = route('bill.concluded', { 'id': idBill });

    axios.post(url).then(function (response) {
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
                changeFilter('Completo');
            });
        }
    }).catch(function (error) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: error
        });
    });
}

function Delete() {
    var url = route('bill.destroy', { 'id': idBill });

    axios.delete(url).then(function (response) {
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
                changeFilter('Completo');
            });
        }
    }).catch(function (error) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: error
        });
    });
}

$('#tipoVisu').change(function () {
    if (this.checked) {
        tipo = $(this).data('checked');
        $('#complete').removeClass('d-none');
        $('#btnNew').attr('disabled', false);
        $('#simple').addClass('d-none');
        changeFilter(tipo);
    } else {
        tipo = $(this).data('unchecked');
        $('#complete').addClass('d-none');
        $('#btnNew').attr('disabled', true);
        $('#simple').removeClass('d-none');
        $('#table-bills-simple').DataTable().columns.adjust().draw();
        changeFilter(tipo);
    }
});

$('#filtroStatus').on('change', function (e) {
    e.preventDefault();

    changeFilter(tipo);
});

function changeFilter(tipo) {
    var status = $('#filtroStatus').val();
    var url = route('bill.filter', { 'status': status, 'tipo': tipo });

    axios.post(url).then(function (response) {
        var data = JSON.parse(response.data);
        if (data.error) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: data.errorMessage
            });
        } else {
            updateFilter(data, tipo);
        }
    }).catch(function (error) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: error
        });
    });
}

function updateFilter(data, tipo) {
    if (tipo === 'Completo') {
        destroyComplete();
        loadCompleteTable(data, idColors);
    } else if (tipo === 'Simplificado') {
        destroySimple();
        loadSimpleTable(data, idColors);
    }
}

function cleanFields() {
    $('#tipo_conta').val('').change();
    $('#descricao').val('');
    $('#valor').val('');
    $('#parcelas').val('');
    $('#vencimento').val('');
    $('#recriar').val('Nao').change();
    $('#status').val('Pagar');
}

function loadOptions(identifiers, tipo) {
    if (tipo === 'Edt') {
        $("#tipo_contaEdt").empty();
        $('#tipo_contaEdt').append(new Option('', '', false, false));
    } else {
        $("#tipo_conta").empty();
        $('#tipo_conta').append(new Option('', '', true, true));
    }
    identifiers.forEach(function (identifier) {
        if (tipo === 'Edt') {
            if (identifier.ATIVO === 'Sim') {
                var option = new Option(identifier.DESCRICAO, identifier.id, false, false);
                $(option).attr('data-tipo', identifier.id);
                $('#tipo_contaEdt').append(option);
            } else if (identifier.ATIVO === 'Nao') {
                var option = new Option(identifier.DESCRICAO + ' - Desativado', '', false, false);
                $(option).attr('data-tipo', identifier.id);
                $('#tipo_contaEdt').append(option);
            }
        }
        else {
            $('#tipo_conta').append(new Option(identifier.DESCRICAO, identifier.id, false, false));
        }
    });
}
