-- phpMyAdmin SQL Dump
-- versão 5.2.1
-- Host: 127.0.0.1
-- Geração: 07/10/2025

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Cria o banco de dados se não existir
CREATE DATABASE IF NOT EXISTS `stock_control` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Seleciona o banco de dados
USE `stock_control`;

-- --------------------------------------------------------
-- Estrutura para tabela `usuarios`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('admin','funcionario') DEFAULT 'funcionario',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Inserindo dados na tabela `usuarios`
-- --------------------------------------------------------

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`) VALUES
(1, 'Yagao', 'yago@gmail.com', '$2y$10$qZ4CgnSEm9ovV.7echwEpeE3.ItktI0aJkKvAF5LD.Smts/F2lQaG', 'admin'),
(2, 'Cleiton', 'cleiton@gmail.com', '$2y$10$LKaq/dqdtXW/.9Ve5LHV4.j4gUPNakm/HKmPx.Ucm12o.ZTvInOlG', 'funcionario');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
