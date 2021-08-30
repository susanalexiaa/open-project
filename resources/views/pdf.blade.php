<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        @font-face {
            font-family: "DejaVu Sans";
            font-style: normal;
            font-weight: 400;
            src: url("/fonts/dejavu-sans/DejaVuSans.ttf");
            /* IE9 Compat Modes */
            src: 
                local("DejaVu Sans"), 
                local("DejaVu Sans"), 
                url("/fonts/dejavu-sans/DejaVuSans.ttf") format("truetype");
        }

        body { 
            font-family: "DejaVu Sans";
            font-size: 12px;
        }

        ol{
            width: 450px;
            margin: 0 auto;
            font-size: 25px;
            margin-top: 50px;
        }
        ol>li{
            margin-top: 30px;
        }
        h1{
            font-size: 40px;
        }
        .qr{
            margin-top: 65px;
        }
        .header h1{
            display: inline-block;
            margin: 40px auto;
            width: 500px;
        }
    </style>
</head>
<body>
    <div class="qr" style="text-align:center;">
        <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(300)->generate($path)) !!} ">
    </div>
    
    <div class="header">
        <h1 style="text-align:center">
            Сканируйте QR-код
            с телефона
        </h1>
    </div>

    <ol>
        <li>Запустите камеру на телефоне</li>
        <li>Наведите объектив на код</li>
        <li>Откройте предлагаемый сайт</li>
        <li>Заполните форму</li>
        <li>Нажмите кнопку "Отправить"</li>
    </ol>
    
</body>
</html>