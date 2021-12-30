<html>

<head>
    <title>Readers</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
    <style>
        html {
            margin: 0;
            padding: 0;
        }

        body {
            background: url('bg.jpg') center center;
            margin: 0;
            padding: 0;
        }

        #container {
            height: 100%;
        }

        #button-container {
            position: absolute;
            bottom: 19px;
            text-align: center;
            width: 100%;
        }

        .button {
            background-color: #078ce5;
            border-radius: 20px 20px 0 0;
            text-decoration: none;
            padding: 20px 60px;
            color: #ffffff;
            font-family: 'Roboto', sans-serif;
        }

    </style>
    <script>
        var $_Huggy = {
            defaultCountry: '+55',
            uuid: '61572559-a9a4-4389-b35f-705c9af50cdc',
            company: '8815'
        };
        (function(i, s, o, g, r, a, m) {
            i[r] = {
                context: {
                    id: 'e942a1f9734de091b6235b3deeaa4680'
                }
            };
            a = o;
            o = s.createElement(o);
            o.async = 1;
            o.src = g;
            m = s.getElementsByTagName(a)[0];
            m.parentNode.insertBefore(o, m);
        })(window, document, 'script', 'https://js.huggy.chat/widget.min.js', 'pwz');

        function handleChat() {
            Huggy.startTrigger(23247, 0, "Gerenciar a plataforma");
        }
    </script>
    <!-- End code Huggy.chat  //-->
</head>

<body>
    <div id="container">
        <div id="button-container">
            <a href="javascript:handleChat();" class="button">Gerenciar a Plataforma</a>
        </div>
    </div>
</body>

</html>
