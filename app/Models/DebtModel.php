<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebtModel extends Model
{
    use HasFactory;
    protected $table = 'debt';
    protected $fillable = [
        'ID_USR',
        'NOME',
        'EMAIL',
        'ATIVO',
        'DUMP'
    ];

    public function relUsrDbt()
    {
        return $this->hasOne(User::class, 'id', 'ID_USR');
    }
}
