<?php

namespace App\Filament\Widgets\Charts;

use Filament\Widgets\ChartWidget;

class BillsDoughnutChart extends ChartWidget
{
    protected static ?int $sort = 5;
    protected ?string $heading = 'Gastos';
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
