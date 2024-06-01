var tableIdentif;
// carrega os dados da tabela
function loadIdentifierTable(data) {
    tableIdentif = $('#table-identifiers').DataTable({
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
        columns: [{
            data: 'IDENTIF'
        },
        {
            data: 'DESCRICAO'
        },
        {
            data: 'ATIVO'
        },
        ]
    });

    // selecao da tabela para edicao
    $('#table-identifiers tbody').on('click', 'tr', function () {
        var selection = $(this).toggleClass('selected').toggleClass('selected');

        if (selection.hasClass('selected')) {
            tableIdentif.rows('.selected').nodes().each(function (row) {
                $(row).removeClass('selected');
                $('#btnEdit').prop('disabled', true);
                $('#btnDelete').prop('disabled', true);
            });
        } else {
            tableIdentif.rows('.selected').nodes().each(function (row) {
                $(row).removeClass('selected');
            });
            selection.addClass('selected');
            $('#btnEdit').prop('disabled', false);
            $('#btnDelete').prop('disabled', false);
        }

        idIdentif = tableIdentif.row(this).data().id;
    });
}

function destroyIdentifiers() {
    tableIdentif.off('click');
    tableIdentif.DataTable().destroy();
}
