<script>
    function loadTopBills(data) {
        var idColors = {};

        data.forEach(function(color) {
            idColors[color.TIPO_CONTA] = color.ID_HEX;
        });

        const table = $('#topBillsTable').DataTable({
            'bFilter': false,
            'lengthChange': false,
            'bPaginate': false,
            'bInfo': false,
            "columnDefs": [{
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
            }, {
                'targets': '_all',
                "orderable": false,
            }],
            "order": [
                [1, "desc"]
            ],
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
            },
            select: true,
            data: data,
            columns: [{
                data: 'DESCRICAO'
            }, {
                data: 'VALOR'
            }]
        });
        table.cells().every(function(rowIdx, colIdx) {
            if (colIdx === 1) {
                let valorFormatado = formatValue(this.data());

                this.data(valorFormatado);
            }
        });
        $('.sorting, .sorting_asc, .sorting_desc').removeClass('sorting sorting_asc sorting_desc').addClass(
            'no-sorting');
    }
</script>
