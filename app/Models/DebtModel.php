<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebtModel extends Model
{
    use HasFactory;
    protected $table = 'debt';
    protected $fillable = [
        'TIPO_CONTA',
        'ID_USR',
        'NOME',
        'DESCRICAO',
        'VALOR',
        'EMAIL',
        'VENCIMENTO',
        'PARCELAS',
        'STATUS',
        'RECRIAR',
        'DUMP',
        'CRIADO_EM',
        'PAGO_EM',
    ];

    public function relUsrDbt() {
        return $this->hasOne(User::class, 'id', 'ID_USR');
    }

    public function relIdfDbt() {
        return $this->hasOne(IdentifierModel::class,'id', 'TIPO_CONTA');
    }
}
