<script>
    let tipo = $('#tipoVisu').data('checked');

    $('#ModalCreate').on('hidden.bs.modal', function() {
        cleanFields();
    });

    $('#tipoVisu').bootstrapToggle({
        onlabel: 'Completo',
        offlabel: 'Simplificado',
        onstyle: 'success',
        offstyle: 'secondary'
    });

    $(document).ready(function() {
        $('#simple').addClass('d-none');
        loadCompleteTable(@json($bills));
        loadSimpleTable(@json($billsPerType));
    });

    //pega os dados da edicao e passa para a model
    $('#btnEdit').on('click', function(e) {
        e.preventDefault();

        let url = "{{ secure_url(route('bill.edit', ['id' => 0])) }}";
        url = url.replace(0, idBill);

        axios.get(url).then(function(response) {
            let bill = response.data;

            if (bill.notFound) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'A conta não foi encontrada, tente novamente.',
                });
            } else {
                $('#tipo_contaEdt option').each(function() {
                    if ($(this).data('tipo') === bill.TIPO_CONTA) {
                        $(this).prop('selected', true);
                    }
                });

                $('#descricaoEdt').val(bill.DESCRICAO);

                $('#valorEdt').val(formatValue(bill.VALOR));

                $('#parcelasEdt').val(bill.PARCELAS);

                $('#vencimentoEdt').val(bill.VENCIMENTO);

                $('#recriarEdt option').each(function() {
                    if ($(this).data('rec') === bill.RECRIAR) {
                        $(this).prop('selected', true);
                    }
                });

                $('#statusEdt').val(bill.STATUS);

                $('#ModalEdit').modal('show');
            }
        }).catch(function(error) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: error
            });
        });
    });

    //envia o formulario de edição
    $('#formEdt').on('submit', function(e) {
        e.preventDefault();

        var url = "{{ secure_url(route('bill.update', ['id' => 0])) }}";
        url = url.replace(0, idBill);
        var data = {
            tipo_contaEdt: $('#tipo_contaEdt').val(),
            descricaoEdt: $('#descricaoEdt').val(),
            valorEdt: $('#valorEdt').val(),
            parcelasEdt: $('#parcelasEdt').val(),
            vencimentoEdt: $('#vencimentoEdt').val(),
            recriarEdt: $('#recriarEdt').val(),
            statusEdt: $('#statusEdt').val(),
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
                    changeFilter('Completo');
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

    //envia o formulario de criação
    $('#formCrt').on('submit', function(e) {
        e.preventDefault();

        var url = "{{ secure_url(route('bill.create')) }}";
        var data = {
            tipo_conta: $('#tipo_conta').val(),
            descricao: $('#descricao').val(),
            valor: $('#valor').val(),
            parcelas: $('#parcelas').val(),
            vencimento: $('#vencimento').val(),
            recriar: $('#recriar').val(),
            status: $('#status').val(),
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
                    cleanFields();
                    changeFilter('Completo');
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
        let url = "{{ secure_url(route('bill.destroy', ['id' => 0])) }}";
        url = url.replace(0, idBill);

        axios.delete(url).then(function(response) {
            let data = response.data;

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
        }).catch(function(error) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: error
            });
        });
    }

    jscolor.presets.default = {
        format: 'hex'
    };

    $('#btnConcluded').on('click', function(e) {
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
        let url = "{{ secure_url(route('bill.concluded', ['id' => 0])) }}";
        url = url.replace(0, idBill);

        axios.post(url).then(function(response) {
            let data = response.data;
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
        }).catch(function(error) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: error
            });
        });
    }

    $('#tipoVisu').change(function() {
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

    $('#filtroStatus').on('change', function(e) {
        e.preventDefault();

        changeFilter(tipo);
    });

    function changeFilter(tipo) {
        let status = $('#filtroStatus').val();
        let url = "{{ secure_url(route('bill.filter', ['status' => 'status', 'tipo' => 'tipo'])) }}";
        url = url.replace('status', status);
        url = url.replace('tipo', tipo);

        axios.post(url).then(function(response) {
            let data = JSON.parse(response.data);
            if (data.error) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: data.errorMessage
                });
            } else {
                updateFilter(data, tipo);
            }
        }).catch(function(error) {
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
            loadCompleteTable(data);
        } else if (tipo === 'Simplificado') {
            destroySimple();
            loadSimpleTable(data);
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
</script>
