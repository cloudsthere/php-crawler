<?php

session_start();

session_destroy();

echo "<pre>";
var_dump($_SESSION);

?>

<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
<a href="three.php">跳到three</a>
</body>
</html>