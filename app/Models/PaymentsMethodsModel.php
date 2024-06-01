<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentsMethods extends Model
{
    use HasFactory;
    protected $table = 'paymentsMethods';
    protected $fillable = [
        'ID_USR',
        'DESCRICAO',
        'TIPO',
        'ATIVO',
        'DUMP'
    ];

    public function relUsrIdf(){
        return $this->hasOne(User::class,'id','ID_USR');
    }

    public function relBllIdf(){
        return $this->hasMany(BillModel::class,'TIPO_CONTA');
    }
}
