<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Identifier extends Model
{
    use SoftDeletes, HasFactory, HasUuids;
    protected $table = 'identifier';
    protected $fillable = [
        'IDENTIF',
        'TIPO_IDENTIFICADOR',
        'ID_HEX',
    ];

    public function relBllIdf()
    {
        return $this->hasMany(Bill::class, 'ID_IDENTIF', 'id');
    }

    public function relIncIdf()
    {
        return $this->hasMany(Income::class, 'ID_IDENTIF', 'id');
    }

    public function relFndIdf()
    {
        return $this->hasMany(Fund::class, 'ID_IDENTIF', 'id');
    }

    public function relInvIdf()
    {
        return $this->hasMany(Investiment::class, 'ID_IDENTIF', 'id');
    }
}
