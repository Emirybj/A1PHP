<?php
require_once 'lock.php';
require_once  'conexao.php';

$mensagem = ''; //armazena mensagens de feedback do usuário.
$titulo_preencher = '';
$descricao_preencher = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = htmlspecialchars(trim($_POST['titulo'] ?? ''));
    $descricao = htmlspecialchars(trim($_POST['descricao'] ?? ''));

    $usuario_id = $_SESSION['usuario_id'] ?? null;

    if (empty($titulo) || empty($descricao)) {
        $mensagem = '<div class="alert-danger" role="alert">Erro: ID do usuário não encontrado na sessão. Por Favor, faça login novamente.</div>';
        $titulo_preencher = $titulo; // preenche o formulario de novo em caso de erro
        $descricao_preencher = $descricao;
    } elseif (empty($usuario_id)) {
        // se lock.php funcionar direito nao vai acontecer (e apenas uma segurança)
        $mensagem = '<div class="alert alert-danger" role="alert">Erro: ID do usuário não encontrado na sessão. Por favor, faça login novamente.</div>';
    } else {
        //prepara para inserir nova musica e '?' vao ser os valores vinculados
        $stmt = $mysqli->prepare("INSERT INTO musicas (titulo, descricao, usuario_id) VALUES (?, ?, ?)");

        if ($stmt) {
            $stmt->bind_param("ssi", $titulo, $descricao, $usuario_id);

            if ($stmt->execute()) {
                $mensagem = 'div class="alert alert-sucess" role="alert">Música "'. htmlspecialchars($titulo) . '"adicionada com sucesso!</div>'; 
                // vai limpar os campos do formulario se der certo.
                $titulo_preencher = '';
                $descricao_preencher = '';
            } else {
                $mensagem = '<div class="alert alert-danger" role="alert">Erro ao adicionar música: ' . $stmt->error . '</div>';
            }
            $stmt->close();
        } else {
            $mensagem = '<div class="alert alert-danger" role="alert">Erro na preparação da query: ' . $mysqli->error . '</div>';
        
        }
    }
}

$mysqli->close();
?>