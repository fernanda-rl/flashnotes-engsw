-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 30/04/2026 às 06:25
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `flashnotes`
--

CREATE USER 'flashuser'@'localhost' IDENTIFIED BY '1234';
GRANT ALL PRIVILEGES ON flashnotes.* TO 'flashuser'@'localhost';

-- --------------------------------------------------------

--
-- Estrutura para tabela `eventos`
--

CREATE TABLE `eventos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `eventos`
--

INSERT INTO `eventos` (`id`, `usuario_id`, `titulo`, `data`, `tipo`) VALUES
(1, 2, 'Prova de Física', '2026-04-10', 'Prova'),
(2, 2, 'Apresentação de Trabalho', '2026-04-11', 'Apresentação'),
(3, 2, 'Prova de Matemática', '2026-04-08', 'Prova'),
(4, 2, 'Entrega de Projeto', '2026-04-15', 'Entrega');

-- --------------------------------------------------------

--
-- Estrutura para tabela `horarios`
--

CREATE TABLE `horarios` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `disciplina` varchar(100) DEFAULT NULL,
  `horario` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `horarios`
--

INSERT INTO `horarios` (`id`, `usuario_id`, `disciplina`, `horario`) VALUES
(1, 2, 'Física', '07:00 - 08:00'),
(2, 2, 'Matemática', '08:00 - 09:00'),
(3, 2, 'Programação', '09:30 - 10:30'),
(4, 2, 'Banco de Dados', '10:30 - 11:30'),
(5, 2, 'Redes', '08:00 - 09:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tarefas`
--

CREATE TABLE `tarefas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `vencimento` date DEFAULT NULL,
  `prioridade` enum('Alta','Média','Baixa') DEFAULT NULL,
  `status` enum('Não iniciado','Em progresso','Concluído') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tarefas`
--

INSERT INTO `tarefas` (`id`, `usuario_id`, `titulo`, `vencimento`, `prioridade`, `status`) VALUES
(1, 2, 'Trabalho de Física', '2026-04-10', 'Alta', 'Não iniciado'),
(2, 2, 'Lista de Matemática', '2026-04-08', 'Média', 'Em progresso'),
(3, 2, 'Resumo de História', '2026-04-12', 'Baixa', 'Concluído'),
(4, 2, 'Projeto de Programação', '2026-04-15', 'Alta', 'Não iniciado');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `tipo_perfil` enum('estudante','professor') NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha_hash`, `tipo_perfil`, `data_criacao`) VALUES
(1, 'Teste', 'usuario@exemplo.com', '$2y$10$wH8QzQzQzQzQzQzQzQzQzOeW8eW8eW8eW8eW8eW8eW8eW8eW8eW8e', 'estudante', '2026-04-30 03:47:00'),
(2, 'Usuário', 'adrielly@gmail.com', '$2y$10$wXdn259EBmBIAj57CunpzOl4ntVy.Xuact3UhA5GaI1raXQJnnMUq', 'estudante', '2026-04-30 03:54:11'),
(3, 'Usuário', 'leticia@gmail.com', '$2y$10$MQxjsnE2oXerfvrHTax1te6jJaZmfkxMPUALMWGeaizf7te7iBN.q', 'estudante', '2026-04-30 03:55:31');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `tarefas`
--
ALTER TABLE `tarefas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `tarefas`
--
ALTER TABLE `tarefas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `eventos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `horarios`
--
ALTER TABLE `horarios`
  ADD CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `tarefas`
--
ALTER TABLE `tarefas`
  ADD CONSTRAINT `tarefas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
