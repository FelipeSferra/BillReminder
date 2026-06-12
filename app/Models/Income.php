<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use SoftDeletes, HasFactory, HasUuids;
    protected $table = 'income';
    protected $fillable = [
        'ID_IDENTIF',
        'ID_USR',
        'DESCRICAO',
        'VALOR',
        'VALOR_FIXO',
        'RECEBIDO_EM',
    ];

    public function relUsrInc()
    {
        return $this->belongsTo(User::class, 'ID_USR', 'id');
    }

    public function relIdfInc()
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
            'VALOR_FIXO' => 'boolean',
            'RECEBIDO_EM' => 'date',
        ];
    }
}
