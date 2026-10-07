ALTER TABLE usuarios
    ADD COLUMN auth_provider VARCHAR(20) NOT NULL DEFAULT 'local' AFTER celular,
    ADD COLUMN google_sub VARCHAR(255) NULL AFTER auth_provider,
    ADD COLUMN email_verificado_em DATETIME NULL AFTER google_sub,
    ADD UNIQUE KEY ux_usuarios_google_sub (google_sub);
