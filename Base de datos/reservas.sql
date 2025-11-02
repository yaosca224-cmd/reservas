/*
 Navicat Premium Data Transfer

 Source Server         : MYSQL
 Source Server Type    : MySQL
 Source Server Version : 80039 (8.0.39)
 Source Host           : localhost:3306
 Source Schema         : reservas

 Target Server Type    : MySQL
 Target Server Version : 80039 (8.0.39)
 File Encoding         : 65001

 Date: 13/10/2025 22:04:14
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for reser000
-- ----------------------------
DROP TABLE IF EXISTS `reser000`;
CREATE TABLE `reser000`  (
  `USUARIO_000` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `FECHA_000` datetime NULL DEFAULT NULL,
  `TABLA_000` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `LLAVE1_000` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `LLAVE2_000` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `OPERACION_000` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `REGISTRO_000` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT 'Guarda una cadena con los datos actuales del registro afectado'
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of reser000
-- ----------------------------

-- ----------------------------
-- Table structure for reser001
-- ----------------------------
DROP TABLE IF EXISTS `reser001`;
CREATE TABLE `reser001`  (
  `USUARIO_REL` int NOT NULL AUTO_INCREMENT,
  `NOMBRE_001` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `CORREO_001` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `CLAVE_001` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `ROL_001` enum('admin','usuario') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT 'usuario',
  PRIMARY KEY (`USUARIO_REL`) USING BTREE,
  UNIQUE INDEX `CORREO_001`(`CORREO_001` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of reser001
-- ----------------------------
INSERT INTO `reser001` VALUES (1, 'kathy', 'yaosca224@gmail.com', '$2y$10$6GiYWjbRm79NPb0bme0HuuVwEt.SHeqX/URVgrGdz265YE8wmNsXG', 'usuario');
INSERT INTO `reser001` VALUES (2, 'Admin Principal', 'admin@correo.com', '$2y$10$8dxU1J94S/P0QRQ.r6vYT.3pDUa8gAZXO82iGeNWmY5BxZrcQxhHG', 'admin');
INSERT INTO `reser001` VALUES (3, 'juana', 'juana@gmail.com', '$2y$10$i.gzIDFgAAFDhYtyEh/9WOoHqkJtb2b7V.FVA6DZOzB3MK1GysiUq', 'usuario');
INSERT INTO `reser001` VALUES (4, 'mary', 'mary@gmail.com', '$2y$10$wTy4L1Yz1KCJxjAKZWEPLe.Z6B5vpMbmZOJd4nPbK4ee6lbWSHhsm', 'admin');
INSERT INTO `reser001` VALUES (5, 'cruz', 'cruz@gmail.com', '$2y$10$/IW6C2OttgDbW7H2XLsAnegbvMP3fRHY4BFnwY6JTcjtXVorNl8ne', 'admin');

-- ----------------------------
-- Table structure for reser002
-- ----------------------------
DROP TABLE IF EXISTS `reser002`;
CREATE TABLE `reser002`  (
  `EVENTOS_REL` int NOT NULL AUTO_INCREMENT,
  `TITULO_002` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `DESC_002` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `FECHA_002` date NULL DEFAULT NULL,
  `CUPOS_002` int NULL DEFAULT 0,
  `PRECIO_002` decimal(10, 2) NULL DEFAULT 0.00,
  `IMAGEN_002` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`EVENTOS_REL`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of reser002
-- ----------------------------
INSERT INTO `reser002` VALUES (7, 'VIAJE A GUATEMALA', 'Viaja a Guatemala', '2002-10-13', 0, 250.00, '1760414378_guatemala.jpg');

-- ----------------------------
-- Table structure for reser003
-- ----------------------------
DROP TABLE IF EXISTS `reser003`;
CREATE TABLE `reser003`  (
  `RESERVAS_REL` int NOT NULL AUTO_INCREMENT,
  `USUARIO_REL` int NOT NULL,
  `EVENTOS_REL` int NOT NULL,
  `CANTIDAD` int NULL DEFAULT 1,
  `TOTAL_PAGO` decimal(10, 2) NULL DEFAULT 0.00,
  `PAGO` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `FECHA_RESERVA` datetime NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`RESERVAS_REL`) USING BTREE,
  INDEX `USUARIO_REL`(`USUARIO_REL` ASC) USING BTREE,
  INDEX `EVENTOS_REL`(`EVENTOS_REL` ASC) USING BTREE,
  CONSTRAINT `reser003_ibfk_1` FOREIGN KEY (`USUARIO_REL`) REFERENCES `reser001` (`USUARIO_REL`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `reser003_ibfk_2` FOREIGN KEY (`EVENTOS_REL`) REFERENCES `reser002` (`EVENTOS_REL`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 67 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of reser003
-- ----------------------------
INSERT INTO `reser003` VALUES (66, 1, 7, 25, 6250.00, 'efectivo', '2025-10-13 22:00:52');

-- ----------------------------
-- Table structure for reser010
-- ----------------------------
DROP TABLE IF EXISTS `reser010`;
CREATE TABLE `reser010`  (
  `INICIO_REL` int NOT NULL AUTO_INCREMENT,
  `TITULO` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `MISION` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `VISION` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  PRIMARY KEY (`INICIO_REL`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of reser010
-- ----------------------------
INSERT INTO `reser010` VALUES (1, 'Vía-Go', 'En VíaGo, conectamos a las personas con experiencias únicas de viaje, tanto nacionales como internacionales.\r\nNuestra misión es hacer que descubrir nuevos destinos sea fácil, accesible y emocionante, ofreciendo un espacio confiable donde cada viajero pueda planificar, reservar y vivir su próxima aventura.', 'Ser la plataforma digital de viajes más innovadora y cercana, reconocida por inspirar a más personas a explorar el mundo con libertad, confianza y pasión por descubrir.\r\n');

-- ----------------------------
-- Table structure for reser011
-- ----------------------------
DROP TABLE IF EXISTS `reser011`;
CREATE TABLE `reser011`  (
  `SLIDER_REL` int NOT NULL AUTO_INCREMENT,
  `IMAGEN` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `ORDEN` int NULL DEFAULT 1,
  PRIMARY KEY (`SLIDER_REL`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of reser011
-- ----------------------------
INSERT INTO `reser011` VALUES (4, '1759974498_guatemala.jpg', 1);
INSERT INTO `reser011` VALUES (6, '1759974523_honduras.webp', 1);
INSERT INTO `reser011` VALUES (9, '1759975290_san juan.webp', 1);

SET FOREIGN_KEY_CHECKS = 1;
