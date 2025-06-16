<?php
/**
 * Usado para validar se um formulário foi submetido.
 * @return bool Retorna true se o método da requisição NÃO for POST, false caso contrário.
 */
function form_nao_enviado() {
    return $_SERVER['REQUEST_METHOD'] !== 'POST';
}

/**
 * Verifica se os campos de usuário e senha estão em branco.
 * Destinada a ser usada em formulários de login.
 * @param string $usuario O valor do campo de usuário.
 * @param string $senha O valor do campo de senha.
 * @return bool Retorna true se qualquer um dos campos estiver vazio, false caso contrário.
 */
function campos_login_em_branco($usuario, $senha) {
    return empty($usuario) || empty($senha);
}

/**
 * Verifica se os campos de título e descrição da música estão em branco.
 * Destinada a ser usada em formulários de cadastro/edição de músicas.
 * @param string $titulo O valor do campo de título da música.
 * @param string $descricao O valor do campo de descrição da música.
 * @return bool Retorna true se qualquer um dos campos estiver vazio, false caso contrário.
 */
function musica_em_branco($titulo, $descricao) {
    return empty($titulo) || empty($descricao);
}

/**
 * Exibe uma mensagem de feedback ao usuário baseada em um código passado via URL.
 * (Utiliza $_GET['codigo']).
 * @param int|string|null $codigo O código numérico ou string da mensagem a ser exibida.
 */
function verificar_codigo($codigo = null) {
    // Se nenhum código for passado para a função, tenta pegar da URL
    if (is_null($codigo) && isset($_GET['codigo'])) {
        $codigo = $_GET['codigo']; // Pode ser string agora
    } elseif (is_null($codigo)) {
        return; // Não tem código para verificar, então não faz nada
    }

    $msg = "";
    $estilo_cor = ""; // Para aplicar cor simples diretamente no HTML

    switch ($codigo) {
        case 0:
            $msg = "Você não tem permissão para acessar a página requisitada. Faça login.";
            $estilo_cor = "color: red;";
            break;
        case 1:
            $msg = "Usuário ou senha inválidos. Por favor, tente novamente!";
            $estilo_cor = "color: red;";
            break;
        case 2:
            $msg = "Por favor, preencha todos os campos do formulário.";
            $estilo_cor = "color: orange;"; 
            break;
        case 3:
            $msg = "Erro interno na consulta SQL. Verifique com o suporte ou tente novamente mais tarde.";
            $estilo_cor = "color: red;";
            break;
        case 4:
            $msg = "Erro ao excluir música selecionada. Verifique com o suporte ou tente novamente mais tarde.";
            $estilo_cor = "color: red;";
            break;
        case 5:
            $msg = "Erro ao cadastrar música. Verifique com o suporte ou tente novamente mais tarde.";
            $estilo_cor = "color: red;";
            break;
        case 10: 
            $msg = "Operação realizada com sucesso!";
            $estilo_cor = "color: green;";
            break;
        case 11: 
            $msg = "Música não encontrada ou você não tem permissão para esta ação.";
            $estilo_cor = "color: orange;"; 
            break;
        case 'senhas_nao_coincidem':
            $msg = "As senhas não coincidem!";
            $estilo_cor = "color: red;";
            break;
        case 'senha_curta':
            $msg = "A senha deve ter pelo menos 6 caracteres!";
            $estilo_cor = "color: orange;";
            break;
        case 'email_invalido':
            $msg = "Formato de e-mail inválido!";
            $estilo_cor = "color: orange;";
            break;
        case 'usuario_email_existente':
            $msg = "Nome de usuário ou e-mail já cadastrado!";
            $estilo_cor = "color: red;";
            break;
        
        default:
            $msg = ""; 
            break;
    }

    if (!empty($msg)) {
        echo '<h3 style="' . $estilo_cor . '">' . $msg . '</h3>';
    }
}

?>