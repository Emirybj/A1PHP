<?php
session_start();

if (!isset($_SESSION['usuario']) || !isset($_SESSION['senha'])) {
    header('location:index.php?codigo=0'); //Se o usuário não estiver logado ele é redirecionado para a página inicial.
    exit;
}

?>