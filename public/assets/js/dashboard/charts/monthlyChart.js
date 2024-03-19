function loadMonthlyChart(data){
    if (data.length === 0) {
        var canvas = document.getElementById('allBills-dashboard');
        var ctx = canvas.getContext('2d');
        ctx.font = '20px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('Nenhum dado disponível', canvas.width / 2, canvas.height / 2);
    } else {
        var data = {
            labels: data.map(item => item.TIPO_CONTA_DESCRICAO),
            datasets: [{
                backgroundColor: data.map(function(item) {
                    return 'rgba(' + hexToRgb(item.ID_HEX) + ', 0.2)';
                }),
                borderColor: data.map(function(item) {
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

        window.addEventListener('resize', function() {
            monthlyChart.resize();
        });
    }
}
