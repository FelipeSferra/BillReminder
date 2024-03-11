<script>
    var allData = {{ Js::from($allBillsByType) }};
    var datasets = [];

    /*     var currentMonthName = new Date().getMonth() + 1;

        var previousMonthData = allData.filter(item => item.MES_NUM != currentMonthName);
        var monthlyData = [];

        previousMonthData.forEach(function(item) {
            item = {
                'MES': '',
                'ANO': 0000,
                'TOTAL_VALOR': 0000,
                'MES_NUM': 0
            };
            monthlyData.push(item);
        })

        monthlyData = monthlyData.concat(allData.filter(item => item.MES_NUM == currentMonthName));

        if (previousMonthData.length > 0) {
            datasets.push({
                label: 'Gastos de Meses Passados',
                backgroundColor: 'rgba(' + hexToRgb('#34344c') + ', 0.2)',
                borderColor: '#34344c',
                borderWidth: 1,
                data: previousMonthData.map(item => item.TOTAL_VALOR),
            });
        }

        datasets.push({
            label: 'Gastos do mês atual',
            backgroundColor: 'rgba(' + hexToRgb('#8b8bcc') + ', 0.2)',
            borderColor: '#8b8bcc',
            borderWidth: 1,
            data: monthlyData.map(item => item.TOTAL_VALOR),
        }); */
    if (allData.length === 0) {
        $('#allBills-dashboard').css({'display': 'none'});
        $('#hasNothing').css({'display': 'block', 'max-width': '100%','height': '300px', 'max-height':'100%'});
        $('#hasNothing').addClass('d-flex justify-content-center align-items-center font-weight-bolder')
        $('#hasNothing').text('Nenhum dado encontrado');
    } else {
        datasets.push({
            label: 'Gastos de Meses Passados',
            backgroundColor: 'rgba(' + hexToRgb('#34344c') + ', 0.2)',
            borderColor: '#34344c',
            borderWidth: 1,
            data: allData.map(item => item.TOTAL_VALOR),
        });

        var data = {
            labels: allData.map(item => item.MES),
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
</script>
