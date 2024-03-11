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
                    <h3 style="font-size: 26px; margin:0 0 20px 0; font-family:Arial; margin-top: 6px;">E-mail de
                        vencimentos</h3>
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
                    <p style="font-size: 16px; margin:0 0 20px 0;color: #FF0000; font-family:Arial;">Não se esqueça
                        de pagar suas contas</p>
                    <p style="font-size: 16px; margin:0 0 20px 0;color: #000000; font-family:Arial;">Essas são as contas
                        que vão
                        vencer:</p>
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
                                    Vencimento</th>
                                <th
                                    style="border: 1px solid #dddddd;padding: 8px;background-color: #212529; color: #fff;text-align:center;">
                                    Dias até o vencimento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bills as $bill)
                                <tr>
                                    <td style="border: 1px solid #dddddd;color: #000000;text-align: left;padding: 8px;">
                                        {{ $bill->DESCRICAO }}</td>
                                    <td style="border: 1px solid #dddddd;color: #000000;text-align: left;padding: 8px;">
                                        R$ {{ $bill->VALOR }}</td>
                                    <td style="border: 1px solid #dddddd;color: #000000;text-align: left;padding: 8px;">
                                        {{ $bill->VENCIMENTO }}</td>
                                    <td style="border: 1px solid #dddddd;color: #000000;text-align: left;padding: 8px;">
                                            {{ $bill->Dias }} Dias
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
