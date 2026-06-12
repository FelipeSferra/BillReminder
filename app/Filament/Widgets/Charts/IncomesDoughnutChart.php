<?php

namespace App\Filament\Widgets\Charts;

use Filament\Widgets\ChartWidget;

class IncomesDoughnutChart extends ChartWidget
{
    protected static ?int $sort = 7;
    protected ?string $heading = 'Receitas';
    protected int|string|array $columnSpan = 2;

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
