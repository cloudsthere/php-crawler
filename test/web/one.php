<?php

session_start();

echo "one";

$_SESSION['test'] = 1;

echo "<pre>";

var_dump($_SESSION);

?>

<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
<a href="two.php">跳到two</a>

</body>
</html>