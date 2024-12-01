<?php

namespace Microwin7\TextureProvider\Utils;

use Microwin7\PHPUtils\Utils\Texture;
use Microwin7\TextureProvider\Config;
use Microwin7\PHPUtils\Helpers\FileSystem;
use Microwin7\PHPUtils\Response\JsonResponse;
use Microwin7\TextureProvider\Data\TextureProperty;
use Microwin7\PHPUtils\Exceptions\FileSystemException;
use Microwin7\PHPUtils\Exceptions\NeedRe_GenerateCache;
use Microwin7\PHPUtils\Contracts\Texture\Enum\ResponseTypeEnum;
use Microwin7\PHPUtils\Contracts\Texture\Enum\TextureStorageTypeEnum;

class IndexSkinRandomCollection
{
    /** @var list<array{file: string, hash: string}> */
    private array $indexArray = [];
    private string $indexPath = __DIR__ . '/../../cache/index.json';
    private bool $regenerated = false;
    /**
     * Генерация index файла коллекции
     * Только для вызова командой
     */
    public function generateIndex(): int
    {
        $fileSystem = new FileSystem;
        $files = $fileSystem->findFiles(Texture::TEXTURE_STORAGE_FULL_PATH(TextureStorageTypeEnum::COLLECTION), Texture::EXTENSTION());
        foreach ($files as $file) {
            $data = file_get_contents($file);

            $this->indexArray[] = [
                'file' => str_replace(Texture::TEXTURE_STORAGE_FULL_PATH(TextureStorageTypeEnum::COLLECTION), '', $file),
                'hash' => Texture::digest($data),
            ];
        }
        $directory = dirname($this->indexPath);
        if (!$fileSystem->is_dir($directory))
            FileSystem::mkdir($directory);
        file_put_contents(
            $this->indexPath,
            JsonResponse::json_encode($this->indexArray)
        );
        return count($files);
    }
    /** 
     * @throw NeedRe_GenerateCache
     * @return array{0: string, 1: int}|null - данные и время модификации
     */
    public function getDataFromUUID(string $uuid): ?array
    {
        try {
            $uuiddec = hexdec(substr($uuid, -12));
            if (($file = file_get_contents($this->indexPath)) !== false) {
                /** @var list<object{file: string, hash: string}> */
                $fileData = json_decode($file);
                if (($count = count($fileData)) > 0) {
                    $modulo = $uuiddec % $count;
                    $index = $fileData[$modulo];
                    $filename = Texture::TEXTURE_STORAGE_FULL_PATH(TextureStorageTypeEnum::COLLECTION) . $index->file;
                    ($data = file_get_contents($filename)) !== false ?: throw new NeedRe_GenerateCache;
                    if (Config::SKIN_RESIZE()) {
                        $textureProperty = new TextureProperty($data);
                        if (($textureProperty->w / 2) === $textureProperty->h) {
                            $data = (new GDUtils(ResponseTypeEnum::SKIN_RESIZE, skinProperty: $textureProperty))->getResultData();
                            file_put_contents($filename, $data) !== false ?: throw FileSystemException::createForbidden(dirname($filename));
                        }
                    }
                    $hash = Texture::digest($data);
                    if ($index->hash !== $hash) throw new NeedRe_GenerateCache;
                    return [$data, Cache::getLastModified($filename)];
                }
            }
        } catch (NeedRe_GenerateCache) {
            if (Config::TRY_REGENERATE_CACHE() && !$this->regenerated) {
                $this->regenerated = true;
                if ($this->generateIndex() > 0) $this->getDataFromUUID($uuid);
            } else throw new NeedRe_GenerateCache;
        }
        return null;
    }
}
