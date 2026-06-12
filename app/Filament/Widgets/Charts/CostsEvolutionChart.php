<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Bill;
use App\Models\Income;
use App\Models\FundHistory;
use App\Models\InvestimentHistory;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class CostsEvolutionChart extends ChartWidget
{
    protected static ?int $sort = 2;
    protected ?string $heading = 'Evolução de custos';
    protected int|string|array $columnSpan = 4;
    protected ?string $maxHeight = '300px';

    protected ?array $options = [
        'scales' => [
            'y' => [
                'min' => 0,
                'ticks' => [
                    'stepSize' => 1000,
                ],
            ],
        ],
    ];

    public ?string $filter = '11';

    protected function getFilters(): ?array
    {
        return [
            '11' => '12 meses',
            '8' => '9 meses',
            '5' => '6 meses',
            '2' => '3 meses',
            '0' => 'Desde o início',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        return [
            'datasets' => [
                [
                    'label' => 'Gastos',
                    'data' => $this->getTotalSpent($activeFilter),
                    'borderColor' => '#FFBF00'
                ],
                [
                    'label' => 'Receitas',
                    'data' => $this->getTotalReceived($activeFilter),
                    'borderColor' => '#50C878'
                ],
                [
                    'label' => 'Investimentos',
                    'data' => $this->getTotalInvestments($activeFilter),
                    'borderColor' => '#92898A'
                ],
                [
                    'label' => 'Reservas',
                    'data' => $this->getTotalFunded($activeFilter),
                    'borderColor' => '#00FFFF'
                ],
            ],
            'labels' => $this->getLabels($activeFilter),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function getTotalSpent(int $months): array
    {
        $totalSpent = [];

        if ($months == 0) {
            $oldest = Bill::where('STATUS', 'Pago')
                ->where('ID_USR', auth()->id())
                ->orderBy('VENCIMENTO', 'asc')
                ->value('VENCIMENTO');
            if (!$oldest) {
                return [];
            }

            $start = Carbon::parse($oldest)->startOfMonth();
            $end = Carbon::now()->startOfMonth();
            $current = $start->copy();

            while ($current->lte($end)) {
                $totalSpent[] = Bill::where('STATUS', 'Pago')
                    ->where('ID_USR', auth()->id())
                    ->whereYear('VENCIMENTO', $current->year)
                    ->whereMonth('VENCIMENTO', $current->month)
                    ->sum('VALOR');

                $current->addMonthNoOverflow();
            }

            return $totalSpent;
        }

        for ($i = $months; $i >= 0; $i--) {
            $date = Carbon::now()->subMonthsNoOverflow($i);

            $totalSpent[] = Bill::where('STATUS', 'Pago')
                ->where('ID_USR', auth()->id())
                ->whereYear('VENCIMENTO', $date->year)
                ->whereMonth('VENCIMENTO', $date->month)
                ->sum('VALOR');
        }

        return $totalSpent;
    }

    private function getTotalReceived(int $months): array
    {
        $totalReceived = [];

        if ($months == 0) {
            $oldest = Income::where('iD_USR', auth()->id())
                ->orderBy('RECEBIDO_EM', 'asc')
                ->value('RECEBIDO_EM');
            if (!$oldest) {
                return [];
            }

            $start = Carbon::parse($oldest)->startOfMonth();
            $end = Carbon::now()->startOfMonth();
            $current = $start->copy();

            while ($current->lte($end)) {
                $totalReceived[] = Income::where('iD_USR', auth()->id())
                    ->whereYear('RECEBIDO_EM', $current->year)
                    ->whereMonth('RECEBIDO_EM', $current->month)
                    ->sum('VALOR');

                $current->addMonthNoOverflow();
            }

            return $totalReceived;
        }

        for ($i = $months; $i >= 0; $i--) {
            $date = Carbon::now()->subMonthsNoOverflow($i);

            $totalReceived[] = Income::where('iD_USR', auth()->id())
                ->whereYear('RECEBIDO_EM', $date->year)
                ->whereMonth('RECEBIDO_EM', $date->month)
                ->sum('VALOR');
        }

        return $totalReceived;
    }

    private function getTotalFunded(int $months): array
    {
        $totalFunded = [];

        if ($months == 0) {
            $oldest = FundHistory::where('ID_USR', auth()->id())
                ->orderBy('DATA_OPERACAO', 'asc')
                ->value('DATA_OPERACAO');
            if (!$oldest) {
                return [];
            }

            $start = Carbon::parse($oldest)->startOfMonth();
            $end = Carbon::now()->startOfMonth();
            $current = $start->copy();

            while ($current->lte($end)) {
                $endOfMonth = $current->copy()->endOfMonth();

                $guardar = FundHistory::where('ID_USR', auth()->id())
                    ->where('DATA_OPERACAO', '<=', $endOfMonth)
                    ->where('TIPO_OPERACAO', 'GUARDAR')
                    ->sum('VALOR');

                $retirar = FundHistory::where('ID_USR', auth()->id())
                    ->where('DATA_OPERACAO', '<=', $endOfMonth)
                    ->where('TIPO_OPERACAO', 'RETIRAR')
                    ->sum('VALOR');

                $totalFunded[] = $guardar - $retirar;

                $current->addMonthNoOverflow();
            }

            return $totalFunded;
        }

        for ($i = $months; $i >= 0; $i--) {
            $date = Carbon::now()->subMonthsNoOverflow($i);
            $endOfMonth = $date->copy()->endOfMonth();

            $guardar = FundHistory::where('ID_USR', auth()->id())
                ->where('DATA_OPERACAO', '<=', $endOfMonth)
                ->where('TIPO_OPERACAO', 'GUARDAR')
                ->sum('VALOR');

            $retirar = FundHistory::where('ID_USR', auth()->id())
                ->where('DATA_OPERACAO', '<=', $endOfMonth)
                ->where('TIPO_OPERACAO', 'RETIRAR')
                ->sum('VALOR');

            $totalFunded[] = $guardar - $retirar;
        }

        return $totalFunded;
    }

    private function getTotalInvestments(int $months): array
    {
        $totalInvestments = [];

        if ($months == 0) {
            $oldest = InvestimentHistory::where('ID_USR', auth()->id())
                ->orderBy('DATA_OPERACAO', 'asc')
                ->value('DATA_OPERACAO');
            if (!$oldest) {
                return [];
            }

            $start = Carbon::parse($oldest)->startOfMonth();
            $end = Carbon::now()->startOfMonth();
            $current = $start->copy();

            while ($current->lte($end)) {
                $endOfMonth = $current->copy()->endOfMonth();

                $aplicar = InvestimentHistory::where('ID_USR', auth()->id())
                    ->where('DATA_OPERACAO', '<=', $endOfMonth)
                    ->where('TIPO_OPERACAO', 'APLICAR')
                    ->sum('VALOR');

                $resgatar = InvestimentHistory::where('ID_USR', auth()->id())
                    ->where('DATA_OPERACAO', '<=', $endOfMonth)
                    ->where('TIPO_OPERACAO', 'RESGATAR')
                    ->sum('VALOR');

                $totalInvestments[] = $aplicar - $resgatar;

                $current->addMonthNoOverflow();
            }

            return $totalInvestments;
        }

        for ($i = $months; $i >= 0; $i--) {
            $date = Carbon::now()->subMonthsNoOverflow($i);
            $endOfMonth = $date->copy()->endOfMonth();

            $aplicar = InvestimentHistory::where('ID_USR', auth()->id())
                ->where('DATA_OPERACAO', '<=', $endOfMonth)
                ->where('TIPO_OPERACAO', 'APLICAR')
                ->sum('VALOR');

            $resgatar = InvestimentHistory::where('ID_USR', auth()->id())
                ->where('DATA_OPERACAO', '<=', $endOfMonth)
                ->where('TIPO_OPERACAO', 'RESGATAR')
                ->sum('VALOR');

            $totalInvestments[] = $aplicar - $resgatar;
        }

        return $totalInvestments;
    }

    private function getLabels(int $months): array
    {
        $labels = [];

        if ($months == 0) {
            $oldest = Bill::where('STATUS', 'Pago')
                ->where('ID_USR', auth()->id())
                ->orderBy('VENCIMENTO', 'asc')
                ->value('VENCIMENTO');

            if (!$oldest) {
                $oldest = Income::where('iD_USR', auth()->id())
                    ->orderBy('RECEBIDO_EM', 'asc')
                    ->value('RECEBIDO_EM');

                if (!$oldest) {
                    return [];
                }
            }

            $start = Carbon::parse($oldest)->startOfMonth();
            $end = Carbon::now()->startOfMonth();

            $current = $start->copy();

            while ($current->lte($end)) {
                $labels[] = $current->shortMonthName . '/' . $current->year;
                $current->addMonthNoOverflow();
            }

            return $labels;
        }

        for ($i = $months; $i >= 0; $i--) {
            $month = Carbon::now()->subMonthsNoOverflow($i);
            $labels[] = $month->shortMonthName . '/' . $month->year;
        }

        return $labels;
    }
}
