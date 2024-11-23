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

 Date: 24/11/2024 00:26:50
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `role` enum('super_admin','admin','operator','saksi') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'saksi',
  `custom_fields` json NULL,
  `avatar_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'avatars/default.jpg',
  `created_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Super Admin', 'superadmin@example.com', NULL, '$2y$12$L02kDUn37FpLaBYFUK46ZOLN3aVQlXpDKEignVFTk9iUsYkV/8DZK', NULL, 'super_admin', NULL, 'avatars/default.jpg', '1', '2024-11-22 21:07:20', NULL);
INSERT INTO `users` VALUES (2, 'Akun Admin', 'admin@admin.com', NULL, '$2y$12$LFU4Yu1sdUlYoD31qh0Pc.f0yOV18k88XbCYQfPoUF.77.c1prIzS', NULL, 'admin', NULL, 'avatars/default.jpg', '2', '2024-11-22 21:09:18', '2024-11-22 21:13:50');
INSERT INTO `users` VALUES (3, 'Akun Operator', 'operator@operator.com', NULL, '$2y$12$67f.d/VPXq7yVav8sLYfbOkRqrt.qpkOULDRyUciNf73juNgmFsgy', NULL, 'operator', NULL, 'avatars/default.jpg', '2', '2024-11-22 21:11:54', '2024-11-22 21:14:27');
INSERT INTO `users` VALUES (4, 'Akun Saksi', 'saksi@saksi.com', NULL, '$2y$12$nzgPQvoQsk97mCSav.5mb.beUD3avDGZtN4fN9jgJnSHMzaNT.3xC', NULL, 'saksi', NULL, 'avatars/default.jpg', '2', '2024-11-22 21:13:08', '2024-11-22 21:13:08');

SET FOREIGN_KEY_CHECKS = 1;
