var tableComplete;
function loadCompleteTable(data, idColors) {
    tableComplete = $('#table-bills').DataTable({
        'bFilter': false,
        'lengthChange': false,
        'bPaginate': true,
        'bInfo': false,
        fixedColumns: true,
        responsive: true,
        responsive: {
            details: {
                type: 'column'
            },

            breakpoints: [
                { name: 'desktop', width: Infinity },
                { name: 'tablet', width: 1024 },
                { name: 'fablet', width: 768 },
                { name: 'phone', width: 480 }
            ]

        },
        'language': {
            'loadingRecords': 'teste',
            'emptyTable': 'Nenhum dado encontrado',
            'paginate': {
                'next': '<i class="fa-thin fa-forward"></i>',
                'previous': '<i class="fa-thin fa-backward"></i>'
            }
        },
        'columnDefs': [
            {
                width: '25%', targets: [0, 1]
            },
            {
                width: '15%', targets: [2, 4]
            },
            {
                width: '10%', targets: [3, 5]
            },
            {
                'responsivePriority': 1,
                'targets': [0, 2, 3]
            }, {
                'responsivePriority': 2,
                'targets': [1, 4, 5]
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
                'targets': 3,
                'createdCell': function (td, cellData, rowData, row, col) {
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
            },
            {
                'targets': 4,
                'render': DataTable.render.datetime('DD/MM/YYYY')
            },
            {
                'targets': '_all',
                'createdCell': function (td, cellData, rowData, row, col) {
                    $(td).attr('class', 'controlBills');
                }
            },
        ],
        select: true,
        data: data,
        columns: [{ 'data': 'TIPO_CONTA_DESCRICAO' },
        { 'data': 'DESCRICAO' },
        { 'data': 'VALOR' },
        { 'data': 'STATUS' },
        { 'data': 'VENCIMENTO' },
        { 'data': 'PARCELAS' }]
    });

    tableComplete.cells().every(function (rowIdx, colIdx) {
        if (colIdx === 2) {
            var valorFormatado = formatValue(this.data());

            valorFormatado = valorFormatado.replace('.', '|').replace(',', '.').replace('|', ',');

            this.data(valorFormatado);
        }
    });

    tableComplete.on('click', 'tbody tr', function (e) {
        e.currentTarget.classList.toggle('selected');

        var selection = tableComplete.rows('.selected').data();
        var bills = [];

        if (selection.length > 0) {
            $('#btnDelete').prop('disabled', false);
            $('#btnConcluded').prop('disabled', false);
        } else {
            $('#btnDelete').prop('disabled', true);
            $('#btnConcluded').prop('disabled', true);
        }

        if (selection.length === 1) {
            $('#btnEdit').prop('disabled', false);
        } else {
            $('#btnEdit').prop('disabled', true);
        }


        selection.each(function (linha) {
            var id = linha.id;
            bills.push(id);
        });

        idBill = bills;
    });
}

function destroyComplete() {
    tableComplete.off('click');
    tableComplete.destroy();
}
