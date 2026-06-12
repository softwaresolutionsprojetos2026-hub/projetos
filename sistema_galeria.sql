/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50516
Source Host           : localhost:3307
Source Database       : sistema_galeria

Target Server Type    : MYSQL
Target Server Version : 50516
File Encoding         : 65001

Date: 2026-06-12 10:54:27
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `categorias`
-- ----------------------------
DROP TABLE IF EXISTS `categorias`;
CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of categorias
-- ----------------------------
INSERT INTO `categorias` VALUES ('1', 'Limpeza', '2026-06-08 10:11:25');

-- ----------------------------
-- Table structure for `imagens`
-- ----------------------------
DROP TABLE IF EXISTS `imagens`;
CREATE TABLE `imagens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `produto_id` int(11) NOT NULL,
  `caminho` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `produto_id` (`produto_id`),
  CONSTRAINT `imagens_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of imagens
-- ----------------------------
INSERT INTO `imagens` VALUES ('3', '2', 'uploads/1/img_6a285d25678d81.39712307.jpg', '2026-06-09 15:36:21');
INSERT INTO `imagens` VALUES ('4', '2', 'uploads/1/img_6a285d256f9231.95985425.jpg', '2026-06-09 15:36:21');

-- ----------------------------
-- Table structure for `produtos`
-- ----------------------------
DROP TABLE IF EXISTS `produtos`;
CREATE TABLE `produtos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria_id` int(11) NOT NULL,
  `nome` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `categoria_id` (`categoria_id`),
  KEY `fk_produtos_usuarios` (`usuario_id`),
  CONSTRAINT `fk_produtos_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `produtos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of produtos
-- ----------------------------
INSERT INTO `produtos` VALUES ('2', '1', 'teste', '1.00', '2026-06-09 15:36:19', '1');

-- ----------------------------
-- Table structure for `usuarios`
-- ----------------------------
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senha` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tipo_acesso` enum('limitado','ilimitado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'limitado',
  `telefone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of usuarios
-- ----------------------------
INSERT INTO `usuarios` VALUES ('1', 'Administrador', 'admin@admin.com', '$2y$12$uzt4EUDgSKvZ1/FIoW6FqO7citYXZkx9omDkqC9khUvy8nuvLB3Sm', '2026-06-06 09:11:11', 'ilimitado', null);
INSERT INTO `usuarios` VALUES ('2', 'Visitante de Teste', 'visitante@teste.com', '$2y$12$xKD9Qby713KgKEXYdkal.eXQsDZ3qr6uC1nsakVu21k3leXAsEBwu', '2026-06-06 09:19:58', 'limitado', null);
INSERT INTO `usuarios` VALUES ('3', 'Fred', 'fred@fred.com', '$2y$12$0XVbBVCjR8MdX6hqrBBEfuO.F/.taEwT6CdKfGAxUTbyuiQ208se.', '2026-06-08 08:34:04', 'limitado', '(22) 22222-2222');
