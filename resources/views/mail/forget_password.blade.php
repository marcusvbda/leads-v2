@extends('templates.mail')
@section("content")

<tr>
    <td style="padding: 0 14px 20px 14px;">
        <table style="width:100%;">
            <tr>

                <td>
                    <table style="width:100%;">

                        <tr>
                            <td style="font-size:14px; padding:10px 0px; width:100%">
                                <p>Olá {{$user->name}},</p>
								<p>Esqueceu sua senha ? Sem problemas! </p>
								<p>Clique no link abaixo e renove-a</p>
								<p style='margin-top:30px'>Obrigado, {{$appName}}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 0;"></td>
                        </tr>
                    </table>
                </td>
            </tr>

        </table>
    </td>
</tr>
<tr>
    <td style="text-align:center;height: 100px;">
        <a href="{{$link}}" style="background-color: #0074d9;
        color: white;
        padding: 20px;" target="_BLANK">Renovar a senha</a>
    </td>
</tr>

@endsection