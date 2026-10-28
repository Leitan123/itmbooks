<!DOCTYPE html>
<html>
<head>
    <title><?php echo isset($heading) ? $heading : '404 Page Not Found'; ?></title>
</head>
<body>
    <h1><?php echo isset($heading) ? $heading : '404 Page Not Found'; ?></h1>
    <p><?php echo isset($message) ? $message : 'The page you are looking for does not exist.'; ?></p>
</body>
</html>
