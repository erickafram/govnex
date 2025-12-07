<?php
session_start();
session_destroy();
header('Location: /govnex/login_usuario.php');
exit;
