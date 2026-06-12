<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Bill extends Model
{
    use SoftDeletes, HasFactory, HasUuids;
    protected $table = 'bill';
    protected $fillable = [
        'ID_IDENTIF',
        'ID_USR',
        'DESCRICAO',
        'VALOR',
        'VENCIMENTO',
        'PARCELAS',
        'STATUS',
        'RECORRENTE',
        'ID_REC',
        'PAGO_EM',
    ];

    public function relUsrBll()
    {
        return $this->belongsTo(User::class, 'ID_USR', 'id');
    }

    public function relIdfBll()
    {
        return $this->belongsTo(Identifier::class, 'ID_IDENTIF', 'id');
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
            'VENCIMENTO' => 'date',
            'PARCELAS' => 'integer',
            'RECORRENTE' => 'boolean',
            'PAGO_EM' => 'date',
        ];
    }
}
