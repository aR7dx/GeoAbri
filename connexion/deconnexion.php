<?php
session_start();
$_SESSION['connected'] = 0;
unset($_SESSION['notif_disconnected_shown']);
header('Location: /');
exit;
?>