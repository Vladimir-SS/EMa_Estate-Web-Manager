<?php
if ($exception->getCode() == 401 || $exception->getCode() == 403) {
    unset($_COOKIE['user']);
    setcookie('user', null, -1, '/');
}
?>

<h2><?php echo $exception->getCode() ?> - <?php echo $exception->getMessage() ?></h2>