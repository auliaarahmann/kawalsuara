/*
 Navicat Premium Dump SQL

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80030 (8.0.30)
 Source Host           : localhost:3306
 Source Schema         : kawalsuara_db

 Target Server Type    : MySQL
 Target Server Version : 80030 (8.0.30)
 File Encoding         : 65001

 Date: 24/11/2024 00:26:38
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for kecamatans
-- ----------------------------
DROP TABLE IF EXISTS `kecamatans`;
CREATE TABLE `kecamatans`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_kecamatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6172 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of kecamatans
-- ----------------------------
INSERT INTO `kecamatans` VALUES (1778, 'Jangka', NULL, NULL);
INSERT INTO `kecamatans` VALUES (1828, 'Jeumpa', NULL, NULL);
INSERT INTO `kecamatans` VALUES (1836, 'Jeunieb', NULL, NULL);
INSERT INTO `kecamatans` VALUES (1958, 'Gandapura (Ganda Pura)', NULL, NULL);
INSERT INTO `kecamatans` VALUES (2070, 'Juli', NULL, NULL);
INSERT INTO `kecamatans` VALUES (2333, 'Kota Juang', NULL, NULL);
INSERT INTO `kecamatans` VALUES (2892, 'Kuala', NULL, NULL);
INSERT INTO `kecamatans` VALUES (3619, 'Kuta Blang', NULL, NULL);
INSERT INTO `kecamatans` VALUES (3801, 'Makmur', NULL, NULL);
INSERT INTO `kecamatans` VALUES (3838, 'Pandrah', NULL, NULL);
INSERT INTO `kecamatans` VALUES (4446, 'Peudada', NULL, NULL);
INSERT INTO `kecamatans` VALUES (4507, 'Peulimbang (Plimbang)', NULL, NULL);
INSERT INTO `kecamatans` VALUES (4593, 'Peusangan', NULL, NULL);
INSERT INTO `kecamatans` VALUES (4611, 'Peusangan Selatan', NULL, NULL);
INSERT INTO `kecamatans` VALUES (4623, 'Peusangan Siblah Krueng', NULL, NULL);
INSERT INTO `kecamatans` VALUES (5073, 'Samalanga', NULL, NULL);
INSERT INTO `kecamatans` VALUES (6171, 'Simpang Mamplam', NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
