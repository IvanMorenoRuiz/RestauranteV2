<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/login.less">
    <script src="./js/loginVal.js"></script>
    <title>Login</title>
</head>
<body>
    <div class="body-BackGround"></div>
<div class="login">
	<h1>OASIS 23</h1>
    <form action="./proc/proclogin.php" method="post">
        <input type="text" name="user" id="user" placeholder="Insertar usuario" onblur="validarUsername(this.value)">
        <span id="errorUser" class="mensajeError"></span>
        <br>
        <br>
        <input type="password" name="pwd" id="pwd" placeholder="Insertar password" onblur="validarPassword(this.value)">
        <span id="errorPwd" class="mensajeError"></span>
        <br>
        <br>
        <?php if (isset($_GET['fallo'])) {
        echo "<p class='errorlogin error-login'> Usuario o Contraseña incorrecta </p>";
        }?>
        <button type="submit" class="btn btn-primary btn-block btn-large" id="loginBtn" name="login" value="Login">Login</button>
    </form>
</div>
</body>
</html>