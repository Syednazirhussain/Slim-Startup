<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{$root}}</title>
    <script src="../assets/app/js/jquery-2.1.4.min.js"></script>
    <script src="../assets/app/js/myscript.js"></script>
</head>
<body>

<div id="website_info" data-website-url="<?php echo $GLOBALS['website_url']?>"></div>
<form id="login">
    <p>User Name :<br>
    <input type="text" id="username" name="username" required>
    <span id="email"></span>
    </p>
    <p>
    Password :<br>
    <input type="password" id="password" name="password" required>
    <span id="pass"></span>
    </p>
    <br><br>
    <input id="submit" type="button" value="Submit">
</form>
<p id="status"></p>

</body>
</html>
