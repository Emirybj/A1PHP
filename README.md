# Dupla Emily Bueno e Caio Felipe

## Tema Escolhido:
# Sistema de Gerenciamento de Músicas (Sua Playlist)

Este sistema utiliza o framework **Bootstrap v5.3** para sua apresentação visual.

## Resumo do Funcionamento

O sistema permite que usuários cadastrados gerenciem suas músicas, incluindo funcionalidades de cadastro de usuários, login, adição, visualização, edição e exclusão de músicas.

## Usuário/Senha de Teste

Para testar o sistema, utilize as seguintes credenciais ou cadastre-se na página `cadastro.php`:

* **Usuário de Teste:** `usuario123`
* **Senha de Teste:** `84181932`
* **E-mail de Teste:** `usuario123@gmail.com`

## Passos para Instalação do Banco de Dados

1.  Inicie o Apache e MySQL no XAMPP.
2.  Acesse o phpMyAdmin (`http://localhost/phpmyadmin`).
3.  Crie um novo banco de dados com o nome `bd_playlist_music` e Collation `utf8mb4_general_ci`.
4.  Com o banco de dados `bd_playlist_music` selecionado, vá para a aba **SQL**.
5.  Cole e execute o seguinte script SQL:

    ```sql
    SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
    START TRANSACTION;
    SET time_zone = "+00:00";

    /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
    /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
    /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
    /*!40101 SET NAMES utf8mb4 */;

    USE `bd_playlist_music`;

    CREATE TABLE `usuarios` (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `nome_usuario` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
      `senha_hash` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL, -- **IMPORTANTE: VARCHAR(255) para hashes de senha**
      `email` VARCHAR(80) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
      `data_cadastro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    -- Usuário de teste, senha 'emi8504'. SUBSTITUA O HASH ABAIXO POR UM HASH REAL SE FOR MANTER.
    -- Para gerar um hash de teste em PHP: echo password_hash('emi8504', PASSWORD_DEFAULT);
    INSERT INTO `usuarios` (`id`, `nome_usuario`, `senha_hash`, `email`) VALUES
    (1, 'emirybj', '$2y$10$f/A/t/p/a/t/e/s/t/e/d/e/s/e/n/h/a/h/a/s/h/e/a/e/s/t/e', 'e.bueno@cs.up.edu.br');
    -- Substitua o hash acima por um hash real gerado para 'emi8504'.

    CREATE TABLE `musicas` (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `titulo` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
      `descricao` TEXT COLLATE utf8mb4_unicode_ci NOT NULL,
      `usuario_id` INT(11) NOT NULL,
      `data_cadastro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `fk_usuario_id` (`usuario_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    ALTER TABLE `usuarios`
      MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

    ALTER TABLE `musicas`
      ADD CONSTRAINT `fk_usuario_id` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

    COMMIT;

    /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
    /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
    /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
    ```