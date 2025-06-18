<?php
session_start();
session_unset();
session_destroy();

header("Location: ../../pages/user/login.php");
exit();
