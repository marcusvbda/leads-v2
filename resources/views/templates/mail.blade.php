<!DOCTYPE html>
<html lang="pt-br" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title></title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,400,700,700i" rel="stylesheet">
    <style>
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
        }
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }
        div[style*="margin: 16px 0"] {
            margin: 0 !important;
        }
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }
        table table table {
            table-layout: auto;
        }
        a {
            text-decoration: none;
            color: #1B4E01;
            font-weight: 700;
        }
        img {
            -ms-interpolation-mode:bicubic;
        }
        *[x-apple-data-detectors],  /* iOS */
        .unstyle-auto-detected-links *,
        .aBn {
            border-bottom: 0 !important;
            cursor: default !important;
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }
        .im {
            color: inherit !important;
        }
        .a6S {
           display: none !important;
           opacity: 0.01 !important;
		}
		img.g-img + div {
		   display: none !important;
		}
        @media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
            u ~ div .email-container {
                min-width: 320px !important;
            }
        }
        @media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
            u ~ div .email-container {
                min-width: 375px !important;
            }
        }
        @media only screen and (min-device-width: 414px) {
            u ~ div .email-container {
                min-width: 414px !important;
            }
        }

    </style>
    <style>
        .button-td,
        .button-a {
            transition: all 100ms ease-in;
        }
	    .button-td-primary:hover,
	    .button-a-primary:hover {
	        background: #555555 !important;
	        border-color: #555555 !important;
	    }
        @media screen and (max-width: 600px) {

            .email-container {
                width: 100% !important;
                margin: auto !important;
            }
            .fluid {
                max-width: 100% !important;
                height: auto !important;
                margin-left: auto !important;
                margin-right: auto !important;
            }
            .stack-column,
            .stack-column-center {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                direction: ltr !important;
            }
            .stack-column-center {
                text-align: center !important;
            }

            .center-on-narrow {
                text-align: center !important;
                display: block !important;
                margin-left: auto !important;
                margin-right: auto !important;
                float: none !important;
            }
            table.center-on-narrow {
                display: inline-block !important;
            }
            .email-container p {
                font-size: 14px !important;
            }
        }

    </style>

</head>

<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #ffffff; font-family: 'Roboto', sans-serif;">
	<center style="width: 100%; background-color: #ffffff;">
        <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="margin: 0 auto;" class="email-container">
            <tr>
                <td style="padding: 18px 0; text-align: center; background:#0074d9;">
                    <b style="color : white">{{ config('app.name') }}</b>
                </td>
            </tr>
            @yield("content")
            <tr>
                <td style="background-color: #F2F2F2; padding: 4px 14px 0 14px;">
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                            <td style="font-weight: 400; text-align: left; font-size: 10px; color: #313131;line-height: 20px;">
                                <p style="">A {{ config('app.name') }} respeita sua privacidade. Você está recebendo esse e-mail exclusivamente.</p>
                            </td>
                        </tr>
                        {{-- <tr>
                            <td style="font-weight: 400; text-align: left; font-size: 10px; color: #313131; line-height: 20px;">
                                <p style="color: #313131;"><a href="#" style="text-decoration: underline; color: #313131;">FAQ</a> <span style="font-size: 14px;">|</span> <a href="#" style="text-decoration: underline; color: #313131;">Politica de privacidade</a></p>
                            </td>
                        </tr> --}}
                    </table>
                </td>
            </tr>
	    </table>
    </center>
</body>
</html>
