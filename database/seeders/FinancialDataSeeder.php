<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Identifier;
use App\Models\Bill;
use App\Models\Income;
use App\Models\Fund;
use App\Models\FundHistory;
use App\Models\Investiment;
use App\Models\InvestimentHistory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinancialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Criar Identificadores (Categorias) Globais se não existirem
        $identifiersData = [
            // Receitas (Tipo 0)
            ['IDENTIF' => 'Salário', 'TIPO_IDENTIFICADOR' => '0', 'ID_HEX' => '#10B981'],
            ['IDENTIF' => 'Freelance', 'TIPO_IDENTIFICADOR' => '0', 'ID_HEX' => '#8B5CF6'],
            ['IDENTIF' => 'Rendimentos', 'TIPO_IDENTIFICADOR' => '0', 'ID_HEX' => '#3B82F6'],
            ['IDENTIF' => 'Vendas', 'TIPO_IDENTIFICADOR' => '0', 'ID_HEX' => '#F59E0B'],

            // Gastos (Tipo 1)
            ['IDENTIF' => 'Habitação', 'TIPO_IDENTIFICADOR' => '1', 'ID_HEX' => '#EF4444'],
            ['IDENTIF' => 'Alimentação', 'TIPO_IDENTIFICADOR' => '1', 'ID_HEX' => '#F97316'],
            ['IDENTIF' => 'Transporte', 'TIPO_IDENTIFICADOR' => '1', 'ID_HEX' => '#84CC16'],
            ['IDENTIF' => 'Saúde', 'TIPO_IDENTIFICADOR' => '1', 'ID_HEX' => '#06B6D4'],
            ['IDENTIF' => 'Educação', 'TIPO_IDENTIFICADOR' => '1', 'ID_HEX' => '#6366F1'],
            ['IDENTIF' => 'Lazer', 'TIPO_IDENTIFICADOR' => '1', 'ID_HEX' => '#EC4899'],
            ['IDENTIF' => 'Assinaturas', 'TIPO_IDENTIFICADOR' => '1', 'ID_HEX' => '#64748B'],
            ['IDENTIF' => 'Outros', 'TIPO_IDENTIFICADOR' => '1', 'ID_HEX' => '#94A3B8'],

            // Reservas (Tipo 2)
            ['IDENTIF' => 'Reserva de Emergência', 'TIPO_IDENTIFICADOR' => '2', 'ID_HEX' => '#0EA5E9'],
            ['IDENTIF' => 'Viagem', 'TIPO_IDENTIFICADOR' => '2', 'ID_HEX' => '#D946EF'],
            ['IDENTIF' => 'Projetos', 'TIPO_IDENTIFICADOR' => '2', 'ID_HEX' => '#14B8A6'],

            // Investimentos (Tipo 3)
            ['IDENTIF' => 'Tesouro Direto', 'TIPO_IDENTIFICADOR' => '3', 'ID_HEX' => '#10B981'],
            ['IDENTIF' => 'Ações', 'TIPO_IDENTIFICADOR' => '3', 'ID_HEX' => '#F59E0B'],
            ['IDENTIF' => 'Fundos Imobiliários', 'TIPO_IDENTIFICADOR' => '3', 'ID_HEX' => '#3B82F6'],
            ['IDENTIF' => 'Criptomoedas', 'TIPO_IDENTIFICADOR' => '3', 'ID_HEX' => '#8B5CF6'],
        ];

        $createdIdentifiers = [];
        foreach ($identifiersData as $data) {
            $createdIdentifiers[$data['IDENTIF']] = Identifier::firstOrCreate(
                ['IDENTIF' => $data['IDENTIF'], 'TIPO_IDENTIFICADOR' => $data['TIPO_IDENTIFICADOR']],
                ['ID_HEX' => $data['ID_HEX']]
            );
        }

        // Obter todos os usuários para popular
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('Nenhum usuário encontrado no banco. Criando usuários padrão...');
            $adminUser = User::factory()->create([
                'name' => 'Felipe Admin',
                'email' => 'felipeadmin@example.com',
            ]);
            $users = collect([$adminUser]);
        }

        // Definir a janela de tempo de 6 anos (Janeiro/2020 até Junho/2026)
        $startDate = Carbon::create(2020, 1, 1)->startOfDay();
        $endDate = Carbon::create(2026, 6, 30)->endOfDay();
        $today = Carbon::now();

        foreach ($users as $user) {
            $this->command->info("Gerando histórico de 6 anos para: {$user->email}");

            DB::transaction(function () use ($user, $startDate, $endDate, $today, $createdIdentifiers) {
                // --- LIMPAR REGISTROS ANTIGOS ---
                Bill::where('ID_USR', $user->id)->forceDelete();
                Income::where('ID_USR', $user->id)->forceDelete();
                FundHistory::where('ID_USR', $user->id)->forceDelete();
                Fund::where('ID_USR', $user->id)->forceDelete();
                InvestimentHistory::where('ID_USR', $user->id)->forceDelete();
                Investiment::where('ID_USR', $user->id)->forceDelete();

                // --- 2. GERAR RECEBIMENTOS (INCOME) ---
                $currentMonth = clone $startDate;
                while ($currentMonth->lte($endDate)) {
                    if ($currentMonth->lte($today)) {
                        // Salário com progressão de carreira
                        $year = $currentMonth->year;
                        if ($year === 2020 || $year === 2021) {
                            $salary = 4500.00;
                        } elseif ($year === 2022 || $year === 2023) {
                            $salary = 6000.00;
                        } elseif ($year === 2024) {
                            $salary = 8200.00;
                        } else {
                            $salary = 10500.00; // 2025 e 2026
                        }

                        // Salário Mensal
                        Income::create([
                            'ID_IDENTIF' => $createdIdentifiers['Salário']->id,
                            'ID_USR' => $user->id,
                            'DESCRICAO' => 'Salário Mensal',
                            'VALOR' => $salary,
                            'VALOR_FIXO' => true,
                            'RECEBIDO_EM' => clone $currentMonth->setDay(5),
                        ]);

                        // Rendimentos crescentes conforme o patrimônio aumenta
                        $monthsSinceStart = $startDate->diffInMonths($currentMonth);
                        $rendimento = 50.00 + ($monthsSinceStart * 3.50) + rand(-15, 15);
                        if ($rendimento < 10) $rendimento = 10;
                        Income::create([
                            'ID_IDENTIF' => $createdIdentifiers['Rendimentos']->id,
                            'ID_USR' => $user->id,
                            'DESCRICAO' => 'Rendimento de Aplicações',
                            'VALOR' => round($rendimento, 2),
                            'VALOR_FIXO' => false,
                            'RECEBIDO_EM' => clone $currentMonth->setDay(15),
                        ]);

                        // Projetos Freelance aleatórios
                        if (rand(1, 100) > 75) {
                            Income::create([
                                'ID_IDENTIF' => $createdIdentifiers['Freelance']->id,
                                'ID_USR' => $user->id,
                                'DESCRICAO' => 'Projeto Freelance Dev',
                                'VALOR' => round(rand(1000, 3500), 2),
                                'VALOR_FIXO' => false,
                                'RECEBIDO_EM' => clone $currentMonth->setDay(rand(10, 28)),
                            ]);
                        }
                    }
                    $currentMonth->addMonth();
                }

                // --- 3. GERAR CONTAS A PAGAR (BILL) ---
                $currentMonth = clone $startDate;
                while ($currentMonth->lte($endDate)) {
                    $isPast = $currentMonth->lte($today);
                    $year = $currentMonth->year;

                    // Aluguel com reajuste anual
                    if ($year === 2020 || $year === 2021) {
                        $rent = 1200.00;
                    } elseif ($year === 2022 || $year === 2023) {
                        $rent = 1500.00;
                    } elseif ($year === 2024) {
                        $rent = 1900.00;
                    } else {
                        $rent = 2400.00;
                    }

                    $billDueDate = clone $currentMonth->setDay(10);
                    Bill::create([
                        'ID_IDENTIF' => $createdIdentifiers['Habitação']->id,
                        'ID_USR' => $user->id,
                        'DESCRICAO' => 'Aluguel Residencial',
                        'VALOR' => $rent,
                        'VENCIMENTO' => $billDueDate,
                        'PARCELAS' => 0,
                        'STATUS' => $isPast ? 'Pago' : 'Pagar',
                        'RECORRENTE' => true,
                        'PAGO_EM' => $isPast ? $billDueDate : null,
                    ]);

                    // Conta de luz oscilando pelas estações do ano
                    $luzBase = 120.00;
                    if (in_array($currentMonth->month, [12, 1, 2, 3])) {
                        $luzBase = 220.00; // Ar condicionado no verão
                    }
                    $luzDueDate = clone $currentMonth->setDay(14);
                    Bill::create([
                        'ID_IDENTIF' => $createdIdentifiers['Habitação']->id,
                        'ID_USR' => $user->id,
                        'DESCRICAO' => 'Conta de Luz (Equatorial)',
                        'VALOR' => round($luzBase + rand(-30, 40), 2),
                        'VENCIMENTO' => $luzDueDate,
                        'PARCELAS' => 0,
                        'STATUS' => $isPast ? 'Pago' : 'Pagar',
                        'RECORRENTE' => true,
                        'PAGO_EM' => $isPast ? $luzDueDate : null,
                    ]);

                    // Internet & Assinaturas
                    $internetDueDate = clone $currentMonth->setDay(20);
                    Bill::create([
                        'ID_IDENTIF' => $createdIdentifiers['Assinaturas']->id,
                        'ID_USR' => $user->id,
                        'DESCRICAO' => 'Plano Internet Banda Larga',
                        'VALOR' => 129.90,
                        'VENCIMENTO' => $internetDueDate,
                        'PARCELAS' => 0,
                        'STATUS' => $isPast ? 'Pago' : 'Pagar',
                        'RECORRENTE' => true,
                        'PAGO_EM' => $isPast ? $internetDueDate : null,
                    ]);

                    // Supermercado
                    $supDueDate = clone $currentMonth->setDay(15);
                    Bill::create([
                        'ID_IDENTIF' => $createdIdentifiers['Alimentação']->id,
                        'ID_USR' => $user->id,
                        'DESCRICAO' => 'Supermercado Mensal',
                        'VALOR' => round(500.00 + ($startDate->diffInMonths($currentMonth) * 4.5) + rand(-100, 150), 2),
                        'VENCIMENTO' => $supDueDate,
                        'PARCELAS' => 0,
                        'STATUS' => $isPast ? 'Pago' : 'Pagar',
                        'RECORRENTE' => false,
                        'PAGO_EM' => $isPast ? $supDueDate : null,
                    ]);

                    // Combustível
                    $gasDueDate = clone $currentMonth->setDay(25);
                    Bill::create([
                        'ID_IDENTIF' => $createdIdentifiers['Transporte']->id,
                        'ID_USR' => $user->id,
                        'DESCRICAO' => 'Combustível Carro',
                        'VALOR' => round(250.00 + rand(-50, 80), 2),
                        'VENCIMENTO' => $gasDueDate,
                        'PARCELAS' => 0,
                        'STATUS' => $isPast ? 'Pago' : 'Pagar',
                        'RECORRENTE' => false,
                        'PAGO_EM' => $isPast ? $gasDueDate : null,
                    ]);

                    // Lazer
                    $lazerDueDate = clone $currentMonth->setDay(28);
                    Bill::create([
                        'ID_IDENTIF' => $createdIdentifiers['Lazer']->id,
                        'ID_USR' => $user->id,
                        'DESCRICAO' => 'Restaurantes e Passeios',
                        'VALOR' => round(180.00 + rand(-60, 200), 2),
                        'VENCIMENTO' => $lazerDueDate,
                        'PARCELAS' => 0,
                        'STATUS' => $isPast ? 'Pago' : 'Pagar',
                        'RECORRENTE' => false,
                        'PAGO_EM' => $isPast ? $lazerDueDate : null,
                    ]);

                    // Saúde (Farmácia) - Meses pares
                    if ($currentMonth->month % 2 === 0) {
                        $saudeDueDate = clone $currentMonth->setDay(18);
                        Bill::create([
                            'ID_IDENTIF' => $createdIdentifiers['Saúde']->id,
                            'ID_USR' => $user->id,
                            'DESCRICAO' => 'Farmácia / Cuidados Pessoais',
                            'VALOR' => round(rand(40, 150), 2),
                            'VENCIMENTO' => $saudeDueDate,
                            'PARCELAS' => 0,
                            'STATUS' => $isPast ? 'Pago' : 'Pagar',
                            'RECORRENTE' => false,
                            'PAGO_EM' => $isPast ? $saudeDueDate : null,
                        ]);
                    }

                    $currentMonth->addMonth();
                }

                // --- 4. GERAR RESERVAS (FUNDS & HISTORIES) ---
                $fundsList = [
                    [
                        'identif' => 'Reserva de Emergência',
                        'desc' => 'Reserva Estratégica Pessoal',
                        'meta' => 30000.00,
                        'monthly_save' => 450.00,
                        'withdrawals' => [
                            ['date' => '2021-08-15', 'amount' => 4000.00, 'desc' => 'Manutenção Urgente de Veículo'],
                            ['date' => '2023-11-20', 'amount' => 7500.00, 'desc' => 'Tratamento de Saúde Familiar'],
                            ['date' => '2025-05-12', 'amount' => 3000.00, 'desc' => 'Conserto de Eletrodomésticos'],
                        ]
                    ],
                    [
                        'identif' => 'Viagem',
                        'desc' => 'Viagem dos Sonhos Europa',
                        'meta' => 25000.00,
                        'monthly_save' => 300.00,
                        'withdrawals' => [
                            ['date' => '2022-07-10', 'amount' => 6000.00, 'desc' => 'Férias Nordeste'],
                            ['date' => '2024-09-18', 'amount' => 12000.00, 'desc' => 'Eurotrip Parte 1'],
                        ]
                    ],
                ];

                foreach ($fundsList as $fData) {
                    $fund = Fund::create([
                        'ID_IDENTIF' => $createdIdentifiers[$fData['identif']]->id,
                        'ID_USR' => $user->id,
                        'DESCRICAO' => $fData['desc'],
                        'VALOR' => 0.00,
                        'META' => $fData['meta'],
                    ]);

                    $totalFundValue = 0.00;

                    // Simular aportes mensais de janeiro de 2020 até o presente
                    $currentMonth = clone $startDate;
                    while ($currentMonth->lte($today)) {
                        $saveDate = clone $currentMonth->setDay(8);
                        
                        // Aporte mensal com leve variação
                        $aport = $fData['monthly_save'] + rand(-50, 100);
                        $totalFundValue += $aport;
                        FundHistory::create([
                            'ID_FUND' => $fund->id,
                            'ID_USR' => $user->id,
                            'VALOR' => round($aport, 2),
                            'TIPO_OPERACAO' => 'GUARDAR',
                            'DATA_OPERACAO' => $saveDate,
                        ]);

                        // Verificar se houve retirada planejada nesta data
                        foreach ($fData['withdrawals'] as $wDrawal) {
                            $wDate = Carbon::parse($wDrawal['date']);
                            if ($wDate->year === $currentMonth->year && $wDate->month === $currentMonth->month) {
                                $totalFundValue -= $wDrawal['amount'];
                                FundHistory::create([
                                    'ID_FUND' => $fund->id,
                                    'ID_USR' => $user->id,
                                    'VALOR' => $wDrawal['amount'],
                                    'TIPO_OPERACAO' => 'RETIRAR',
                                    'DATA_OPERACAO' => $wDate,
                                ]);
                            }
                        }

                        $currentMonth->addMonth();
                    }

                    // Atualiza o saldo real no fundo
                    $fund->update(['VALOR' => round($totalFundValue, 2)]);
                }

                // --- 5. GERAR INVESTIMENTOS (INVESTIMENTS & HISTORIES) ---
                $investmentsList = [
                    [
                        'identif' => 'Tesouro Direto',
                        'desc' => 'Tesouro IPCA+ Aposentadoria',
                        'monthly_invest' => 350.00,
                        'withdrawals' => []
                    ],
                    [
                        'identif' => 'Ações',
                        'desc' => 'Ações Diversas Ibovespa',
                        'monthly_invest' => 300.00,
                        'withdrawals' => [
                            ['date' => '2023-03-12', 'amount' => 5000.00],
                            ['date' => '2025-09-25', 'amount' => 8000.00],
                        ]
                    ],
                    [
                        'identif' => 'Fundos Imobiliários',
                        'desc' => 'FIIs Imobiliários de Tijolo',
                        'monthly_invest' => 250.00,
                        'withdrawals' => [
                            ['date' => '2024-06-15', 'amount' => 3000.00]
                        ]
                    ],
                ];

                foreach ($investmentsList as $iData) {
                    $investiment = Investiment::create([
                        'ID_IDENTIF' => $createdIdentifiers[$iData['identif']]->id,
                        'ID_USR' => $user->id,
                        'DESCRICAO' => $iData['desc'],
                        'VALOR' => 0.00,
                    ]);

                    $totalInvestValue = 0.00;

                    // Simular aplicações mensais de janeiro de 2020 até o presente
                    $currentMonth = clone $startDate;
                    while ($currentMonth->lte($today)) {
                        $investDate = clone $currentMonth->setDay(22);
                        
                        // Aplicação mensal com variação baseada em bonificações
                        $invest = $iData['monthly_invest'] + rand(-40, 150);
                        $totalInvestValue += $invest;
                        InvestimentHistory::create([
                            'ID_INVESTIMENT' => $investiment->id,
                            'ID_USR' => $user->id,
                            'VALOR' => round($invest, 2),
                            'TIPO_OPERACAO' => 'APLICAR',
                            'DATA_OPERACAO' => $investDate,
                        ]);

                        // Verificar se houve resgate nesta data
                        foreach ($iData['withdrawals'] as $wDrawal) {
                            $wDate = Carbon::parse($wDrawal['date']);
                            if ($wDate->year === $currentMonth->year && $wDate->month === $currentMonth->month) {
                                $totalInvestValue -= $wDrawal['amount'];
                                InvestimentHistory::create([
                                    'ID_INVESTIMENT' => $investiment->id,
                                    'ID_USR' => $user->id,
                                    'VALOR' => $wDrawal['amount'],
                                    'TIPO_OPERACAO' => 'RESGATAR',
                                    'DATA_OPERACAO' => $wDate,
                                ]);
                            }
                        }

                        $currentMonth->addMonth();
                    }

                    // Atualiza o saldo real no investimento
                    $investiment->update(['VALOR' => round($totalInvestValue, 2)]);
                }
            });
        }

        $this->command->info('Dados financeiros com histórico de 6 anos semeados com sucesso.');
    }
}
