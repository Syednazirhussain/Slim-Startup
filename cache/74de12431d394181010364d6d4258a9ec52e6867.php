<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <script src="../assets/app/js/jquery-2.1.4.min.js"></script>
    <script src="../assets/app/js/myscript.js"></script>
</head>
<body>


<h1>Login As <?php echo e($result[0]->username); ?></h1>
<ul>
    <li>COOKIES SESSIONID : <?php echo e($SESSIONID); ?></li>
</ul>
<ol>
<?php $__currentLoopData = $result[0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
    <li><?php echo e($key); ?> = <?php echo e($value); ?></li>
<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
</ol>

<a href=<?php echo $GLOBALS['website_url']."/logout" ?> >Logout</a>

</body>
</html>