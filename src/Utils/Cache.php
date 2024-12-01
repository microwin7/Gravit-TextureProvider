<?php

namespace Microwin7\TextureProvider\Utils;

use Microwin7\PHPUtils\Utils\Texture;
use Microwin7\PHPUtils\Helpers\FileSystem;
use Microwin7\PHPUtils\Contracts\Texture\Enum\ResponseTypeEnum;
use Microwin7\PHPUtils\Exceptions\FileSystemException;

/** ПЕРЕПИСАТЬ */
class Cache
{
    public static function saveGDTexture(string $login, \GdImage $canvas, ResponseTypeEnum $responseType, ?int $size): void
    {
        $directory = Texture::TEXTURE_STORAGE_FULL_PATH($responseType, $size);
        if (!file_exists($directory))
            FileSystem::mkdir($directory);
        $filename = Texture::PATH($responseType, $login, Texture::EXTENSTION(), $size);
        imagepng($canvas, $filename, 9) ?: throw FileSystemException::createForbidden($directory);
    }
    public static function loadCacheFile(string $filename): string
    {
        return file_get_contents($filename);
    }
    public static function getLastModified(string $filename): int
    {
        return lstat($filename)['ctime'];
    }
    /**
     * @param ResponseTypeEnum::SKIN|ResponseTypeEnum::CAPE $responseType
     */
    public static function resetUserCachedFiles(ResponseTypeEnum $responseType, string $login): void
    {
        if ($responseType === ResponseTypeEnum::SKIN) {
            foreach (
                [
                    ResponseTypeEnum::AVATAR,
                    ResponseTypeEnum::FRONT,
                    ResponseTypeEnum::FRONT_WITH_CAPE,
                    ResponseTypeEnum::BACK,
                    ResponseTypeEnum::BACK_WITH_CAPE
                ] as $type
            ) {
                try {
                    foreach ((new FileSystem)->findFiles(Texture::TEXTURE_STORAGE_FULL_PATH($type), Texture::EXTENSTION(), 1) as $file) {
                        if (pathinfo($file, PATHINFO_BASENAME) === ($login . Texture::EXTENSTION())) {
                            unlink($file);
                        }
                    }
                } catch (FileSystemException $e) {
                    if (!$e->isErrorFolderNotExist()) throw $e;
                }
            }
        }
        if ($responseType === ResponseTypeEnum::CAPE) {
            foreach (
                [
                    ResponseTypeEnum::FRONT_CAPE,
                    ResponseTypeEnum::FRONT_WITH_CAPE,
                    ResponseTypeEnum::BACK,
                    ResponseTypeEnum::BACK_WITH_CAPE,
                    ResponseTypeEnum::CAPE_RESIZE
                ] as $type
            ) {
                try {
                    foreach ((new FileSystem)->findFiles(Texture::TEXTURE_STORAGE_FULL_PATH($type), Texture::EXTENSTION(), 1) as $file) {
                        if (pathinfo($file, PATHINFO_BASENAME) === ($login . Texture::EXTENSTION())) {
                            unlink($file);
                        }
                    }
                } catch (FileSystemException $e) {
                    if (!$e->isErrorFolderNotExist()) throw $e;
                }
            }
        }
    }
}
