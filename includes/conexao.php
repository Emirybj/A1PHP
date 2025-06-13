<?php 
function conectar_banco() {

    $servidor   = 'localhost';
    $usuario    = 'root';
    $senha      = '';
    $banco      = 'bd_playlist_music';
    $porta      = '3306';
    
    $conn = mysqli_connect($servidor, $usuario, $senha, $banco, $porta);

    if (!$conn) {
        exit("Erro na conexão: " . mysqli_connect_error());
    }

    if (!mysqli_set_charset($conn, "utf8mb4")) {
        exit("Erro ao definir charset: " . mysqli_error($conn));
    }

    return $conn;
}
?>
