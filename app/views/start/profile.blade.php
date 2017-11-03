<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <script src="../assets/app/js/jquery-2.1.4.min.js"></script>
    <script src="../assets/app/js/myscript.js"></script>
</head>
<body>
<div id="website_info" data-website-url="<?php echo $GLOBALS['website_url']?>"></div>
<form id="answer">
    <p>Question ID :<br>
        <input type="number" id="qid" name="qid" required>
    </p>
    <p>
        Answer ID :<br>
        <input type="number" id="aid" name="aid"  required>
    </p>
    <br><br>
    <input id="check" type="button" value="Submit">
</form>
<p id="status"></p>

</body>
</html>
