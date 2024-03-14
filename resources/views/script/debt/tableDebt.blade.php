<script>
    let idDebt;
    let identifiers = @json($identifiers);
    let identifiersMap = @json($identifiersMap);
    let idColors = {};

    @foreach ($identifiers as $identifier)
        idColors[{{ $identifier->id }}] = "{{ $identifier->ID_HEX }}";
    @endforeach

    function loadDebtTable(data) {
        const table = $('#table-debts').DataTable({
            'bFilter': false,
            'lengthChange': false,
            'bPaginate': true,
            'bInfo': false,
            responsive: {
                details: {
                    type: 'column'
                },
                breakpoints: [{
                        name: 'desktop',
                        width: Infinity
                    },
                    {
                        name: 'tablet',
                        width: 1024
                    },
                    {
                        name: 'fablet',
                        width: 768
                    },
                    {
                        name: 'phone',
                        width: 480
                    }
                ]
            },
            'language': {
                'emptyTable': 'Nenhum dado encontrado',
                'paginate': {
                    'next': '<i class="fa-thin fa-forward"></i>',
                    'previous': '<i class="fa-thin fa-backward"></i>'
                }
            },
            'columnDefs': [{
                    'targets': 0,
                    'createdCell': function(td, cellData, rowData, row, col) {
                        if (rowData.TIPO_CONTA in idColors) {
                            $(td).empty();

                            var div = $('<div/>', {
                                class: 'tipo-conta div-inner text-center',
                                'data-id': rowData.TIPO_CONTA,
                                text: cellData
                            });

                            applyCss(div, rowData.TIPO_CONTA, idColors);

                            $(td).append(div);
                        }
                    }
                },
                {
                    'targets': 3,
                    'createdCell': function(td, cellData, rowData, row, col) {
                        $(td).attr('class', 'valores');
                    }
                },
                {
                    'targets': 4,
                    'createdCell': function(td, cellData, rowData, row, col) {
                        $(td).empty();

                        var div = $('<div/>', {
                            class: 'div-inner text-center',
                            text: cellData
                        });

                        if (cellData === 'Pagar')
                            applyCss(div, 0, '', '#FFCE57');
                        if (cellData === 'Pago')
                            applyCss(div, 0, '', '#8AD38C');

                        $(td).append(div);
                    }
                }, {
                    'targets': '_all',
                    'createdCell': function(td, cellData, rowData, row, col) {
                        $(td).attr('class', 'controlDebts');
                    }
                },
            ],
            drawCallback: function() {
                $('.tipo-conta').each(function() {
                    let id = $(this).data('id');
                    if (identifiersMap.hasOwnProperty(id))
                        $(this).text(identifiersMap[id]);
                });
            },
            select: true,
            data: data,
            columns: [{
                    data: 'TIPO_CONTA'
                },
                {
                    data: 'DESCRICAO'
                },
                {
                    data: 'NOME'
                },
                {
                    data: 'VALOR'
                },
                {
                    data: 'STATUS'
                },
                {
                    data: 'data_formatada'
                },
                {
                    data: 'PARCELAS'
                }
            ]
        });

        table.cells().every(function(rowIdx, colIdx) {
            if (colIdx === 2) {
                let valorFormatado = formatValue(this.data());

                valorFormatado = valorFormatado.replace('.', '|').replace(',', '.').replace('|', ',');

                this.data(valorFormatado);
            }
        });

        // selecao da tabela para edicao
        $('#table-debts tbody').on('click', 'tr', function() {
            let classList = $(this).toggleClass('selected').toggleClass('selected');

            if (classList.hasClass('selected')) {
                table.rows('.selected').nodes().each(function(row) {
                    $(row).removeClass('selected');
                    $('#btnEdit').prop('disabled', true);
                    $('#btnDelete').prop('disabled', true);
                    $('#btnConcluded').prop('disabled', true);
                });
            } else {
                table.rows('.selected').nodes().each(function(row) {
                    $(row).removeClass('selected');
                });
                classList.addClass('selected');
                $('#btnEdit').prop('disabled', false);
                $('#btnDelete').prop('disabled', false);
                $('#btnConcluded').prop('disabled', false);
            }

            idDebt = table.row(this).data().id;
        });
    }
</script>
