<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width">
    <title></title>
    <style></style>
</head>

<body>
    <div id="email" style="width:600px;margin: auto;background:white;">
        <!-- Header -->
        <table role="presentation" border="0" width="100%" cellspacing="0">
            <tr>
                <td align="center" style="background: #F5F8FA;color: #28124b;">
                    <img alt="BillReminder" src="{{ url('img/u906dbwokgrlsro6r14.png') }}" width="400px"
                        style=" margin-top: 6px;">
                    <h3 style="font-size: 26px; margin:0 0 20px 0; font-family:Arial; margin-top: 6px;">E-mail de gastos
                    </h3>
            </tr>
            </td>
        </table>

        <!-- Body 1 -->
        <table role="presentation" border="0" width="100%" cellspacing="0">
            <tr>
                <td style="padding: 30px 30px 30px 60px;">
                    <p style="font-size: 16px; margin:0 0 20px 0;color: #000000; font-family:Arial;">Olá
                        <strong>{{ $user->name }}</strong>!
                    </p>
                    @if ($user->TIPO_NOTIF_GASTO == 'Quinzenal')
                        <p style="font-size: 16px; margin:0 0 20px 0;color: #000000; font-family:Arial;">Aqui estão seus
                            gastos quinzenais: </p>
                    @elseif($user->TIPO_NOTIF_GASTO == 'Mensal')
                        <p style="font-size: 16px; margin:0 0 20px 0;color: #000000; font-family:Arial;">Aqui estão seus
                            gastos do último mês: </p>
                    @endif
                    <table width="100%" style="border-collapse: collapse;font-family:Arial;">
                        <thead>
                            <tr>
                                <th
                                    style="border: 1px solid #dddddd;padding: 8px;background-color: #212529; color: #fff;text-align:center;">
                                    Descrição</th>
                                <th
                                    style="border: 1px solid #dddddd;padding: 8px;background-color: #212529; color: #fff;text-align:center;">
                                    Valor</th>
                                <th
                                    style="border: 1px solid #dddddd;padding: 8px;background-color: #212529; color: #fff;text-align:center;">
                                    Data de pagamento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allExpenses as $expense)
                                <tr>
                                    <td style="border: 1px solid #dddddd;color: #000000;text-align: left;padding: 8px;">
                                        {{ $expense->DESCRICAO }}</td>
                                    <td style="border: 1px solid #dddddd;color: #000000;text-align: left;padding: 8px;">
                                        R$ {{ $expense->VALOR }}</td>
                                    <td style="border: 1px solid #dddddd;color: #000000;text-align: left;padding: 8px;">
                                        {{ $expense->PAGO_EM }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    @if ($user->TIPO_NOTIF_GASTO == 'Quinzenal')
                    <p style="font-size: 16px; margin:0 0 30px 0;color: #000000; font-family:Arial;">Seu gasto total
                        dos últimos 15 dias foi de <strong>R$ {{ $totalExpense }}</strong>.</p>
                    @elseif($user->TIPO_NOTIF_GASTO == 'Mensal')
                        <p style="font-size: 16px; margin:0 0 30px 0;color: #000000; font-family:Arial;">Seu gasto total
                            do mês foi de <strong>R$ {{ $totalExpense }}</strong>.</p>
                    @endif
                </td>
            </tr>
        </table>


        <table role="presentation" border="0" width="100%" cellspacing="0">
            <tr>
                <td bgcolor="#F5F8FA" style="padding: 30px 30px;">
                    <a href="{{ route('menu') }}"
                        style="font-size: 12px; text-transform:uppercase; letter-spacing: 1px; color: #28124b;  font-family:Arial;">
                        Seguir para o portal </a>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
