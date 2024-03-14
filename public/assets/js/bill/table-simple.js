var tableSimple;
function loadSimpleTable(data, idColors) {
    tableSimple = $('#table-bills-simple').DataTable({
        'bFilter': false,
        'lengthChange': false,
        'bPaginate': true,
        'bInfo': false,
        responsive: true,
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
                name: 'phone',
                width: 480
            }]
        },
        'language': {
            'emptyTable': 'Nenhum dado encontrado',
            'paginate': {
                'next': '<i class="fa-thin fa-forward"></i>',
                'previous': '<i class="fa-thin fa-backward"></i>'
            }
        },
        'columnDefs': [
            {
                width: '35%', targets: [0, 1]
            },
            {
                width: '30%', targets: 2
            },
            {
                'responsivePriority': 1,
                'targets': [0, 2]
            },
            {
                'responsivePriority': 2,
                'targets': 1
            },
            {
                'targets': 0,
                'createdCell': function (td, cellData, rowData, row, col) {
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
                'targets': 2,
                'createdCell': function (td, cellData, rowData, row, col) {
                    $(td).attr('class', 'valores');
                }
            },
            {
                'targets': '_all',
                'createdCell': function (td, cellData, rowData, row, col) {
                    $(td).attr('class', 'controlBills');
                }
            }
        ],
        select: true,
        data: data,
        columns: [{
            data: 'TIPO_CONTA_DESCRICAO'
        }, {
            data: 'DESCRICAO'
        }, {
            data: 'TOTAL_VALOR'
        }]
    });
    tableSimple.cells().every(function (rowIdx, colIdx) {
        if (colIdx === 2) {
            var valorFormatado = formatValue(this.data());

            valorFormatado = valorFormatado.replace('.', '|').replace(',', '.').replace('|', ',');

            this.data(valorFormatado);
        }
    });
}

function destroySimple() {
    tableSimple.destroy();
}
