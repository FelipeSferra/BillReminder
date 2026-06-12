<?php

namespace App\Filament\Widgets\Stats;

use App\Models\Bill;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BillsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    protected ?string $pollingInterval = null;
    protected int|string|array $columnSpan = 6;

    protected function getStats(): array
    {
        return [
            Stat::make('Contas mensais em aberto', $this->totalBills())
                ->chart($this->chartNumberGenerator(15))
                ->color(Color::Blue)
                ->extraAttributes(
                    fn() => $this->totalBills() > 0 ? [
                        'wire:click' => "redirectUrl('total')",
                        'style' => 'cursor: pointer',
                    ] : []
                ),
            Stat::make('Contas vencidas', $this->overdueDate())
                ->chart($this->chartNumberGenerator(15))
                ->description(fn() => $this->overdueDate() > 0 ? 'Algumas contas não foram pagas ainda ' : '')
                ->descriptionIcon(Heroicon::ExclamationTriangle)
                ->color(Color::Red)
                ->extraAttributes(
                    fn() => $this->overdueDate() > 0 ? [
                        'wire:click' => "redirectUrl('overdue')",
                        'style' => 'cursor: pointer',
                    ] : []
                ),
            Stat::make('Contas mensais pagas', $this->paidBills())
                ->chart($this->chartNumberGenerator(15))
                ->color(Color::Purple)
                ->extraAttributes(
                    fn() => $this->paidBills() > 0 ? [
                        'wire:click' => "redirectUrl('paid')",
                        'style' => 'cursor: pointer',
                    ] : []
                ),
            Stat::Make('Total gasto', 'R$ ' . $this->totalSpent())
                ->description('Valor referente ao mês atual')
                ->chart($this->chartNumberGenerator(15))
                ->color(Color::Amber),
        ];
    }

    private function totalBills(): int
    {
        return Bill::where('VENCIMENTO', '>=', now()->startOfMonth())->where('VENCIMENTO', '<=', now()->endOfMonth())->where('STATUS', 'Pagar')->where('ID_USR', auth()->id())->count();
    }

    private function overdueDate(): int
    {
        return Bill::where('VENCIMENTO', '<', now())->where('STATUS', 'Pagar')->where('ID_USR', auth()->id())->count();
    }

    private function paidBills(): int
    {
        return Bill::where('PAGO_EM', '>=', now()->startOfMonth())->where('PAGO_EM', '<=', now()->endOfMonth())->where('STATUS', 'Pago')->where('ID_USR', auth()->id())->count();
    }

    private function totalSpent(): string
    {
        $totalValue = Bill::where('PAGO_EM', '>=', now()->startOfMonth())->where('PAGO_EM', '<=', now()->endOfMonth())->where('STATUS', 'Pago')->where('ID_USR', auth()->id())->sum('VALOR');
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

    public function redirectUrl($filterType)
    {
        $de = now()->startOfMonth()->format('Y-m-d');
        $ate = now()->endOfMonth()->format('Y-m-d');
        $status = 'Pagar';

        switch ($filterType) {
            case 'overdue':
                $de = null;
                $ate = now()->format('Y-m-d');
                $status = 'Pagar';
                break;
            case 'total':
                $status = 'Pagar';
                break;
            case 'paid':
                $status = 'Pago';
                break;
        }

        $url = $status == 'Pagar' ? '/bills?filters[filtro_avancado][STATUS]=' . $status . '&filters[filtro_avancado][vencimento_de]=' . $de . '&filters[filtro_avancado][vencimento_ate]=' . $ate
            : '/bills?filters[filtro_avancado][STATUS]=' . $status . '&filters[filtro_avancado][pago_de]=' . $de . '&filters[filtro_avancado][pago_ate]=' . $ate;

        return redirect()->to($url);
    }
}
