<?php

namespace App\Livewire\Tables;

use App\Filament\Tables\InvestimentHistoryTable;
use App\Models\InvestimentHistory;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

use Illuminate\Database\Eloquent\Builder;

use Livewire\Component;

class InvestimentHistoryTableComponent extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public string $investimentId;

    public function table(Table $table): Table
    {
        return InvestimentHistoryTable::configure(
            $table->query(fn(): Builder => InvestimentHistory::query()->where('ID_INVESTIMENT', $this->investimentId))
        );
    }

    public function render()
    {
        return view('livewire.tables.investiment-history-table-component');
    }
}
