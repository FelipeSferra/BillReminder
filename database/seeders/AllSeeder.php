<?php

namespace Database\Seeders;

use App\Models\BillModel;
use App\Models\IdentifierModel;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AllSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    private $user;
    private $identifier;
    private $bill;

    public function run(): void {
        $this->user = new User();
        $this->identifier = new IdentifierModel();
        $this->bill = new BillModel();

        /*         $this->user->create([
            'name' => 'testes',
            'email' => 'felipesferra@hotmail.com',
            'password' => Hash::make(12345678),
        ]);
        */
        $userId = $this->user->select('id')->where('name', 'testes')->first();

        for ($i = 3; $i <= 20; $i++) {
            $this->identifier->create([
                'ID_USR' => $userId->id,
                'IDENTIF' => 'Boleto',
                'DESCRICAO' => 'teste ' . $i,
                'ATIVO' => 'Sim'
            ]);
        }
        /* for ($i = 1; $i <= 3; $i++) {
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
                    'CRIADO_EM' => '2024-01-01 00:00:00',
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
                    'CRIADO_EM' => '2024-01-01 00:00:00',
                ]);
            }
        } */
    }
}
