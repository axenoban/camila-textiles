<?php
session_start();
require_once __DIR__ . '/../config/app.php';

session_unset();
session_destroy();

header('Location: ' . BASE_URL . '/views/public/login.php');
exit;
