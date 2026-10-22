USE db_santri;

ALTER TABLE santri
ADD COLUMN angkatan INT NULL AFTER nama;
