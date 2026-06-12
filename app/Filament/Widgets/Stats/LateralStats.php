<?php

namespace App\Filament\Widgets\Stats;

use App\Models\Fund;
use App\Models\Income;
use App\Models\Investiment;
use Filament\Support\Colors\Color;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LateralStats extends StatsOverviewWidget
{
    protected static ?int $sort = 3;
    protected ?string $pollingInterval = null;
    protected int|string|array $columnSpan = 2;

    protected function getStats(): array
    {
        return [
            Stat::make('Total recebido', 'R$ ' . $this->totalIncomes())
                ->chart($this->chartNumberGenerator(15))
                ->color(Color::Emerald)
                ->description('Valor referente ao mês atual'),
            Stat::make('Total investido', 'R$ ' . $this->totalInvestiments())
                ->chart($this->chartNumberGenerator(15))
                ->color(Color::Zinc),
            Stat::make('Total reservado', 'R$ ' . $this->totalFunds())
                ->chart($this->chartNumberGenerator(15))
                ->color(Color::Cyan),
        ];
    }

    private function totalIncomes()
    {
        $totalValue = Income::where('RECEBIDO_EM', '>=', now()->startOfMonth()->format('Y-m-d'))->where('RECEBIDO_EM', '<=', now()->endOfMonth()->format('Y-m-d'))->where('ID_USR', auth()->id())->sum('VALOR');
        return number_format($totalValue, 2, ',', '.');
    }

    private function totalInvestiments()
    {
        $totalValue = Investiment::where('ID_USR', auth()->id())->sum('VALOR');
        return number_format($totalValue, 2, ',', '.');
    }

    private function totalFunds()
    {
        $totalValue = Fund::where('ID_USR', auth()->id())->sum('VALOR');
        return number_format($totalValue, 2, ',', '.');
    }

    private function chartNumberGenerator($increment)
    {
        $chartData = [];

        for ($i = 0; $i < $increment; $i++) {
            $chartData[] = rand(1, 20);
        }

        return $chartData;
    }
}
