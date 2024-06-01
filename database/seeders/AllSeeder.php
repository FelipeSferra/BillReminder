<?php

namespace Database\Seeders;

use App\Models\BillModel;
use App\Models\DebtModel;
use App\Models\IdentifierModel;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AllSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    private $user;
    private $identifier;
    private $bill;

    public function run(): void
    {
        $this->user = new User();
        $this->identifier = new IdentifierModel();
        $this->bill = new BillModel();

        $userId = $this->user->select('id')->where('name', 'testes')->first();

        for ($i = 1; $i <= 5; $i++) {
            DebtModel::create([
                'ID_USR' => $userId->id,
                'TIPO_CONTA' => 71,
                'NOME' => 'teste',
                'EMAIL' => 'teste@teste.com',
                'DESCRICAO' => 'Conta teste ' . $i,
                'VALOR' => 100 * ($i + 1),
                'VENCIMENTO' => '2024-03-19',
                'PARCELAS' => 3,
                'STATUS' => 'Pagar',
            ]);
        }

/*         for ($i = 1; $i <= 5; $i++) {
            $this->identifier->create([
                'ID_USR' => $userId->id,
                'IDENTIF' => 'Boleto',
                'DESCRICAO' => 'teste ' . $i,
                'ATIVO' => 'Sim'
            ]);
        } */
/*
        for ($i = 1; $i <= 5; $i++) {
            if ($i == 2) {
                $this->bill->create([
                    'TIPO_CONTA' => $i,
                    'ID_USR' => $userId->id,
                    'DESCRICAO' => 'TESTE ' . $i,
                    'VALOR' => ($i * 90),
                    'VENCIMENTO' => '2024-01-12',
                    'PARCELAS' => $i,
                    'STATUS' => 'Pago',
                    'RECRIAR' => 'Sim',
                    'PAGO_EM' => '2024-01-11'
                ]);
            } else {
                $this->bill->create([
                    'TIPO_CONTA' => $i,
                    'ID_USR' => $userId->id,
                    'DESCRICAO' => 'TESTE ' . $i,
                    'VALOR' => ($i * 90),
                    'VENCIMENTO' => '2024-01-12',
                    'PARCELAS' => $i,
                    'STATUS' => 'Pagar',
                    'RECRIAR' => 'Sim',
                ]);
            }
        } */
    }
}
