<script>
    $(document).ready(function() {
        loadTopBills(@json($tableData));
        loadDueDate(@json($tableDueDate));
        @if ($monthlyExpenses->TOTAL_VALOR > 0)
            let monthlyExpenses = formatValue({{ $monthlyExpenses->TOTAL_VALOR }});
        @else
            let monthlyExpenses = formatValue(0);
        @endif
        $('#monthlyExpenses').text("R$ " + monthlyExpenses);
    });
</script>
