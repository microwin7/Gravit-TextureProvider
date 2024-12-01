CREATE TABLE texture_provider_users (
    id serial4 NOT NULL,
    username varchar NULL,
    "uuid" varchar NULL,
    CONSTRAINT pk PRIMARY KEY (id),
    CONSTRAINT username_un UNIQUE (username),
    CONSTRAINT uuid_un UNIQUE (uuid)
);