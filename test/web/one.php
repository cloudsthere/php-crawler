<?php

session_start();

echo "one";

if($_POST['hello'] == 'beauty')
    $_SESSION['love'] = 'sex';

var_dump($_SESSION['love']);
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