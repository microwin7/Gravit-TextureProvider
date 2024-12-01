<?php

declare(strict_types=1);

namespace Microwin7\TextureProvider\Texture\Storage;

use TypeError;
use Microwin7\TextureProvider\Config;
use Microwin7\TextureProvider\Utils\GDUtils;
use Microwin7\TextureProvider\Texture\Texture;
use Microwin7\TextureProvider\Request\Provider\RequestParams;
use Microwin7\PHPUtils\Contracts\Texture\Enum\ResponseTypeEnum;
use Microwin7\TextureProvider\Utils\IndexSkinRandomCollection;
use Microwin7\PHPUtils\Contracts\Texture\Enum\TextureStorageTypeEnum;

class CollectionType
{
    public              ?string             $skinData = null;
    public              ?int                $skinLastModified = null;
    /** @psalm-suppress PropertyNotSetInConstructor */
    public readonly     string              $skinUrl;
    /** @psalm-suppress PropertyNotSetInConstructor */
    public readonly     bool                $skinSlim;
    public readonly     null                $capeData;
    public readonly     string              $capeUrl;

    private IndexSkinRandomCollection       $index;

    function __construct(
        public readonly string              $uuid,
        ResponseTypeEnum    $responseType
    ) {
        $this->index = new IndexSkinRandomCollection;
        $this->getSkinData();
        if ($this->skinData !== null) {
            if ($responseType === ResponseTypeEnum::SKIN) Texture::ResponseTexture($this->skinData, $this->skinLastModified);
            $this->skinUrl = $this->getSkinUrl();
            $this->skinSlim = GDUtils::slim($this->skinData);
        }

        $this->capeData = null;
        $this->capeUrl = '';
    }
    private function getSkinData(): void
    {
        [$this->skinData, $this->skinLastModified] = $this->index->getDataFromUUID($this->uuid);
    }
    private function getSkinUrl(): string
    {
        return (string)(new RequestParams)
            ->withEnum(ResponseTypeEnum::SKIN)
            ->withEnum(TextureStorageTypeEnum::COLLECTION)
            ->setVariable('login', $this->uuid);
    }
}
