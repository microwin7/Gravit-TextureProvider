CREATE TYPE "texture_provider_assets_meta" AS ENUM('SLIM');

CREATE TYPE "texture_provider_assets_type" AS ENUM('SKIN', 'CAPE');

CREATE TABLE texture_provider_user_assets (
    user_id int4 NOT NULL,
    "type" "texture_provider_assets_type" NOT NULL,
    hash varchar NULL,
    "meta" "texture_provider_assets_meta" NULL,
    CONSTRAINT assets_pk PRIMARY KEY (user_id, type),
    CONSTRAINT assets_users_fk FOREIGN KEY (user_id) REFERENCES texture_provider_users (id)
);