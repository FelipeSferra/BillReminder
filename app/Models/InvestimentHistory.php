<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvestimentHistory extends Model
{
    use SoftDeletes, HasFactory, HasUuids;

    protected $table = 'investiment_history';

    protected $fillable = [
        'ID_INVESTIMENT',
        'ID_USR',
        'VALOR',
        'TIPO_OPERACAO',
        'DATA_OPERACAO',
    ];

    public function relInv()
    {
        return $this->belongsTo(Investiment::class, 'ID_INVESTIMENT', 'id');
    }

    public function relUsr()
    {
        return $this->belongsTo(User::class, 'ID_USR', 'id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'VALOR' => 'decimal:2',
            'DATA_OPERACAO' => 'date',
        ];
    }
}
