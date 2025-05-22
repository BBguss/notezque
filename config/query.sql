-- gunakan query disini untuk melakukan update
-- TABEL USER
ALTER TABLE `users` ADD `aktivitas_terakhir` DATETIME NULL AFTER `quiz_answer`;
-- END TABEL USER 