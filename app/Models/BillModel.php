<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillModel extends Model {
    use HasFactory;
    protected $table = 'bills';
    protected $fillable = [
        'TIPO_CONTA',
        'ID_USR',
        'DESCRICAO',
        'VALOR',
        'VENCIMENTO',
        'PARCELAS',
        'STATUS',
        'RECRIAR',
        'DUMP',
        'PAGO_EM',
    ];

    public function relUsrBll() {
        return $this->hasOne(User::class, 'id', 'ID_USR');
    }

    public function relIdfBll() {
        return $this->hasOne(IdentifierModel::class,'id', 'TIPO_CONTA');
    }
}
