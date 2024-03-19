function loadExpensesHistory(data) {
    var datasets = [];

    if (data.length === 0) {
        $('#allBills-dashboard').css({
            'display': 'none'
        });
        $('#hasNothing').css({
            'display': 'block',
            'max-width': '100%',
            'height': '300px',
            'max-height': '100%'
        });
        $('#hasNothing').addClass('d-flex justify-content-center align-items-center font-weight-bolder')
        $('#hasNothing').text('Nenhum dado encontrado');
    } else {
        datasets.push({
            label: 'Gastos de Meses Passados',
            backgroundColor: 'rgba(' + hexToRgb('#34344c') + ', 0.2)',
            borderColor: '#34344c',
            borderWidth: 1,
            data: data.map(item => item.TOTAL_VALOR),
        });

        var data = {
            labels: data.map(item => item.MES),
            datasets: datasets
        };

        var options = {
            indexAxis: 'x',
            responsive: true,
            aspectRatio: 2.6,
        };

        var ctx = document.getElementById('allBills-dashboard');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: options
        });

        window.addEventListener('resize', function() {
            myChart.resize();
        })
    };
}
