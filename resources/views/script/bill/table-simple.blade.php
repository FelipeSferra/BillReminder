<script>
    function loadSimpleTable(data) {
        const table = $('#table-bills-simple').DataTable({
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

                            applyCss(div, rowData.TIPO_CONTA,idColors);

                            $(td).append(div);
                        }
                    }
                },
                {
                    'targets': 2,
                    'createdCell': function(td, cellData, rowData, row, col) {
                        $(td).attr('class', 'valores');
                    }
                },
                {
                    'targets': '_all',
                    'createdCell': function(td, cellData, rowData, row, col) {
                        $(td).attr('class', 'controlBills');
                    }
                }
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
            }, {
                data: 'DESCRICAO'
            }, {
                data: 'TOTAL_VALOR'
            }]
        });
        table.cells().every(function(rowIdx, colIdx) {
            if (colIdx === 2){
                let valorFormatado = formatValue(this.data());

                valorFormatado = valorFormatado.replace('.', '|').replace(',', '.').replace('|', ',');

                this.data(valorFormatado);
            }
        });
    }

    function destroySimple() {
        $('#table-bills-simple').DataTable().destroy();
    }
</script>
