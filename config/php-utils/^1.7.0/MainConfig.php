<?php

namespace Microwin7\PHPUtils\Configs;

class MainConfig
{
    public const array DB_PDO_OPTIONS = [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_PERSISTENT => true
    ];

    /** @var array<string, array<string, mixed>> */
    public const array SERVERS = [];
    /** @var array<string, array<string, string|array<string, string>>> */
    public const array MODULES = [
        'LuckPerms' => [
            'DB_NAME' => 'LuckPerms',
            'prefix' => 'luckperms_',
        ],
        'TextureProvider' => [
            /** Driver Connect Database */
            'DB_NAME' => 'texture_provider',
            'table_user' => [
                'TABLE_NAME' => 'texture_provider_users',
                /**
                 * Колонка связывания с table_user_assets
                 * Либо для получения User ID
                 * Example:
                 * 'user_id' for UserStorageTypeEnum::DB_USER_ID,
                 */
                'id_column' => 'id',
                'username_column' => 'username',
                'uuid_column' => 'uuid',
            ],
            /**
             * For UserStorageTypeEnum::DB_SHA1
             * or UserStorageTypeEnum::DB_SHA256
             */
            'table_user_assets' => [
                'TABLE_NAME' => 'texture_provider_user_assets',
                /**
                 * Колонка связывания с table_user
                 */
                'id_column' => 'user_id',
                /**
                 * key-of<ResponseTypeEnum::SKIN|ResponseTypeEnum::CAPE>
                 */
                'texture_type_column' => 'type',
                'hash_column' => 'hash',
                /** NULL|SLIM */
                'texture_meta_column' => 'meta',
            ],
        ],
    ];
}
