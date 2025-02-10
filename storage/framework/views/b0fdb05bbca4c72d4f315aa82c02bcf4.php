<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>HandyFix</title>
    <link rel="icon" href="<?php echo e(asset('adminpanel/dist/img/logo.ico')); ?>" type="image/x-icon">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>
</head>

<body>
<div id="root">
    <?php echo $__env->yieldContent('content'); ?>
</div>
</body>
</html>
<?php /**PATH D:\DevTools\OpenServer5\OSPanel\domains\HandyFix\resources\views/layouts/main.blade.php ENDPATH**/ ?>