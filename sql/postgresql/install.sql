DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'texture_provider_assets_meta') THEN
        CREATE TYPE "texture_provider_assets_meta" AS ENUM('SLIM');
    END IF;

    IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'texture_provider_assets_type') THEN
        CREATE TYPE "texture_provider_assets_type" AS ENUM('SKIN', 'CAPE');
    END IF;
END
$$;

CREATE TABLE IF NOT EXISTS texture_provider_users (
    id serial4 NOT NULL,
    username varchar NULL,
    "uuid" varchar NULL,
    CONSTRAINT pk PRIMARY KEY (id),
    CONSTRAINT username_un UNIQUE (username),
    CONSTRAINT uuid_un UNIQUE (uuid)
);

CREATE TABLE IF NOT EXISTS texture_provider_user_assets (
    user_id int4 NOT NULL,
    "type" "texture_provider_assets_type" NOT NULL,
    hash varchar NULL,
    "meta" "texture_provider_assets_meta" NULL,
    CONSTRAINT assets_pk PRIMARY KEY (user_id, type),
    CONSTRAINT assets_users_fk FOREIGN KEY (user_id) REFERENCES texture_provider_users (id)
);
