<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="/css/materialize.css" rel="stylesheet">
</head>
    <body>
        <h1>Reset password</h1>

        <form action = "/api/auth/reset" method = "post">
            <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">

            <table>
                <tr>
                    <td>E-mail</td>
                    <td><input type = "text" name = "email" /></td>
                </tr>
                <tr>
                    <td colspan = "2" align = "center">
                        <input type = "submit" value = "Reset" />
                    </td>
                </tr>
            </table>
        </form>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="red-text">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </body>
</html>
