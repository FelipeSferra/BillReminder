<?php

namespace App\Livewire\Tables;

use App\Filament\Tables\FundHistoryTable;
use App\Models\FundHistory;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

use Illuminate\Database\Eloquent\Builder;

use Livewire\Component;

class FundHistoryTableComponent extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public string $fundId;

    public function table(Table $table): Table
    {
        return FundHistoryTable::configure(
            $table->query(fn(): Builder => FundHistory::query()->where('ID_FUND', $this->fundId))
        );
    }

    public function render()
    {
        return view('livewire.tables.fund-history-table-component');
    }
}
