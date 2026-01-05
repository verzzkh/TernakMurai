/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE DATABASE IF NOT EXISTS `ternak` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `ternak`;

CREATE TABLE IF NOT EXISTS `admin` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_user_id_unique` (`user_id`),
  UNIQUE KEY `admin_username_unique` (`username`),
  CONSTRAINT `admin_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `admin` (`id`, `user_id`, `username`, `nama_lengkap`, `created_at`, `updated_at`) VALUES
	(1, 1, 'admin', 'Administrator', '2025-10-02 20:27:01', '2025-10-02 20:27:01');

CREATE TABLE IF NOT EXISTS `anakan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `peternak_id` bigint unsigned NOT NULL,
  `kandang_id` bigint unsigned NOT NULL,
  `perkawinan_id` bigint unsigned DEFAULT NULL,
  `nomor_ring` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('jantan','betina','tidak_diketahui') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tidak_diketahui',
  `status_pertumbuhan` enum('trotol','pastol','lomba') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'trotol',
  `deskripsi_karakteristik` text COLLATE utf8mb4_unicode_ci,
  `catatan_perubahan` text COLLATE utf8mb4_unicode_ci,
  `catatan_penjualan` text COLLATE utf8mb4_unicode_ci,
  `harga` int DEFAULT NULL,
  `status_penjualan` enum('belum_dijual','terjual') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_dijual',
  `tanggal_jual` date DEFAULT NULL,
  `foto_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `anakan_unique_ring_per_peternak` (`peternak_id`,`nomor_ring`),
  KEY `anakan_kandang_id_foreign` (`kandang_id`),
  KEY `anakan_perkawinan_id_foreign` (`perkawinan_id`),
  KEY `anakan_peternak_id_status_penjualan_index` (`peternak_id`,`status_penjualan`),
  CONSTRAINT `anakan_kandang_id_foreign` FOREIGN KEY (`kandang_id`) REFERENCES `kandang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `anakan_perkawinan_id_foreign` FOREIGN KEY (`perkawinan_id`) REFERENCES `perkawinan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `anakan_peternak_id_foreign` FOREIGN KEY (`peternak_id`) REFERENCES `peternak` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `anakan` (`id`, `peternak_id`, `kandang_id`, `perkawinan_id`, `nomor_ring`, `tanggal_lahir`, `jenis_kelamin`, `status_pertumbuhan`, `deskripsi_karakteristik`, `catatan_perubahan`, `catatan_penjualan`, `harga`, `status_penjualan`, `tanggal_jual`, `foto_path`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1, 1, NULL, 'A-1-1-KFMR', '1995-12-18', 'jantan', 'trotol', 'Eos expedita ut alias dolores sed id.', NULL, NULL, 418320, 'belum_dijual', NULL, 'anakan/1/cfaeaf94-3523-4959-94ac-61d72b2904b9.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(2, 1, 1, NULL, 'A-1-1-EMYC', '2009-02-12', 'tidak_diketahui', 'trotol', 'Placeat sit dolorem unde expedita sint labore.', NULL, NULL, 700852, 'belum_dijual', NULL, 'anakan/1/23c5119e-76a7-47f9-bcb3-38d4ff07268e.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(3, 1, 1, NULL, 'A-1-1-RGFD', '2001-11-02', 'jantan', 'trotol', 'Fuga unde officiis aperiam voluptatem.', 'Doloribus nam totam ut eos soluta in.', NULL, 1161921, 'belum_dijual', NULL, 'anakan/1/34a92d09-8614-4743-aa71-aacae9a71964.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(4, 1, 1, NULL, 'A-1-1-AHTT', '1982-01-16', 'jantan', 'trotol', 'Et laudantium iusto debitis.', 'Sunt tempore dolorem nihil non qui repudiandae possimus.', 'Laku via WA', 1584698, 'terjual', '2025-09-20', 'anakan/1/2b3236b2-2921-429c-b0b6-40e328f67ed2.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(5, 1, 1, NULL, 'A-1-1-E7B4', '1998-05-02', 'tidak_diketahui', 'pastol', 'Labore dolores qui ut possimus velit optio minima blanditiis.', NULL, NULL, 893675, 'belum_dijual', NULL, 'anakan/1/eeb4e746-5a78-4d15-af35-079b7f34c8dd.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(6, 1, 2, NULL, 'A-1-2-M0PX', '2001-11-15', 'jantan', 'pastol', 'Cupiditate adipisci quia nulla illo ducimus voluptatem.', NULL, NULL, 1991404, 'belum_dijual', NULL, 'anakan/1/fba2ae3e-dff6-4c49-8179-2a9129bc4910.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(7, 1, 2, NULL, 'A-1-2-TZYI', '1986-11-21', 'betina', 'pastol', 'Eveniet officiis quia totam quas.', NULL, 'Laku via WA', 1887334, 'terjual', '2025-10-02', 'anakan/1/addaf5c7-f74d-491c-921e-8d3c62bc7546.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(8, 1, 2, NULL, 'A-1-2-5K70', '2009-01-27', 'betina', 'trotol', 'Dolores libero est excepturi officiis blanditiis iste labore velit.', 'Et consequuntur neque sed nesciunt aut nam eos.', NULL, 493686, 'belum_dijual', NULL, 'anakan/1/ed133357-018a-4f0f-b39d-0a8c592e1b80.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(9, 1, 2, NULL, 'A-1-2-E1ZK', '1983-09-27', 'betina', 'lomba', 'Totam et similique labore voluptas sint.', NULL, NULL, 1281735, 'belum_dijual', NULL, 'anakan/1/423afe43-858b-4549-90ce-02c72c909774.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(10, 1, 2, NULL, 'A-1-2-O9NO', '2010-12-23', 'jantan', 'trotol', 'Aut alias odio aut.', NULL, NULL, 1423151, 'belum_dijual', NULL, 'anakan/1/be9d4ee9-f1d2-43cc-89be-ace507770c3f.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(11, 1, 3, NULL, 'A-1-3-T124', '1996-01-03', 'betina', 'lomba', 'Atque rerum amet beatae est sunt asperiores iste.', 'Consequuntur qui quidem qui quae enim velit.', NULL, 672569, 'belum_dijual', NULL, 'anakan/1/da0c2b32-b1b2-441c-9b93-d933948acabd.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(12, 1, 3, NULL, NULL, '2024-07-18', 'tidak_diketahui', 'trotol', 'Eos incidunt illum libero.', 'Et in ut ducimus fuga dolore.', 'Laku via WA', 455284, 'terjual', '2025-10-02', 'anakan/1/7db5f7d2-c335-4779-b109-c30725dc873c.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(13, 1, 3, NULL, 'A-1-3-4GBT', '1998-06-28', 'tidak_diketahui', 'trotol', 'Ipsum perferendis quod officiis ipsum voluptatem incidunt tempore enim.', NULL, NULL, 1838944, 'belum_dijual', NULL, 'anakan/1/e80f3b36-7c68-4d77-b407-f86e49a0bfbf.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(14, 1, 3, NULL, 'A-1-3-K0TK', '2009-12-10', 'betina', 'lomba', 'Qui odit fugit rem voluptas.', 'Voluptas alias sapiente rerum deleniti earum consequatur ut.', NULL, 414701, 'belum_dijual', NULL, 'anakan/1/5b0354d6-06d6-4c12-a119-da04432658ae.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(15, 1, 3, NULL, 'A-1-3-DCPQ', '2003-10-30', 'jantan', 'pastol', 'Vel et minus quibusdam qui ab quas.', NULL, 'Laku via WA', 1098769, 'terjual', '2025-10-02', 'anakan/1/2930e4fc-d436-4582-b64b-524cae8adc10.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(16, 1, 4, NULL, NULL, '1977-02-07', 'tidak_diketahui', 'trotol', 'Dolores cupiditate quo ex facere ipsum vel.', NULL, NULL, 640095, 'belum_dijual', NULL, 'anakan/1/1c897362-7c44-4556-b21c-24b1dae23baf.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(17, 1, 4, NULL, 'A-1-4-LNVM', '2016-08-12', 'tidak_diketahui', 'pastol', 'Quisquam tenetur aut dolorum fuga enim.', NULL, NULL, 1302456, 'belum_dijual', NULL, 'anakan/1/fa1686c9-94e4-4726-acac-abd92c922dfa.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(18, 1, 4, NULL, NULL, '2002-07-03', 'tidak_diketahui', 'pastol', 'Veritatis dolore commodi unde necessitatibus doloremque reprehenderit qui aliquid.', NULL, NULL, 758309, 'belum_dijual', NULL, 'anakan/1/fb92a951-13a2-4514-a01d-955de3e67c6b.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(19, 1, 4, NULL, 'A-1-4-YZ0A', '1999-05-10', 'betina', 'trotol', 'Quas quis optio id blanditiis libero.', 'Aut minima dolorum impedit molestias fugit dicta.', NULL, 1915024, 'belum_dijual', NULL, 'anakan/1/7c2491f8-4227-46a2-9a5e-68a4a3facc38.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(20, 1, 4, NULL, 'A-1-4-0DLY', '1983-05-07', 'betina', 'trotol', 'Ab facere aut odio.', NULL, 'Laku via WA', 1143435, 'terjual', '2025-09-26', 'anakan/1/5c1c6bda-420f-4760-a27b-2e82f0561592.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(21, 2, 5, NULL, 'A-2-5-QLXO', '2005-06-25', 'betina', 'pastol', 'Architecto aperiam reiciendis voluptate excepturi.', NULL, 'Laku via WA', 1084817, 'terjual', '2025-09-16', 'anakan/2/c969b92a-62f8-4973-b82e-a6a3001debcb.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(22, 2, 5, NULL, 'A-2-5-J4XX', '1971-09-24', 'jantan', 'lomba', 'Optio dolores nisi quam possimus a reiciendis.', 'Explicabo ducimus id mollitia quisquam.', NULL, 420031, 'belum_dijual', NULL, 'anakan/2/8cf56bb5-f7b6-4260-9b6a-7d46aa3109b3.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(23, 2, 5, NULL, 'A-2-5-K7FY', '1971-02-03', 'jantan', 'lomba', 'Voluptates eos explicabo voluptatem quia sit.', NULL, 'Laku via WA', 882108, 'terjual', '2025-09-17', 'anakan/2/f4287a07-70f0-488e-a9e8-db4012008eeb.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(24, 2, 6, NULL, 'A-2-6-JGSE', '2019-06-02', 'tidak_diketahui', 'lomba', 'Ea atque suscipit consequuntur cupiditate recusandae dolorum quia nulla.', 'Omnis accusantium illum occaecati provident in eius sint dolores.', NULL, 1162535, 'belum_dijual', NULL, 'anakan/2/97be4e6a-4a15-4710-b58e-351b4703b4cb.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(25, 2, 6, NULL, 'A-2-6-IZGW', '1981-08-28', 'jantan', 'trotol', 'Iure saepe qui suscipit temporibus quod consequatur.', NULL, NULL, 606584, 'belum_dijual', NULL, 'anakan/2/71eefcbe-7167-485e-bade-21a1b6cbd6fd.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(26, 2, 6, NULL, 'A-2-6-E8DF', '2023-08-22', 'jantan', 'pastol', 'Deleniti delectus et eum eveniet dolores provident.', NULL, NULL, 656018, 'belum_dijual', NULL, 'anakan/2/ad58bfe9-fd53-42b1-80df-6b041423ec0e.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(27, 2, 6, NULL, 'A-2-6-RGVI', '1984-02-18', 'tidak_diketahui', 'trotol', 'Corporis qui minima sunt unde ex ex.', 'Delectus a perferendis enim molestiae vel.', NULL, 481600, 'belum_dijual', NULL, 'anakan/2/4389e51a-d4a8-497b-a41d-0eb5c054bdf7.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(28, 2, 7, NULL, 'A-2-7-RAXA', '2013-01-29', 'betina', 'trotol', 'Quae et eum consequatur.', 'Expedita mollitia quam sequi porro porro quam repellendus.', NULL, 1172386, 'belum_dijual', NULL, 'anakan/2/9ec0593a-c710-4e03-9c94-28dd1ccccc41.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(29, 2, 7, NULL, 'A-2-7-GESE', '2004-02-16', 'jantan', 'lomba', 'Occaecati quasi cum dolores laborum.', NULL, 'Laku via WA', 1571881, 'terjual', '2025-09-20', 'anakan/2/80197e2f-c948-46b9-8925-8a2f4c50b273.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(30, 2, 7, NULL, 'A-2-7-D5LL', '1983-10-15', 'jantan', 'pastol', 'Voluptatum est ut iusto fuga voluptatibus sequi quas.', 'Odit rerum maxime quasi error in dolorem.', NULL, 454257, 'belum_dijual', NULL, 'anakan/2/b0717c67-94b0-4292-8491-170acc307be7.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(31, 2, 9, NULL, 'A-2-9-7QCE', '1984-03-24', 'tidak_diketahui', 'trotol', 'Ex repellendus aperiam ex quae.', 'Non sit maiores quae impedit.', NULL, 1423444, 'belum_dijual', NULL, 'anakan/2/7a6e9157-6081-4c65-8cfc-a34a10b636ca.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(32, 2, 9, NULL, 'A-2-9-XR7Y', '2022-08-13', 'jantan', 'lomba', 'Omnis voluptatem eveniet voluptatem nostrum explicabo.', NULL, NULL, 741216, 'belum_dijual', NULL, 'anakan/2/e18c7819-0795-4675-92b6-4901b97e743b.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(33, 2, 9, NULL, 'A-2-9-UVLN', '2002-04-18', 'tidak_diketahui', 'pastol', 'Illo ullam et quis consectetur quo veritatis facilis.', 'Quia facere pariatur error vero ratione magni sunt.', NULL, 535252, 'belum_dijual', NULL, 'anakan/2/8166d598-e788-4891-879f-4440ac6a5e08.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(34, 2, 9, NULL, 'A-2-9-T8PP', '1979-12-15', 'betina', 'trotol', 'At facere aut inventore ipsa deleniti exercitationem pariatur.', 'Aut eos ea ullam corporis mollitia qui.', NULL, 1533233, 'belum_dijual', NULL, 'anakan/2/3538b625-f7a8-4645-9581-36b07df89169.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(35, 3, 10, NULL, 'A-3-10-3T7E', '1973-06-23', 'jantan', 'lomba', 'Ut quis aut sit sed.', 'Et officiis quas id id rerum.', NULL, 1370916, 'belum_dijual', NULL, 'anakan/3/c1c4085e-8a10-4d1f-aaf1-a08fab2487af.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(36, 3, 10, NULL, 'A-3-10-BGPI', '1995-06-14', 'betina', 'trotol', 'Voluptates esse molestiae non iusto reprehenderit veniam eos.', 'Reprehenderit vel et officia reiciendis rerum laboriosam ut explicabo.', NULL, 1683651, 'belum_dijual', NULL, 'anakan/3/b95abd7d-5bd5-44d7-9b21-1abd0e77079f.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(37, 3, 10, NULL, 'A-3-10-UBAG', '1972-05-01', 'tidak_diketahui', 'lomba', 'Saepe accusamus labore ab odio adipisci esse.', 'Et pariatur voluptatem quibusdam fugiat dolores.', NULL, 498162, 'belum_dijual', NULL, 'anakan/3/16b76e50-e7c3-4e91-85d5-701411e77de1.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(38, 4, 11, NULL, 'A-4-11-XEBM', '2023-09-19', 'jantan', 'lomba', 'Nam mollitia temporibus ea autem similique omnis.', 'Quo et exercitationem ut aliquam.', NULL, 1654521, 'belum_dijual', NULL, 'anakan/4/5e895c76-8a37-4477-804e-97e29c34f948.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(39, 4, 11, NULL, NULL, '1996-08-12', 'betina', 'trotol', 'Ut dolorum consequatur reprehenderit voluptas ea quo nesciunt.', 'Odit animi aut ducimus repudiandae minima error.', NULL, 1702899, 'belum_dijual', NULL, 'anakan/4/9ed83550-6373-481c-bd27-c6a8621119f7.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(40, 4, 11, NULL, 'A-4-11-LDLX', '1972-02-20', 'jantan', 'trotol', 'Eos totam eveniet illo esse.', NULL, NULL, 1431085, 'belum_dijual', NULL, 'anakan/4/c1560886-fea9-4c28-89eb-10f6d6d44837.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(41, 4, 13, NULL, NULL, '1985-07-02', 'betina', 'lomba', 'Est numquam sed cupiditate nisi.', NULL, 'Laku via WA', 900716, 'terjual', '2025-09-20', 'anakan/4/750515fe-2993-463a-a9bf-2edfb1b42e45.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(42, 4, 13, NULL, 'A-4-13-LH9L', '2023-07-13', 'jantan', 'lomba', 'Sunt illum quis illum rem ducimus consequatur.', NULL, 'Laku via WA', 629090, 'terjual', '2025-09-23', 'anakan/4/e46a73a0-4f5b-48dd-8a7c-75f139fa2567.jpg', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(43, 5, 14, NULL, 'A-5-14-C0Y9', '2003-11-15', 'jantan', 'trotol', 'Nisi quae quam ut beatae.', 'Reiciendis ullam ipsum adipisci.', NULL, 1263270, 'belum_dijual', NULL, 'anakan/5/0fcb11e5-a9fb-4f08-9670-0b6fc8f740e9.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(44, 5, 14, NULL, 'A-5-14-VDAJ', '2025-08-31', 'betina', 'pastol', 'Quidem ullam eum sed.', 'Non tenetur voluptatem possimus soluta sint est eos perferendis.', NULL, 550341, 'belum_dijual', NULL, 'anakan/5/c8f3ae0d-8712-4599-a726-8c5bdaf8cbe0.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(45, 5, 14, NULL, 'A-5-14-POAW', '2025-06-13', 'tidak_diketahui', 'trotol', 'Hic eligendi provident dicta reprehenderit eos nam illum.', NULL, NULL, 1873573, 'belum_dijual', NULL, 'anakan/5/7fc48098-d0ae-4cfd-bce0-3940d3a8366f.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(46, 5, 14, NULL, 'A-5-14-WZOG', '2012-07-06', 'betina', 'trotol', 'Nostrum et minima qui dolorem occaecati tenetur.', NULL, NULL, 1633078, 'belum_dijual', NULL, 'anakan/5/7e94771c-6f3d-479d-924a-27c0976abf0a.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(47, 5, 15, NULL, NULL, '1998-07-10', 'betina', 'trotol', 'Quo veniam eum velit aut quaerat consequatur.', NULL, NULL, 1160930, 'belum_dijual', NULL, 'anakan/5/9992ed4e-1ade-4ae9-8967-e90aa5690b47.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(48, 5, 15, NULL, NULL, '2020-01-01', 'tidak_diketahui', 'lomba', 'Est qui iste esse.', 'Corporis qui iste est animi sunt facere enim rem.', NULL, 584024, 'belum_dijual', NULL, 'anakan/5/b6897d9b-3653-403f-a627-d4e576639670.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(49, 5, 15, NULL, NULL, '1981-03-07', 'tidak_diketahui', 'lomba', 'Nesciunt molestiae eveniet at laborum.', NULL, NULL, 1986065, 'belum_dijual', NULL, 'anakan/5/d37b06ad-121f-417b-b3ee-563d4e133c06.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(50, 5, 16, NULL, 'A-5-16-UDE2', '1999-11-04', 'betina', 'lomba', 'Quis laboriosam libero qui deserunt qui consequatur.', NULL, 'Laku via WA', 1906182, 'terjual', '2025-09-20', 'anakan/5/0fcbc5bf-3651-496f-b843-c56f32a5fc5a.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(51, 5, 17, NULL, 'A-5-17-SANG', '2024-11-18', 'betina', 'trotol', 'Nihil quo sed aut nam.', NULL, NULL, 1113221, 'belum_dijual', NULL, 'anakan/5/89e68db0-a187-453e-9114-4ab496e23b87.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(52, 5, 17, NULL, NULL, '2009-10-15', 'jantan', 'trotol', 'Placeat nam at ut nihil earum voluptatem.', 'Velit in vitae enim aut.', 'Laku via WA', 1448833, 'terjual', '2025-09-19', 'anakan/5/144c48ca-a6ec-4e66-ab08-ddbb43328ed0.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(53, 6, 18, NULL, 'A-6-18-N4GX', '1980-12-16', 'betina', 'trotol', 'Provident nulla magni maxime voluptatem in delectus.', 'Quo quaerat earum doloremque ea aut.', NULL, 1552048, 'belum_dijual', NULL, 'anakan/6/ecb01c2b-e9f6-41ca-99c9-669092183eb4.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(54, 6, 19, NULL, 'A-6-19-E0FK', '1974-03-15', 'betina', 'trotol', 'Est quod non magnam asperiores ut voluptas harum iste.', NULL, 'Laku via WA', 1030977, 'terjual', '2025-09-19', 'anakan/6/62c0af2b-0edf-40b0-9bce-cafbf63a514b.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(55, 6, 20, NULL, 'A-6-20-WUVK', '1972-02-03', 'jantan', 'trotol', 'Facere corrupti quia laboriosam ab exercitationem voluptates.', NULL, 'Laku via WA', 936676, 'terjual', '2025-09-26', 'anakan/6/c8f5c915-2a8f-4fff-abc4-794a648e8788.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(56, 6, 20, NULL, NULL, '2017-01-10', 'tidak_diketahui', 'trotol', 'Id sapiente iusto est vero consequatur nostrum sed assumenda.', NULL, NULL, 777950, 'belum_dijual', NULL, 'anakan/6/083ca93c-856a-4448-bf00-a6213064cc77.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(57, 6, 21, NULL, NULL, '1974-08-05', 'betina', 'pastol', 'Ea accusantium recusandae ea sequi alias omnis.', NULL, NULL, 1447174, 'belum_dijual', NULL, 'anakan/6/3c52d5d4-6210-437d-99a0-3672cbb22da7.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(58, 7, 22, NULL, 'A-7-22-CCC5', '1974-04-01', 'betina', 'trotol', 'Quo labore qui architecto consequuntur est necessitatibus.', NULL, NULL, 569057, 'belum_dijual', NULL, 'anakan/7/dfee1247-2bd1-4908-9ae8-1397eef7af58.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(59, 7, 22, NULL, 'A-7-22-PXMD', '2015-10-19', 'betina', 'trotol', 'Provident sint sit consequatur quasi ut voluptate consequatur.', NULL, NULL, 1168662, 'belum_dijual', NULL, 'anakan/7/a77bc21f-8d9e-4945-bbc2-72c1021c4553.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(60, 7, 22, NULL, 'A-7-22-UNBU', '2009-09-27', 'jantan', 'pastol', 'Non enim voluptas iure in commodi.', NULL, NULL, 1510383, 'belum_dijual', NULL, 'anakan/7/958029c1-1386-48c0-8099-f45a4d6a7426.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(61, 7, 22, NULL, NULL, '2010-08-29', 'jantan', 'pastol', 'Odit perspiciatis ipsa suscipit distinctio.', NULL, NULL, 1019361, 'belum_dijual', NULL, 'anakan/7/72d84376-2cfc-4d8f-bbcd-624178882191.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(62, 7, 23, NULL, NULL, '2014-02-11', 'jantan', 'lomba', 'Quis dolores qui qui mollitia et esse consectetur.', NULL, NULL, 1064793, 'belum_dijual', NULL, 'anakan/7/6e3061d4-4fe5-46b7-bebc-3cf0a1be6495.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(63, 7, 23, NULL, 'A-7-23-WCBL', '1987-02-11', 'jantan', 'lomba', 'Nemo nam necessitatibus porro reiciendis velit.', NULL, NULL, 435263, 'belum_dijual', NULL, 'anakan/7/c7f7627e-6187-4f95-b795-229ce37638c0.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(64, 7, 23, NULL, 'A-7-23-AKZ6', '2008-08-21', 'tidak_diketahui', 'lomba', 'At aliquid necessitatibus nobis expedita.', NULL, NULL, 365519, 'belum_dijual', NULL, 'anakan/7/04fda7df-9ca5-4ac4-b18a-cd9f81e57f1d.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(65, 7, 23, NULL, NULL, '2004-01-03', 'jantan', 'pastol', 'Quisquam aspernatur dicta maxime quis explicabo tenetur.', NULL, NULL, 892555, 'belum_dijual', NULL, 'anakan/7/a8ac1102-4895-4c58-8e49-80f3a89a6158.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(66, 7, 24, NULL, 'A-7-24-KV8T', '2019-12-12', 'tidak_diketahui', 'lomba', 'Accusantium expedita nihil maiores error quia nihil sed iusto.', NULL, NULL, 1430007, 'belum_dijual', NULL, 'anakan/7/9b2aa2d7-f6d3-4e08-9180-4ecc429eab1c.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(67, 7, 24, NULL, 'A-7-24-NTO4', '1982-11-24', 'tidak_diketahui', 'lomba', 'Voluptatum quas consequatur magnam voluptatum dignissimos sit nihil.', 'Sunt blanditiis doloribus aperiam eum quia exercitationem ea.', 'Laku via WA', 1975741, 'terjual', '2025-09-25', 'anakan/7/4ee2cd08-1107-4f06-9c5f-586737348262.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(68, 7, 24, NULL, 'A-7-24-JMYA', '1981-01-24', 'tidak_diketahui', 'pastol', 'Impedit nihil tempora excepturi ut.', NULL, NULL, 1392615, 'belum_dijual', NULL, 'anakan/7/0604fc80-8a29-49e2-be2b-74cf18e50fbd.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(69, 7, 24, NULL, 'A-7-24-YUIR', '1980-05-02', 'betina', 'lomba', 'Ut quis cumque tenetur consequuntur officiis.', NULL, NULL, 771568, 'belum_dijual', NULL, 'anakan/7/aebfe84b-5c3f-4aff-b635-659e19d14a7e.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(70, 7, 26, NULL, 'A-7-26-8CJV', '2010-03-05', 'tidak_diketahui', 'trotol', 'Nesciunt delectus aperiam aperiam eveniet eius consequuntur.', NULL, NULL, 1551431, 'belum_dijual', NULL, 'anakan/7/4011fc1c-c91f-4c49-875f-3e7dc894a9bd.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(71, 7, 26, NULL, 'A-7-26-LEQH', '1977-10-23', 'jantan', 'lomba', 'Reiciendis quod sunt consequatur.', NULL, NULL, 1098835, 'belum_dijual', NULL, 'anakan/7/3c47c12d-ec95-4e61-930d-1dcb15fccc96.jpg', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL);

CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
	('boost.roster.scan', 'a:2:{s:6:"roster";O:21:"Laravel\\Roster\\Roster":2:{s:13:"\0*\0approaches";O:29:"Illuminate\\Support\\Collection":2:{s:8:"\0*\0items";a:0:{}s:28:"\0*\0escapeWhenCastingToString";b:0;}s:11:"\0*\0packages";O:32:"Laravel\\Roster\\PackageCollection":2:{s:8:"\0*\0items";a:8:{i:0;O:22:"Laravel\\Roster\\Package":6:{s:9:"\0*\0direct";b:1;s:13:"\0*\0constraint";s:6:"^11.31";s:10:"\0*\0package";E:37:"Laravel\\Roster\\Enums\\Packages:LARAVEL";s:14:"\0*\0packageName";s:17:"laravel/framework";s:10:"\0*\0version";s:7:"11.43.2";s:6:"\0*\0dev";b:0;}i:1;O:22:"Laravel\\Roster\\Package":6:{s:9:"\0*\0direct";b:0;s:13:"\0*\0constraint";s:6:"v0.3.5";s:10:"\0*\0package";E:37:"Laravel\\Roster\\Enums\\Packages:PROMPTS";s:14:"\0*\0packageName";s:15:"laravel/prompts";s:10:"\0*\0version";s:5:"0.3.5";s:6:"\0*\0dev";b:0;}i:2;O:22:"Laravel\\Roster\\Package":6:{s:9:"\0*\0direct";b:1;s:13:"\0*\0constraint";s:5:"^1.13";s:10:"\0*\0package";E:34:"Laravel\\Roster\\Enums\\Packages:PINT";s:14:"\0*\0packageName";s:12:"laravel/pint";s:10:"\0*\0version";s:6:"1.21.0";s:6:"\0*\0dev";b:1;}i:3;O:22:"Laravel\\Roster\\Package":6:{s:9:"\0*\0direct";b:1;s:13:"\0*\0constraint";s:5:"^1.26";s:10:"\0*\0package";E:34:"Laravel\\Roster\\Enums\\Packages:SAIL";s:14:"\0*\0packageName";s:12:"laravel/sail";s:10:"\0*\0version";s:6:"1.41.0";s:6:"\0*\0dev";b:1;}i:4;O:22:"Laravel\\Roster\\Package":6:{s:9:"\0*\0direct";b:1;s:13:"\0*\0constraint";s:7:"^11.0.1";s:10:"\0*\0package";E:37:"Laravel\\Roster\\Enums\\Packages:PHPUNIT";s:14:"\0*\0packageName";s:15:"phpunit/phpunit";s:10:"\0*\0version";s:6:"11.5.9";s:6:"\0*\0dev";b:1;}i:5;O:22:"Laravel\\Roster\\Package":6:{s:9:"\0*\0direct";b:0;s:13:"\0*\0constraint";s:0:"";s:10:"\0*\0package";E:38:"Laravel\\Roster\\Enums\\Packages:ALPINEJS";s:14:"\0*\0packageName";s:8:"alpinejs";s:10:"\0*\0version";s:6:"3.14.8";s:6:"\0*\0dev";b:1;}i:6;O:22:"Laravel\\Roster\\Package":6:{s:9:"\0*\0direct";b:0;s:13:"\0*\0constraint";s:0:"";s:10:"\0*\0package";E:38:"Laravel\\Roster\\Enums\\Packages:PRETTIER";s:14:"\0*\0packageName";s:8:"prettier";s:10:"\0*\0version";s:5:"3.5.1";s:6:"\0*\0dev";b:1;}i:7;O:22:"Laravel\\Roster\\Package":6:{s:9:"\0*\0direct";b:0;s:13:"\0*\0constraint";s:0:"";s:10:"\0*\0package";E:41:"Laravel\\Roster\\Enums\\Packages:TAILWINDCSS";s:14:"\0*\0packageName";s:11:"tailwindcss";s:10:"\0*\0version";s:6:"3.4.17";s:6:"\0*\0dev";b:1;}}s:28:"\0*\0escapeWhenCastingToString";b:0;}}s:9:"timestamp";i:1760923843;}', 1761010243);

CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `foto_indukan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `peternak_id` bigint unsigned NOT NULL,
  `indukan_id` bigint unsigned NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_cover` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `foto_indukan_peternak_id_indukan_id_index` (`peternak_id`,`indukan_id`),
  KEY `foto_indukan_indukan_id_is_cover_index` (`indukan_id`,`is_cover`),
  CONSTRAINT `foto_indukan_indukan_id_foreign` FOREIGN KEY (`indukan_id`) REFERENCES `indukan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `foto_indukan_peternak_id_foreign` FOREIGN KEY (`peternak_id`) REFERENCES `peternak` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `foto_indukan` (`id`, `peternak_id`, `indukan_id`, `path`, `caption`, `is_cover`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1, 1, 'indukan/1/1/b08a5630-efac-452a-ae8c-359593ee14bb.jpg', 'Cover jantan', 1, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(2, 1, 2, 'indukan/1/2/6ddf6f5f-acff-40a7-b5b5-cf68a56418ac.jpg', 'Cover betina', 1, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(3, 1, 1, 'indukan/1/1/873da130-5f32-4506-8004-bea81812bbdc.jpg', 'Close-up jantan', 0, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(4, 2, 3, 'indukan/2/3/618798aa-99a7-47a3-93fc-2a4346ca80f0.jpg', 'Cover jantan', 1, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(5, 2, 4, 'indukan/2/4/f42b039a-aeb3-42ed-b32a-30fa3cd8f2de.jpg', 'Cover betina', 1, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(6, 2, 3, 'indukan/2/3/2ae87a0c-de8f-408d-833b-6ebbf6f17a21.jpg', 'Close-up jantan', 0, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(7, 3, 5, 'indukan/3/5/383c6ce9-b28f-48b1-8e3d-ceb3d36491af.jpg', 'Cover jantan', 1, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(8, 3, 6, 'indukan/3/6/56e5a6f2-faa6-463a-8df4-9f6df373b30c.jpg', 'Cover betina', 1, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(9, 3, 5, 'indukan/3/5/7134687e-3a70-45d6-8b73-f109a7698fb4.jpg', 'Close-up jantan', 0, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(10, 4, 7, 'indukan/4/7/17815a01-038d-4928-8d22-7d8ad571b134.jpg', 'Cover jantan', 1, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(11, 4, 8, 'indukan/4/8/4a4063da-9a93-47e1-a168-87ecd1ce24d7.jpg', 'Cover betina', 1, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(12, 4, 7, 'indukan/4/7/171ba8c4-9d0d-4f8a-814b-3213f3bb0f60.jpg', 'Close-up jantan', 0, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(13, 5, 9, 'indukan/5/9/57f6fe1a-c642-4b15-85b5-07850ebc20c1.jpg', 'Cover jantan', 1, '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(14, 5, 10, 'indukan/5/10/0ab25839-7e09-440c-993b-809f5ddc414d.jpg', 'Cover betina', 1, '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(15, 5, 9, 'indukan/5/9/cd3977b2-038f-469c-81eb-9274079b4e24.jpg', 'Close-up jantan', 0, '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(16, 6, 11, 'indukan/6/11/384bd46f-98b5-4daa-8a85-78e14b1ffdc6.jpg', 'Cover jantan', 1, '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(17, 6, 12, 'indukan/6/12/dbf3a817-d5ff-4c8c-a127-3eadaab2e2d7.jpg', 'Cover betina', 1, '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(18, 6, 11, 'indukan/6/11/ba2a06ad-0440-4442-8748-cb4d1b71e63b.jpg', 'Close-up jantan', 0, '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(19, 7, 13, 'indukan/7/13/ab094151-f983-425e-99bd-f5c42b9c123a.jpg', 'Cover jantan', 1, '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(20, 7, 14, 'indukan/7/14/6d46a3dd-f392-46aa-a3aa-72f2cd7bf605.jpg', 'Cover betina', 1, '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(21, 7, 13, 'indukan/7/13/97669821-1b77-43fe-9590-1f01eee5ea8f.jpg', 'Close-up jantan', 0, '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(22, 9, 20, 'indukan/9/20/1759642104__MG_8370.JPG', 'Foto utama b21222', 1, '2025-10-04 22:28:24', '2025-10-04 22:28:24', NULL),
	(23, 9, 21, 'indukan/9/21/1759642145__MG_8534.JPG', 'Foto utama b31313', 1, '2025-10-04 22:29:05', '2025-10-04 22:29:05', NULL);

CREATE TABLE IF NOT EXISTS `indukan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `peternak_id` bigint unsigned NOT NULL,
  `nomor_ring` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_kelamin` enum('jantan','betina') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `prestasi` text COLLATE utf8mb4_unicode_ci,
  `karakteristik` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `indukan_peternak_id_nomor_ring_unique` (`peternak_id`,`nomor_ring`),
  CONSTRAINT `indukan_peternak_id_foreign` FOREIGN KEY (`peternak_id`) REFERENCES `peternak` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `indukan` (`id`, `peternak_id`, `nomor_ring`, `nama`, `jenis_kelamin`, `tanggal_lahir`, `catatan`, `prestasi`, `karakteristik`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1, 'J-1-620', 'Jantan 1', 'jantan', '1982-06-29', 'Catatan jantan', 'Juara Latber 4', NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(2, 1, 'B-1-712', 'Betina 1', 'betina', '2011-09-07', 'Catatan betina', NULL, 'Agresif, rajin makan', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(3, 2, 'J-2-913', 'Jantan 2', 'jantan', '2010-12-19', 'Catatan jantan', 'Juara Latber 2', NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(4, 2, 'B-2-877', 'Betina 2', 'betina', '1993-03-27', 'Catatan betina', NULL, 'Agresif, rajin makan', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(5, 3, 'J-3-790', 'Jantan 3', 'jantan', '2024-06-22', 'Catatan jantan', 'Juara Latber 2', NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(6, 3, 'B-3-276', 'Betina 3', 'betina', '1976-02-15', 'Catatan betina', NULL, 'Agresif, rajin makan', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(7, 4, 'J-4-179', 'Jantan 4', 'jantan', '1979-05-07', 'Catatan jantan', 'Juara Latber 4', NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(8, 4, 'B-4-118', 'Betina 4', 'betina', '1985-07-14', 'Catatan betina', NULL, 'Agresif, rajin makan', '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(9, 5, 'J-5-960', 'Jantan 5', 'jantan', '2018-10-16', 'Catatan jantan', 'Juara Latber 1', NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(10, 5, 'B-5-864', 'Betina 5', 'betina', '1997-11-07', 'Catatan betina', NULL, 'Agresif, rajin makan', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(11, 6, 'J-6-435', 'Jantan 6', 'jantan', '1979-02-21', 'Catatan jantan', 'Juara Latber 1', NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(12, 6, 'B-6-505', 'Betina 6', 'betina', '2024-05-07', 'Catatan betina', NULL, 'Agresif, rajin makan', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(13, 7, 'J-7-839', 'Jantan 7', 'jantan', '2002-11-05', 'Catatan jantan', 'Juara Latber 5', NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(14, 7, 'B-7-792', 'Betina 7', 'betina', '2024-01-06', 'Catatan betina', NULL, 'Agresif, rajin makan', '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(15, 9, 'J001', 'Raja Murai', 'jantan', '2020-01-15', 'Indukan jantan berkualitas tinggi', 'Juara 1 Kontes Murai 2023', NULL, '2025-10-04 22:04:17', '2025-10-04 22:04:17', NULL),
	(16, 9, 'B001', 'Ratu Murai', 'betina', '2020-03-20', 'Indukan betina yang produktif', NULL, 'Sangat jinak dan mudah dijinakkan', '2025-10-04 22:04:17', '2025-10-04 22:04:17', NULL),
	(17, 9, 'J002', 'Murai Champion', 'jantan', '2019-12-10', 'Indukan jantan berpengalaman', 'Juara 2 Kontes Murai 2022', NULL, '2025-10-04 22:04:17', '2025-10-04 22:04:17', NULL),
	(18, 9, 'B002', 'Murai Cantik', 'betina', '2021-02-05', 'Indukan betina muda', NULL, 'Aktif dan sehat', '2025-10-04 22:04:17', '2025-10-04 22:04:17', NULL),
	(20, 9, 'b21222', 'saber', 'jantan', '2025-05-08', 'ga ada', 'seperti itu', NULL, '2025-10-04 22:28:24', '2025-10-04 22:28:24', NULL),
	(21, 9, 'b31313', 'aslii', 'betina', '2025-10-24', 'gada', NULL, 'iyaa', '2025-10-04 22:29:05', '2025-10-04 22:29:05', NULL);

CREATE TABLE IF NOT EXISTS `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `peternak_id` bigint unsigned NOT NULL,
  `periode_mulai` date NOT NULL,
  `periode_selesai` date NOT NULL,
  `harga` int NOT NULL DEFAULT '25000',
  `status` enum('pending','paid','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `metode` enum('manual','midtrans') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `bukti_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoices_peternak_id_status_index` (`peternak_id`,`status`),
  KEY `invoices_peternak_id_periode_mulai_periode_selesai_index` (`peternak_id`,`periode_mulai`,`periode_selesai`),
  CONSTRAINT `invoices_peternak_id_foreign` FOREIGN KEY (`peternak_id`) REFERENCES `peternak` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `invoices` (`id`, `peternak_id`, `periode_mulai`, `periode_selesai`, `harga`, `status`, `metode`, `bukti_path`, `paid_at`, `created_at`, `updated_at`) VALUES
	(1, 1, '2025-09-26', '2025-10-26', 25000, 'paid', 'manual', NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(2, 2, '2025-09-29', '2025-10-29', 25000, 'paid', 'manual', NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(3, 3, '2025-09-24', '2025-10-24', 25000, 'paid', 'manual', NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02', '2025-10-02 20:27:02');

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `kandang` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `peternak_id` bigint unsigned NOT NULL,
  `nomor_kandang` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_kandang` text COLLATE utf8mb4_unicode_ci,
  `status` enum('kosong','bertelur','mengeram','menetas') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kosong',
  `indukan_jantan_id` bigint unsigned DEFAULT NULL,
  `indukan_betina_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kandang_peternak_id_nomor_kandang_unique` (`peternak_id`,`nomor_kandang`),
  KEY `kandang_indukan_jantan_id_foreign` (`indukan_jantan_id`),
  KEY `kandang_indukan_betina_id_foreign` (`indukan_betina_id`),
  CONSTRAINT `kandang_indukan_betina_id_foreign` FOREIGN KEY (`indukan_betina_id`) REFERENCES `indukan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `kandang_indukan_jantan_id_foreign` FOREIGN KEY (`indukan_jantan_id`) REFERENCES `indukan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `kandang_peternak_id_foreign` FOREIGN KEY (`peternak_id`) REFERENCES `peternak` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `kandang` (`id`, `peternak_id`, `nomor_kandang`, `deskripsi_kandang`, `status`, `indukan_jantan_id`, `indukan_betina_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1, 'K1-1', 'Quia non quod molestiae deleniti.', 'mengeram', 1, 2, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(2, 1, 'K1-2', 'Exercitationem est explicabo minima hic officiis.', 'kosong', 1, 2, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(3, 1, 'K1-3', 'Corrupti aut rerum est a numquam tempore nihil.', 'bertelur', 1, 2, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(4, 1, 'K1-4', 'Natus sed perferendis facere repellendus.', 'menetas', 1, 2, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(5, 2, 'K2-1', 'Eos laboriosam omnis tempore.', 'menetas', 3, 4, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(6, 2, 'K2-2', 'Pariatur omnis voluptatem facere.', 'mengeram', 3, 4, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(7, 2, 'K2-3', 'Ad et non assumenda aliquid unde.', 'mengeram', 3, 4, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(8, 2, 'K2-4', 'Excepturi optio et eos sed quia laudantium molestias.', 'mengeram', 3, 4, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(9, 2, 'K2-5', 'Provident itaque aut nesciunt saepe.', 'bertelur', 3, 4, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(10, 3, 'K3-1', 'Est voluptate rem eligendi nemo quibusdam ullam.', 'bertelur', 5, 6, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(11, 4, 'K4-1', 'Fugit ut quam asperiores cumque qui et necessitatibus.', 'kosong', 7, 8, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(12, 4, 'K4-2', 'Veniam similique et consequatur et.', 'menetas', 7, 8, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(13, 4, 'K4-3', 'Corporis libero earum laboriosam quaerat omnis sit quia similique.', 'bertelur', 7, 8, '2025-10-02 20:27:02', '2025-10-02 20:27:02', NULL),
	(14, 5, 'K5-1', 'Doloribus sit nesciunt commodi necessitatibus.', 'kosong', 9, 10, '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(15, 5, 'K5-2', 'Dolores enim animi vero dolor voluptate aut.', 'kosong', 9, 10, '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(16, 5, 'K5-3', 'Dolorum laborum accusamus voluptatum.', 'bertelur', 9, 10, '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(17, 5, 'K5-4', 'Qui voluptas qui doloremque quo.', 'menetas', 9, 10, '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(18, 6, 'K6-1', 'Amet magnam iure quam et.', 'bertelur', 11, 12, '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(19, 6, 'K6-2', 'Omnis sequi tempora tempore ducimus.', 'mengeram', 11, 12, '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(20, 6, 'K6-3', 'Mollitia eos totam aut sed.', 'menetas', 11, 12, '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(21, 6, 'K6-4', 'Alias doloribus quae numquam ut dolore ea incidunt.', 'kosong', 11, 12, '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(22, 7, 'K7-1', 'Molestias autem voluptatem consequatur in.', 'mengeram', 13, 14, '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(23, 7, 'K7-2', 'Eveniet nobis aliquam fugit quia est deserunt quis.', 'bertelur', 13, 14, '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(24, 7, 'K7-3', 'Eos dolor sit eos qui et quos laudantium.', 'mengeram', 13, 14, '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(25, 7, 'K7-4', 'Neque ullam molestiae maxime dolores non fugiat.', 'bertelur', 13, 14, '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(26, 7, 'K7-5', 'Assumenda occaecati explicabo eaque et praesentium molestias et quae.', 'bertelur', 13, 14, '2025-10-02 20:27:03', '2025-10-02 20:27:03', NULL),
	(27, 9, 'K001', 'Kandang utama untuk breeding', 'bertelur', 15, 16, '2025-10-04 22:04:17', '2025-10-04 22:04:17', NULL),
	(28, 9, 'K002', 'Kandang cadangan', 'kosong', 17, 18, '2025-10-04 22:04:17', '2025-10-04 22:04:17', NULL),
	(29, 9, 'K003', 'Kandang untuk anakan', 'menetas', NULL, NULL, '2025-10-04 22:04:17', '2025-10-04 22:04:17', NULL),
	(30, 9, 'A67', 'kaya gini lah dia', 'kosong', 20, 21, '2025-10-04 22:29:32', '2025-10-04 22:29:32', NULL);

CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_10_02_132834_rename_name_to_username_in_users_table', 1),
	(5, '2025_10_03_012522_create_admin_table', 1),
	(6, '2025_10_03_012925_create_peternak_table', 1),
	(7, '2025_10_03_013050_create_kandang_table', 1),
	(8, '2025_10_03_014031_create_indukan_table', 1),
	(9, '2025_10_03_014111_alter_kandang_add_deskripsi_and_fk_indukan', 1),
	(10, '2025_10_03_020151_create_perkawinan_table', 1),
	(11, '2025_10_03_020155_create_anakan_table', 1),
	(12, '2025_10_03_022143_create_foto_indukan_table', 1),
	(13, '2025_10_03_030202_create_transaksi_table', 1),
	(14, '2025_10_03_030633_create_invoices_table', 1);

CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `perkawinan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kandang_id` bigint unsigned NOT NULL,
  `indukan_jantan_id` bigint unsigned DEFAULT NULL,
  `indukan_betina_id` bigint unsigned DEFAULT NULL,
  `nomor_trip` int NOT NULL,
  `tanggal_kawin` date DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `perkawinan_unique_trip_per_kandang` (`kandang_id`,`nomor_trip`),
  KEY `perkawinan_indukan_jantan_id_foreign` (`indukan_jantan_id`),
  KEY `perkawinan_indukan_betina_id_foreign` (`indukan_betina_id`),
  CONSTRAINT `perkawinan_indukan_betina_id_foreign` FOREIGN KEY (`indukan_betina_id`) REFERENCES `indukan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `perkawinan_indukan_jantan_id_foreign` FOREIGN KEY (`indukan_jantan_id`) REFERENCES `indukan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `perkawinan_kandang_id_foreign` FOREIGN KEY (`kandang_id`) REFERENCES `kandang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `perkawinan` (`id`, `kandang_id`, `indukan_jantan_id`, `indukan_betina_id`, `nomor_trip`, `tanggal_kawin`, `catatan`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 2, 1, '2015-05-03', 'Quia dolor maxime id quae ut et dolore.', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(2, 1, 1, 2, 2, '2004-03-06', 'Modi laudantium quod veniam quaerat.', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(3, 1, 1, 2, 3, '1976-02-06', 'Porro aperiam delectus dignissimos est ea dolore id.', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(4, 2, 1, 2, 1, '2006-08-25', 'Nam voluptatibus et et earum qui quia omnis.', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(5, 3, 1, 2, 1, '1985-05-23', 'Et in explicabo eum et expedita aut.', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(6, 4, 1, 2, 1, '1983-08-07', 'Delectus voluptatem excepturi sequi sapiente vitae non nulla.', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(7, 4, 1, 2, 2, '1991-10-29', 'Tempora voluptatum alias qui doloribus qui cumque saepe harum.', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(8, 5, 3, 4, 1, '1987-08-05', 'Et nobis enim quo voluptas accusantium vel odit neque.', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(9, 5, 3, 4, 2, '2007-01-04', 'Est odio tenetur porro vel.', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(10, 5, 3, 4, 3, '2004-03-16', 'Eum et ex eaque omnis similique voluptas soluta.', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(11, 6, 3, 4, 1, '1996-11-01', 'Eligendi praesentium nisi amet culpa cupiditate odit adipisci.', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(12, 6, 3, 4, 2, '1980-04-14', 'Et et et quia sed quos.', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(13, 6, 3, 4, 3, '2024-02-16', 'Quibusdam et veniam ut qui consequatur.', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(14, 7, 3, 4, 1, '1995-07-14', 'Exercitationem cum quia accusantium blanditiis rem numquam.', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(15, 7, 3, 4, 2, '2024-11-26', 'Repellat nesciunt laboriosam autem.', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(16, 8, 3, 4, 1, '2007-05-23', 'Cum eos cum similique dolores doloribus voluptates qui perferendis.', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(17, 8, 3, 4, 2, '1983-09-18', 'Sed corrupti qui est est a.', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(18, 8, 3, 4, 3, '2013-03-23', 'Impedit sequi incidunt harum animi qui velit iusto.', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(19, 9, 3, 4, 1, '2018-10-01', 'Deserunt maiores aut vero ullam a occaecati.', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(20, 9, 3, 4, 2, '1992-12-30', 'Consectetur quis mollitia in eveniet.', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(21, 9, 3, 4, 3, '2018-09-17', 'Ut perspiciatis vel saepe voluptas.', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(22, 10, 5, 6, 1, '1996-02-28', 'Et assumenda ipsa temporibus quis facere non quos.', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(23, 11, 7, 8, 1, '2005-06-26', 'Necessitatibus facilis sed nihil pariatur ut.', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(24, 12, 7, 8, 1, '2020-06-03', 'Nihil officiis nemo doloribus.', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(25, 12, 7, 8, 2, '1982-01-04', 'Sint quia quia dolor ipsa incidunt qui.', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(26, 13, 7, 8, 1, '1984-05-15', 'Ut et rem nihil reprehenderit.', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(27, 13, 7, 8, 2, '1983-04-04', 'Ipsa nihil earum nihil enim harum aut doloremque.', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(28, 14, 9, 10, 1, '2005-02-26', 'Atque est sunt aut aspernatur ullam.', '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(29, 14, 9, 10, 2, '1991-10-18', 'Eos repellat sint blanditiis et ad.', '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(30, 15, 9, 10, 1, '2007-07-05', 'Enim eos nemo provident quibusdam.', '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(31, 16, 9, 10, 1, '2017-08-26', 'Pariatur voluptatem expedita et magni.', '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(32, 17, 9, 10, 1, '2022-05-17', 'Explicabo eum et atque est velit.', '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(33, 17, 9, 10, 2, '2022-03-07', 'Voluptatem deserunt eaque quam quasi.', '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(34, 17, 9, 10, 3, '2009-05-02', 'Dolor quia molestiae consequuntur.', '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(35, 18, 11, 12, 1, '2022-05-09', 'Dolor quaerat recusandae mollitia nesciunt sunt.', '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(36, 19, 11, 12, 1, '2017-09-25', 'Hic expedita atque inventore et consectetur qui.', '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(37, 19, 11, 12, 2, '1983-04-07', 'Est culpa velit et sit dolorum.', '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(38, 20, 11, 12, 1, '2005-09-06', 'Et ea quibusdam saepe facere iusto quidem.', '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(39, 21, 11, 12, 1, '1993-11-02', 'Consequatur et reiciendis et quia.', '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(40, 22, 13, 14, 1, '2000-12-05', 'Quaerat eos rerum consequuntur sunt odio et.', '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(41, 22, 13, 14, 2, '1999-07-05', 'Deserunt provident voluptatum iusto animi aut laudantium nihil dolore.', '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(42, 22, 13, 14, 3, '1991-03-09', 'Et eos omnis dicta fugiat.', '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(43, 23, 13, 14, 1, '2011-02-26', 'Illum voluptas aliquid illum quia rerum quibusdam qui.', '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(44, 24, 13, 14, 1, '1995-11-22', 'Minus voluptatem tenetur et porro est voluptates dolores.', '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(45, 24, 13, 14, 2, '2011-11-11', 'Aut laborum reprehenderit doloremque quos repellat.', '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(46, 24, 13, 14, 3, '2000-12-09', 'Voluptas eius illo unde corrupti nesciunt.', '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(47, 25, 13, 14, 1, '2023-08-23', 'Reiciendis laboriosam nostrum officia odit iure.', '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(48, 26, 13, 14, 1, '2003-01-23', 'Cumque enim rem consectetur sed.', '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(49, 26, 13, 14, 2, '1981-01-29', 'Eaque expedita similique repudiandae et aut quo.', '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(50, 26, 13, 14, 3, '1979-11-22', 'Laborum cupiditate aspernatur deleniti quae veritatis quam.', '2025-10-02 20:27:03', '2025-10-02 20:27:03');

CREATE TABLE IF NOT EXISTS `peternak` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `nama_peternakan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `nomor_handphone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_profil` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_akun` enum('free','pro') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'free',
  `pro_berlaku_hingga` date DEFAULT NULL,
  `periode_deteksi` char(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2025-10',
  `deteksi_terpakai` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `peternak_user_id_unique` (`user_id`),
  CONSTRAINT `peternak_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `peternak` (`id`, `user_id`, `nama_peternakan`, `alamat`, `nomor_handphone`, `foto_profil`, `jenis_akun`, `pro_berlaku_hingga`, `periode_deteksi`, `deteksi_terpakai`, `created_at`, `updated_at`) VALUES
	(1, 2, 'Murai Farm 1', 'Gg. Baik No. 834, Bitung 70912, Sumsel', '081893806028', NULL, 'pro', '2025-10-26', '2025-10', 2, '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(2, 3, 'Murai Farm 2', 'Jln. Flores No. 419, Yogyakarta 50087, Kaltara', '081714011446', NULL, 'pro', '2025-10-29', '2025-10', 6, '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(3, 4, 'Murai Farm 3', 'Jr. K.H. Maskur No. 307, Tebing Tinggi 39511, Papua', '088784037845', NULL, 'pro', '2025-10-24', '2025-10', 5, '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(4, 5, 'Murai Farm 4', 'Ds. Ujung No. 22, Serang 58940, Kalsel', '081495541244', NULL, 'free', NULL, '2025-10', 1, '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(5, 6, 'Murai Farm 5', 'Psr. Basuki Rahmat  No. 388, Tangerang 92637, NTB', '082437026463', NULL, 'free', NULL, '2025-10', 2, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(6, 7, 'Murai Farm 6', 'Gg. Bakhita No. 950, Pekanbaru 37072, Pabar', '084333705237', NULL, 'free', NULL, '2025-10', 0, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(7, 8, 'Murai Farm 7', 'Jr. Laksamana No. 657, Denpasar 93397, Sumsel', '082597077704', NULL, 'free', NULL, '2025-10', 2, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(8, 10, 'AbantosMomentos', 'Jalan kaliurang km.11', '082287110856', NULL, 'free', NULL, '2025-10', 0, '2025-10-04 20:14:01', '2025-10-04 20:14:01'),
	(9, 11, 'Peternakan Test', 'Jl. Test No. 123', '081234567890', NULL, 'free', NULL, '2025-10', 0, '2025-10-04 22:04:17', '2025-10-04 22:04:17');

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('LPL1YzhGzD79Y5uoT2VlLmFT97jqjWMcMboEFrNh', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR3hxRDlLM0tLck5hYTRWOGZoc1lKS2xxbG1Ndlc4MmpkcWtCeVlXdiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kZXRla3NpLXBlbnlha2l0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1760924816),
	('x1cO9Ioi8yjpiC3aWliIsEsxEVOloACT5KZESXBO', 11, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaTBrZ0tCaWZTUWh6anZ5U3VqMUp2SXB0WHVEY3FiZ0d6b2RESlhGNiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9rYW5kYW5nLzMwIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTE7fQ==', 1759643117);

CREATE TABLE IF NOT EXISTS `transaksi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `peternak_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `tipe` enum('pemasukan','pengeluaran') COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` enum('penjualan_anakan','penjualan_indukan','pemasukan_lainnya','pakan','vitamin','perawatan','pengeluaran_lainnya') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` decimal(12,2) NOT NULL,
  `nama_item` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `anakan_id` bigint unsigned DEFAULT NULL,
  `indukan_id` bigint unsigned DEFAULT NULL,
  `ring_referensi` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaksi_anakan_id_foreign` (`anakan_id`),
  KEY `transaksi_indukan_id_foreign` (`indukan_id`),
  KEY `transaksi_peternak_id_tanggal_index` (`peternak_id`,`tanggal`),
  KEY `transaksi_peternak_id_tipe_kategori_index` (`peternak_id`,`tipe`,`kategori`),
  CONSTRAINT `transaksi_anakan_id_foreign` FOREIGN KEY (`anakan_id`) REFERENCES `anakan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `transaksi_indukan_id_foreign` FOREIGN KEY (`indukan_id`) REFERENCES `indukan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `transaksi_peternak_id_foreign` FOREIGN KEY (`peternak_id`) REFERENCES `peternak` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `transaksi` (`id`, `peternak_id`, `tanggal`, `tipe`, `kategori`, `jumlah`, `nama_item`, `deskripsi`, `anakan_id`, `indukan_id`, `ring_referensi`, `created_at`, `updated_at`) VALUES
	(1, 1, '2025-10-03', 'pemasukan', 'penjualan_anakan', 1584698.00, NULL, 'Penjualan anakan', 4, NULL, 'A-1-1-AHTT', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(2, 1, '2025-10-03', 'pemasukan', 'penjualan_anakan', 1887334.00, NULL, 'Penjualan anakan', 7, NULL, 'A-1-2-TZYI', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(3, 1, '2025-10-03', 'pemasukan', 'penjualan_anakan', 455284.00, NULL, 'Penjualan anakan', 12, NULL, NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(4, 1, '2025-10-03', 'pemasukan', 'penjualan_anakan', 1098769.00, NULL, 'Penjualan anakan', 15, NULL, 'A-1-3-DCPQ', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(5, 1, '2025-10-03', 'pemasukan', 'penjualan_anakan', 1143435.00, NULL, 'Penjualan anakan', 20, NULL, 'A-1-4-0DLY', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(6, 1, '2025-09-29', 'pengeluaran', 'vitamin', 28410.00, 'Multivit Burung', 'Voluptates cum blanditiis voluptatum animi rerum reprehenderit.', NULL, NULL, NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(7, 1, '2025-09-24', 'pengeluaran', 'pakan', 85794.00, 'Voer Premium', 'Qui veniam repudiandae voluptatem ipsam rerum magni.', NULL, NULL, NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(8, 1, '2025-09-24', 'pengeluaran', 'pakan', 86244.00, 'Voer Premium', 'Fugit dolorem doloribus recusandae.', NULL, NULL, NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(9, 1, '2025-09-20', 'pengeluaran', 'vitamin', 59596.00, 'Multivit Burung', 'Provident nostrum sed recusandae.', NULL, NULL, NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(10, 1, '2025-09-26', 'pemasukan', 'pemasukan_lainnya', 57351.00, 'Hadiah lomba', 'Bonus event kecil', NULL, NULL, NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(11, 2, '2025-10-03', 'pemasukan', 'penjualan_anakan', 1084817.00, NULL, 'Penjualan anakan', 21, NULL, 'A-2-5-QLXO', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(12, 2, '2025-10-03', 'pemasukan', 'penjualan_anakan', 882108.00, NULL, 'Penjualan anakan', 23, NULL, 'A-2-5-K7FY', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(13, 2, '2025-10-03', 'pemasukan', 'penjualan_anakan', 1571881.00, NULL, 'Penjualan anakan', 29, NULL, 'A-2-7-GESE', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(14, 2, '2025-09-24', 'pengeluaran', 'pengeluaran_lainnya', 30318.00, 'Perawatan kandang', 'Necessitatibus reiciendis facilis enim repellat.', NULL, NULL, NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(15, 2, '2025-09-27', 'pengeluaran', 'pengeluaran_lainnya', 40615.00, 'Perawatan kandang', 'Porro architecto sit iusto et pariatur.', NULL, NULL, NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(16, 2, '2025-09-27', 'pengeluaran', 'pakan', 142669.00, 'Voer Premium', 'Voluptatem eligendi excepturi vitae doloremque dignissimos.', NULL, NULL, NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(17, 3, '2025-09-28', 'pengeluaran', 'pakan', 54793.00, 'Voer Premium', 'Occaecati non ratione dolorum autem.', NULL, NULL, NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(18, 3, '2025-09-18', 'pengeluaran', 'perawatan', 115513.00, 'Perawatan kandang', 'Qui fugit perspiciatis veniam ea.', NULL, NULL, NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(19, 3, '2025-10-02', 'pengeluaran', 'vitamin', 47748.00, 'Multivit Burung', 'Rerum distinctio veritatis nam perferendis.', NULL, NULL, NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(20, 3, '2025-09-22', 'pengeluaran', 'perawatan', 118423.00, 'Perawatan kandang', 'Aut cumque illo hic ut ut illum sed velit.', NULL, NULL, NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(21, 3, '2025-09-27', 'pengeluaran', 'pakan', 109104.00, 'Voer Premium', 'Quae tempore qui modi non qui illo laborum.', NULL, NULL, NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(22, 3, '2025-09-23', 'pengeluaran', 'perawatan', 62552.00, 'Perawatan kandang', 'Quasi aperiam odio ut magni.', NULL, NULL, NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(23, 3, '2025-10-01', 'pemasukan', 'pemasukan_lainnya', 119485.00, 'Hadiah lomba', 'Bonus event kecil', NULL, NULL, NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(24, 4, '2025-10-03', 'pemasukan', 'penjualan_anakan', 900716.00, NULL, 'Penjualan anakan', 41, NULL, NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(25, 4, '2025-10-03', 'pemasukan', 'penjualan_anakan', 629090.00, NULL, 'Penjualan anakan', 42, NULL, 'A-4-13-LH9L', '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(26, 4, '2025-09-18', 'pengeluaran', 'perawatan', 52500.00, 'Perawatan kandang', 'Dolor quaerat sint quis aspernatur.', NULL, NULL, NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(27, 4, '2025-09-22', 'pengeluaran', 'pakan', 32620.00, 'Voer Premium', 'Laboriosam debitis laborum quia aut aliquid.', NULL, NULL, NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(28, 4, '2025-10-01', 'pengeluaran', 'pengeluaran_lainnya', 40717.00, 'Perawatan kandang', 'Aliquam enim et nihil dolore error hic corporis.', NULL, NULL, NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(29, 4, '2025-09-28', 'pengeluaran', 'pengeluaran_lainnya', 109670.00, 'Perawatan kandang', 'Earum dolores fugit aut.', NULL, NULL, NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(30, 4, '2025-09-28', 'pengeluaran', 'vitamin', 97230.00, 'Multivit Burung', 'Autem vel tenetur explicabo velit quidem ut autem.', NULL, NULL, NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(31, 4, '2025-09-21', 'pengeluaran', 'pakan', 69855.00, 'Voer Premium', 'Quae illum dolorem et nihil aut quam harum non.', NULL, NULL, NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(32, 4, '2025-09-30', 'pengeluaran', 'vitamin', 95361.00, 'Multivit Burung', 'Hic et quo perspiciatis id et qui.', NULL, NULL, NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(33, 4, '2025-09-28', 'pemasukan', 'pemasukan_lainnya', 219943.00, 'Hadiah lomba', 'Bonus event kecil', NULL, NULL, NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(34, 5, '2025-10-03', 'pemasukan', 'penjualan_anakan', 1906182.00, NULL, 'Penjualan anakan', 50, NULL, 'A-5-16-UDE2', '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(35, 5, '2025-10-03', 'pemasukan', 'penjualan_anakan', 1448833.00, NULL, 'Penjualan anakan', 52, NULL, NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(36, 5, '2025-10-01', 'pengeluaran', 'pengeluaran_lainnya', 20175.00, 'Perawatan kandang', 'Est officiis et distinctio occaecati dolor est rerum.', NULL, NULL, NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(37, 5, '2025-09-22', 'pengeluaran', 'vitamin', 28308.00, 'Multivit Burung', 'Libero delectus voluptatem deserunt dignissimos.', NULL, NULL, NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(38, 5, '2025-09-19', 'pengeluaran', 'pakan', 108944.00, 'Voer Premium', 'Beatae totam ex quod fugit.', NULL, NULL, NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(39, 5, '2025-09-19', 'pengeluaran', 'perawatan', 43994.00, 'Perawatan kandang', 'Modi non fugiat cum.', NULL, NULL, NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(40, 5, '2025-09-22', 'pengeluaran', 'pakan', 144469.00, 'Voer Premium', 'Provident eum perspiciatis expedita doloribus dicta ex est.', NULL, NULL, NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(41, 5, '2025-09-25', 'pengeluaran', 'perawatan', 76627.00, 'Perawatan kandang', 'Iusto mollitia occaecati sed quia qui et.', NULL, NULL, NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(42, 5, '2025-09-28', 'pemasukan', 'pemasukan_lainnya', 250806.00, 'Hadiah lomba', 'Bonus event kecil', NULL, NULL, NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(43, 6, '2025-10-03', 'pemasukan', 'penjualan_anakan', 1030977.00, NULL, 'Penjualan anakan', 54, NULL, 'A-6-19-E0FK', '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(44, 6, '2025-10-03', 'pemasukan', 'penjualan_anakan', 936676.00, NULL, 'Penjualan anakan', 55, NULL, 'A-6-20-WUVK', '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(45, 6, '2025-09-18', 'pengeluaran', 'pengeluaran_lainnya', 94626.00, 'Perawatan kandang', 'Sequi vel facere qui quia autem.', NULL, NULL, NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(46, 6, '2025-09-18', 'pengeluaran', 'vitamin', 67123.00, 'Multivit Burung', 'Labore cupiditate omnis molestiae quia.', NULL, NULL, NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(47, 6, '2025-10-03', 'pengeluaran', 'vitamin', 20615.00, 'Multivit Burung', 'Voluptatem facilis non sunt ipsum nostrum ex est.', NULL, NULL, NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(48, 6, '2025-09-18', 'pengeluaran', 'pakan', 83580.00, 'Voer Premium', 'Iusto et eum sed qui non.', NULL, NULL, NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(49, 6, '2025-10-01', 'pemasukan', 'pemasukan_lainnya', 145045.00, 'Hadiah lomba', 'Bonus event kecil', NULL, NULL, NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(50, 7, '2025-10-03', 'pemasukan', 'penjualan_anakan', 1975741.00, NULL, 'Penjualan anakan', 67, NULL, 'A-7-24-NTO4', '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(51, 7, '2025-09-30', 'pengeluaran', 'vitamin', 72228.00, 'Multivit Burung', 'Ducimus consequuntur molestias ut commodi sequi aperiam.', NULL, NULL, NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(52, 7, '2025-09-20', 'pengeluaran', 'pengeluaran_lainnya', 37513.00, 'Perawatan kandang', 'Tempora aut autem accusantium vel id.', NULL, NULL, NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(53, 7, '2025-09-24', 'pengeluaran', 'pengeluaran_lainnya', 148602.00, 'Perawatan kandang', 'Rerum unde ipsam laborum tenetur aut in ducimus cupiditate.', NULL, NULL, NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(54, 7, '2025-09-22', 'pengeluaran', 'pengeluaran_lainnya', 133841.00, 'Perawatan kandang', 'Doloremque ullam magnam aspernatur et quasi qui accusamus amet.', NULL, NULL, NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(55, 7, '2025-09-19', 'pengeluaran', 'perawatan', 133984.00, 'Perawatan kandang', 'Voluptate veritatis et in.', NULL, NULL, NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(56, 7, '2025-10-01', 'pemasukan', 'pemasukan_lainnya', 248462.00, 'Hadiah lomba', 'Bonus event kecil', NULL, NULL, NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03');

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `username`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'admin', NULL, NULL, '$2y$12$Qhe94VS6ZFmnOKRVJ8c4J.D7TIir0P9Ackmnw0MIgBRQH3iX/FtiO', NULL, '2025-10-02 20:27:01', '2025-10-02 20:27:01'),
	(2, 'peternak1', NULL, NULL, '$2y$12$uA5r1/fvBeVyoNuYtGAq8esxaPpKVBzgU6lduuYmW7gkbpeOTrV7W', NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(3, 'peternak2', NULL, NULL, '$2y$12$eLzklHqYFMY5nahzpKIy/.kny74Mphueh/f5qjQbjqacqwpd99Z3O', NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(4, 'peternak3', NULL, NULL, '$2y$12$DEB6Vt2CZFNhpqLjN9pe6OPnH/qiyPx.Pu4/4EAC03saufIoWnn36', NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(5, 'peternak4', NULL, NULL, '$2y$12$4og9o9N2Pggp4mjGA1iVbely0ozt64GMamlg8rRxESAul7yfeL5mK', NULL, '2025-10-02 20:27:02', '2025-10-02 20:27:02'),
	(6, 'peternak5', NULL, NULL, '$2y$12$oo5z3iZQrn4yHOPnSUX1pecVSxqIajqEbFMJ7pjKxV875iQ1VdtEG', NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(7, 'peternak6', NULL, NULL, '$2y$12$YVC1n6kQLag2vhvOkVgkI.EezXJkIng6UVG6KPCZfYvrNonPJBSqS', NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(8, 'peternak7', NULL, NULL, '$2y$12$zOWE67coXPczNJXTRaK7qeQ8ApC/5oZ8t13a7rNIgCaP93BzLBk8e', NULL, '2025-10-02 20:27:03', '2025-10-02 20:27:03'),
	(10, 'Rakha', NULL, NULL, '$2y$12$MVajjivaRoFojSUtG2RQteVNbW8GxgNpECcOAvAHDu7nzkyyeLb8S', NULL, '2025-10-04 20:14:01', '2025-10-04 20:14:01'),
	(11, 'peternak_test', NULL, NULL, '$2y$12$4uGWl3XpINaoB7QjbcuGkOAmd8nXqN4z9ojZb8TxTTPXGvRDyfhuO', NULL, '2025-10-04 22:04:17', '2025-10-04 22:04:17');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
