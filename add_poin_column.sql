-- Add poin column to prestasis table
ALTER TABLE `prestasis` ADD COLUMN `poin` INT NOT NULL DEFAULT 0 AFTER `jenis_prestasi_id`;

-- Update existing data to copy poin from jenis_prestasis
UPDATE prestasis p
JOIN jenis_prestasis jp ON p.jenis_prestasi_id = jp.id
SET p.poin = jp.poin_reward;
