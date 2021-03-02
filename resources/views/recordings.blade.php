<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script>
        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
                callback(xmlHttp.responseText);
        }
        xmlHttp.setRequestHeader('Authorization', 'Bearer' + {{ $token }});
        xmlHttp.open("GET", '/api/get-recordings', true); // true for asynchronous
        xmlHttp.send(null);
    </script>
</head>
    <body>
    </body>
</html>
