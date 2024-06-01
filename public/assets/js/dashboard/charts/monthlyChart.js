function loadMonthlyChart(data) {
    if (data.length === 0) {
        $('#monthBills-dashboard').css({
            'display': 'none'
        });
        $('#hasNothingMonth').css({
            'display': 'block',
            'max-width': '100%',
            'height': '300px',
            'max-height': '100%'
        });
        $('#hasNothingMonth').addClass('d-flex justify-content-center align-items-center font-weight-bolder')
        $('#hasNothingMonth').text('Nenhum dado encontrado');
    } else {
        var data = {
            labels: data.map(item => item.TIPO_CONTA_DESCRICAO),
            datasets: [{
                backgroundColor: data.map(function (item) {
                    return 'rgba(' + hexToRgb(item.ID_HEX) + ', 0.2)';
                }),
                borderColor: data.map(function (item) {
                    if (item.ID_HEX === '#FFFFFF')
                        return '#000000';
                    else
                        return item.ID_HEX;
                }),
                borderWidth: 1,
                data: data.map(item => item.TOTAL_VALOR),
            }]
        };

        var options = {
            responsive: true,
            aspectRatio: 2.6,
        };

        var chart = document.getElementById('monthBills-dashboard');
        var monthlyChart = new Chart(chart, {
            type: 'doughnut',
            data: data,
            options: options
        });

        window.addEventListener('resize', function () {
            monthlyChart.resize();
        });
    }
}
