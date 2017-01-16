<?php
setcookie("loggedin", "val", time() - 3600, "/");
header("Location: index.php");
?>