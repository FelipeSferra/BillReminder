<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    use SoftDeletes, HasFactory, HasUuids;
    protected $table = 'fund';
    protected $fillable = [
        'ID_IDENTIF',
        'ID_USR',
        'DESCRICAO',
        'VALOR',
        'META',
    ];

    public function relUsrFnd()
    {
        return $this->belongsTo(User::class, 'ID_USR', 'id');
    }

    public function relIdfFnd()
    {
        return $this->belongsTo(Identifier::class, 'ID_IDENTIF', 'id');
    }

    public function relFndHst()
    {
        return $this->hasMany(FundHistory::class, 'ID_FUND', 'id');
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
            'META' => 'decimal:2',
        ];
    }
}
