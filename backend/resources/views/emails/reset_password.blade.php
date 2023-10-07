<!DOCTYPE html>
<html>
<head>
    <title>Restablecimiento de contraseña</title>
</head>
<body>
    <p>Hola {{ $name }},</p>
    <p>Haz clic en el siguiente enlace para restablecer tu contraseña:</p>
    <p><a href="{{ 'http://127.0.0.1:5173/reset-password/' . $token }}">Restablecer contraseña</a></p>
    <p>Si no solicitaste un restablecimiento de contraseña, puedes ignorar este mensaje.</p>
    <p>Gracias</p>
</body>
</html>
