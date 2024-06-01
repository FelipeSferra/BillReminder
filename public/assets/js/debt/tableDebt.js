var tableDebt;
function loadDebtTable(data) {
    tableDebt = $('#table-debt').DataTable({
        'bFilter': false,
        'lengthChange': false,
        'bPaginate': true,
        'bInfo': false,
        'language': {
            'emptyTable': 'Nenhum dado encontrado',
            'paginate': {
                'next': '<i class="fa-thin fa-forward"></i>',
                'previous': '<i class="fa-thin fa-backward"></i>'
            }
        },
        select: true,
        data: data,
        columns: [
            {
                data: 'NOME'
            }, {
                data: 'EMAIL'
            }, {
                data: 'ATIVO'
            }
        ]
    });

    // selecao da tabela para edicao
    tableDebt.on('click', 'tbody tr', function (e) {
        var selection = $(this).toggleClass('selected').toggleClass('selected');

        if (selection.hasClass('selected')) {
            tableDebt.rows('.selected').nodes().each(function (row) {
                $(row).removeClass('selected');
                $('#btnEdit').prop('disabled', true);
                $('#btnDelete').prop('disabled', true);
            });
        } else {
            tableDebt.rows('.selected').nodes().each(function (row) {
                $(row).removeClass('selected');
            });
            selection.addClass('selected');
            $('#btnEdit').prop('disabled', false);
            $('#btnDelete').prop('disabled', false);
        }

        idDebt = tableDebt.row(this).data().id;
    });
}

function destroyDebt() {
    tableDebt.off('click');
    tableDebt.DataTable().destroy();
}
