<?php

declare(strict_types=1);

namespace Microwin7\TextureProvider\Texture\Storage;

use TypeError;
use Microwin7\TextureProvider\Config;
use Microwin7\PHPUtils\Helpers\FileSystem;
use Microwin7\TextureProvider\Utils\Cache;
use Microwin7\TextureProvider\Utils\GDUtils;
use Microwin7\TextureProvider\Texture\Texture;
use Microwin7\TextureProvider\Data\TextureProperty;
use Microwin7\PHPUtils\Utils\Texture as TextureUtils;
use Microwin7\PHPUtils\Exceptions\FileSystemException;
use Microwin7\TextureProvider\Request\Provider\RequestParams;
use Microwin7\PHPUtils\Contracts\Texture\Enum\ResponseTypeEnum;
use Microwin7\PHPUtils\Contracts\Texture\Enum\TextureStorageTypeEnum;
use Microwin7\PHPUtils\Utils\Path;

class DefaultType
{
    public          ?string             $skinData = null;
    public          ?int                $skinLastModified = null;
    /** @psalm-suppress PropertyNotSetInConstructor */
    public readonly string              $skinUrl;
    /** @psalm-suppress PropertyNotSetInConstructor */
    public readonly bool                $skinSlim;

    public          ?string             $capeData = null;
    public          ?int                $capeLastModified = null;
    /** @psalm-suppress PropertyNotSetInConstructor */
    public readonly string              $capeUrl;

    private readonly    FileSystem      $fileSystem;
    /**
     * @param string|null $skinID Передаётся для переопределния, при прасваении
     * @param string|null $capeID Передаётся для переопределния, при прасваении
     */
    function __construct(
        ResponseTypeEnum    $responseType,
        bool                $skinAlreadyDetected,
        bool                $capeAlreadyDetected,
        public  ?string     &$skinID = null,
        public  ?string     &$capeID = null
    ) {
        $this->fileSystem = new FileSystem;
        if (
            $skinAlreadyDetected === false && (Config::GIVE_DEFAULT_SKIN() || $responseType === ResponseTypeEnum::AVATAR) &&
            in_array($responseType, [ResponseTypeEnum::JSON, ResponseTypeEnum::SKIN, ResponseTypeEnum::AVATAR])
        ) {
            $this->getSkinData();
            /** @var string $this->skinData */
            if ($responseType === ResponseTypeEnum::SKIN) Texture::ResponseTexture($this->skinData, $this->skinLastModified);
            $this->skinUrl = $this->getSkinUrl();
            $this->skinSlim = GDUtils::slim($this->skinData);
        }
        if (
            $capeAlreadyDetected === false && Config::GIVE_DEFAULT_CAPE() &&
            in_array($responseType, [
                ResponseTypeEnum::JSON,
                ResponseTypeEnum::CAPE,
                ResponseTypeEnum::CAPE_RESIZE
            ])
        ) {
            $this->getCapeData();
            if ($responseType === ResponseTypeEnum::CAPE) Texture::ResponseTexture($this->capeData, $this->capeLastModified);
            $this->capeUrl = $this->getCapeUrl();
        }
    }
    private function getSkinData(): void
    {
        $this->skinID = TextureUtils::SKIN_DEFAULT_SHA256();
        if ($this->fileSystem->is_file($skinPath = TextureUtils::PATH(ResponseTypeEnum::SKIN, $this->skinID))) {
            $this->skinData = file_get_contents($skinPath);
            $this->skinLastModified = Cache::getLastModified($skinPath);
        } else {
            $this->skinID = null;
            $this->skinData = TextureUtils::SKIN_DEFAULT();
        }
    }
    private function getSkinUrl(): string
    {
        return (string)(new RequestParams)
            ->withEnum(ResponseTypeEnum::SKIN)
            ->withEnum($this->skinID === null ? TextureStorageTypeEnum::DEFAULT : TextureStorageTypeEnum::STORAGE)
            ->setVariable('login', $this->skinID);
    }
    private function getCapeData(): void
    {
        $this->capeID = TextureUtils::CAPE_DEFAULT_SHA256();
        if ($this->fileSystem->is_file($capePath = TextureUtils::PATH(ResponseTypeEnum::CAPE, $this->capeID))) {
            $this->capeData = file_get_contents($capePath);
            $this->capeLastModified = Cache::getLastModified($capePath);
        } else {
            $this->capeID = null;
            $this->capeData = TextureUtils::CAPE_DEFAULT();
        }
    }
    private function getCapeUrl(): string
    {
        return (string)(new RequestParams)
            ->withEnum(ResponseTypeEnum::CAPE)
            ->withEnum($this->capeID === null ? TextureStorageTypeEnum::DEFAULT : TextureStorageTypeEnum::STORAGE)
            ->setVariable('login', $this->capeID);
    }
}
