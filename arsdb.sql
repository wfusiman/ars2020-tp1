-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-04-2020 a las 06:45:24
-- Versión del servidor: 10.4.11-MariaDB
-- Versión de PHP: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de datos: `arsdb`
--

CREATE DATABASE arsdb;

-- --------------------------------------------------------

--
-- Usuario: `usuario_ars`
--

CREATE USER 'usuario_ars'@'localhost' IDENTIFIED BY '12345678';
GRANT SELECT, INSERT, UPDATE, DELETE, SHOW VIEW ON `arsdb`.* TO 'usuario_ars'@'%';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

USE arsdb;
	
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `pwd` varchar(200) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

COMMIT;
