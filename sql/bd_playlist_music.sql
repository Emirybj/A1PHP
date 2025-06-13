-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 12/06/2025 às 20:18 (Atualizado)
-- Versão do servidor: 10.4.32-MariaDB - mariadb.org binary distribution
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bd_playlist_music`
--
CREATE DATABASE IF NOT EXISTS `bd_playlist_music` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `bd_playlist_music`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
    `id` INT(11) NOT NULL,
    `nome_usuario` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
    `senha_hash` VARCHAR(20) COLLATE utf8mb4_unicode_ci NOT NULL, 
    `email` VARCHAR(80) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
    `data_cadastro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--
INSERT INTO `usuarios` (`id`, `nome_usuario`, `senha_hash`, `email`) VALUES
(1, 'emirybj', 'emi8504', 'e.bueno@cs.up.edu.br');

-- --------------------------------------------------------

--
-- Estrutura para tabela `musicas`
--

CREATE TABLE `musicas` (
    `id` INT(11) NOT NULL AUTO_INCREMENT, -- Adicionado AUTO_INCREMENT aqui
    `titulo` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `descricao` TEXT COLLATE utf8mb4_unicode_ci NOT NULL,
    `usuario_id` INT(11) NOT NULL,
    `data_cadastro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `fk_usuario_id` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `nome_usuario_UNIQUE` (`nome_usuario`),
    ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- Índices de tabela `musicas`
--
ALTER TABLE `musicas`
    ADD PRIMARY KEY (`id`), -- Corrigido PRIMARY KEI para PRIMARY KEY
    ADD KEY `fk_usuario_id` (`usuario_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
    MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabela `musicas`
--
ALTER TABLE `musicas`
    ADD CONSTRAINT `fk_usuario_id` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
