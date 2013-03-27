ALTER TABLE users ADD COLUMN language varchar(255) NOT NULL DEFAULT 'pt-BR' AFTER last_login;
