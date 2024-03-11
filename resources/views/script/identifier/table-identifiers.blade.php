<script>
    // carrega os dados da tabela
    function loadIdentifierTable(data) {
        const table = $('#table-identifiers').DataTable({
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
        $('#table-identifiers tbody').on('click', 'tr', function() {
            let classList = $(this).toggleClass('selected').toggleClass('selected');

            if (classList.hasClass('selected')) {
                table.rows('.selected').nodes().each(function(row) {
                    $(row).removeClass('selected');
                    $('#btnEdit').prop('disabled', true);
                    $('#btnDelete').prop('disabled', true);
                });
            } else {
                table.rows('.selected').nodes().each(function(row) {
                    $(row).removeClass('selected');
                });
                classList.addClass('selected');
                $('#btnEdit').prop('disabled', false);
                $('#btnDelete').prop('disabled', false);
            }

            idIdentif = table.row(this).data().id;
        });
    }

    function destroyIdentifiers() {
        $('#table-identifiers tbody').off('click');
        $('#table-identifiers').DataTable().destroy();
    }
</script>
