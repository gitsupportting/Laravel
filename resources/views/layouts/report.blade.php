<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <style>
        table, th, td {border-bottom: 1px solid LightGray;}
        th.rotate {
            /* Something you can count on */
            height: 140px;
            white-space: nowrap;
        }

        th.rotate > div {
            transform:
                /* Magic Numbers */
                translate(25px, 51px)
                    /* 45 is really 360 - 45 */
                rotate(315deg);
            width: 30px;
        }
        th.rotate > div > span {
            border-bottom: 1px solid #ccc;
            padding: 5px 10px;
        }
    </style>
</head>
<body>
<div id="DivIdToPrint" style="color:#343434;font-family: Arial, Helvetica, sans-serif;font-size: 12px; font-weight: 400;">
    @yield('content')
    <p style="text-align: center">Printed: {{now()->format('d/m/Y')}}</p>
</div>
<script>
    window.print();
    setTimeout(function () {
        window.close();
    }, 2000);
</script>
</body>
</html>
