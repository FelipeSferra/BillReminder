<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdentifierModel extends Model
{
    use HasFactory;
    protected $table = 'identifier';
    protected $fillable = [
        'ID_USR',
        'IDENTIF',
        'DESCRICAO',
        'ATIVO',
        'ID_HEX',
        'DUMP'
    ];

    public function relUsrIdf(){
        return $this->hasOne(User::class,'id','ID_USR');
    }

    public function relBllIdf(){
        return $this->hasMany(BillModel::class,'TIPO_CONTA');
    }

    public function relDbtIdf(){
        return $this->hasMany(DebtModel::class,'TIPO_CONTA');
    }
}
