$(document).ready(function() {
    var url = route('dashboard.data');

    axios.get(url).then(function(response) {
        var data = response.data;
        if (data.error) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: data.errorMessage
            });
        } else {
            var allBills = JSON.parse(data.allBills);
            var monthBills = JSON.parse(data.monthBills);
            var tableData = JSON.parse(data.tableData);
            var tableDueDate = JSON.parse(data.tableDueDate);
            var countBills = JSON.parse(data.countBills);
            var countDueDate = JSON.parse(data.countDueDate);
            var monthlyExpenses = JSON.parse(data.monthlyExpenses);

            loadTopBills(tableData);
            loadDueDate(tableDueDate);
            loadExpensesHistory(allBills);
            loadMonthlyChart(monthBills);

            monthlyExpenses = formatValue(monthlyExpenses.TOTAL_VALOR);
            $('#monthlyExpenses').text("R$ " + monthlyExpenses);
            $('#billsDue').text(countDueDate);
            $('#openBills').text(countBills);
        }
    }).catch(function(error) {
        console.log(error);
    });
});
