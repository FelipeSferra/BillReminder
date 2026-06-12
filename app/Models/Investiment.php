<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investiment extends Model
{
    use SoftDeletes, HasFactory, HasUuids;
    protected $table = 'investiment';
    protected $fillable = [
        'ID_IDENTIF',
        'ID_USR',
        'DESCRICAO',
        'VALOR',
    ];

    public function relUsrInv()
    {
        return $this->belongsTo(User::class, 'ID_USR', 'id');
    }

    public function relIdfInv()
    {
        return $this->belongsTo(Identifier::class, 'ID_IDENTIF', 'id');
    }

    public function relInvHst()
    {
        return $this->hasMany(InvestimentHistory::class, 'ID_INVESTIMENT', 'id');
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
        ];
    }
}
