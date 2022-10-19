<?php
session_start();
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = "us";
}
require '../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);

echo $twig->render('index.twig', ['session' => $_SESSION]);
?>