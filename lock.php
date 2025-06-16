<?php
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    
    header('Location: login.php?codigo=permissao_negada'); 
    exit(); 
}
?>