<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <script src="../assets/app/js/jquery-2.1.4.min.js"></script>
    <script src="../assets/app/js/myscript.js"></script>
</head>
<body>
<h1>Login As {{ $result[0]->username }}</h1>
<ul>
    <li>COOKIES SESSIONID : {{ $SESSIONID }}</li>
</ul>
<ol>
@foreach ($result[0] as $key => $value)
    <li>{{ $key }} = {{ $value }}</li>
@endforeach
</ol>

<a href=<?php echo $GLOBALS['website_url']."/logout" ?> >Logout</a>

</body>
</html>