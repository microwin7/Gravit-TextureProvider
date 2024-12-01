<?php

namespace Microwin7\TextureProvider\Utils;

use GdImage;
use Microwin7\PHPUtils\Contracts\Texture\Enum\ResponseTypeEnum;
use Microwin7\TextureProvider\Config;
use Microwin7\TextureProvider\Data\TextureProperty;
use Microwin7\PHPUtils\Utils\GDUtils as UtilsGDUtils;

class GDUtils extends UtilsGDUtils
{
    // public TextureProperty|null $skinProperty = null;
    // public TextureProperty|null $capeProperty = null;
    private \GdImage|null $canvas = null;

    // case FRONT_CAPE         = 5;
    // case FRONT_WITH_CAPE    = 6;
    // case BACK_CAPE          = 8;
    // case BACK_WITH_CAPE     = 9;
    public function __construct(
        ResponseTypeEnum $needResult,
        string|null $skinData = null,
        string|null $capeData = null,
        int|null $needSize = null,
        public TextureProperty|null &$skinProperty = null,
        public TextureProperty|null &$capeProperty = null,

    ) {
        if ($skinProperty === null && $skinData !== null) $this->skinProperty = new TextureProperty($skinData);
        if ($capeProperty === null && $capeData !== null) $this->capeProperty = new TextureProperty($capeData);

        if ($needResult === ResponseTypeEnum::SKIN_RESIZE && $this->skinProperty !== null) {
            if (Config::SKIN_RESIZE() && ($this->skinProperty->w / 2 === $this->skinProperty->h)) {
                $this->canvas = parent::create_canvas_transparent($this->skinProperty->w, $this->skinProperty->w);
                imagecopy($this->canvas, $this->skinProperty->image, 0, 0, 0, 0, $this->skinProperty->w, $this->skinProperty->h);
                $this->skin_resize();
            } else {
                $this->canvas = $this->skinProperty->image;
            }
        }
        if ($needResult === ResponseTypeEnum::FRONT && $this->skinProperty !== null && $needSize !== null) {
            $this->canvas = parent::create_canvas_transparent($needSize, $needSize * 2);
            $this->front($needSize);
        }
        // if ($needResult === ResponseTypeEnum::FRONT_CAPE && $this->capeProperty !== null && $needSize !== null) {
        //     $cape_canvas = (new self(
        //         ResponseTypeEnum::CAPE_RESIZE,
        //         needSize: 1,
        //         capeProperty: $this->capeProperty
        //     ))->getResultGD();
        //     $this->canvas = self::create_canvas_transparent(imagesx($cape_canvas), imagesy($cape_canvas));
        //     $this->front_cape($needSize);
        // }
        if ($needResult === ResponseTypeEnum::BACK && $this->skinProperty !== null && $needSize !== null) {
            $this->canvas = parent::create_canvas_transparent($needSize, $needSize * 2);
            $this->back($needSize);
        }
        if ($needResult === ResponseTypeEnum::CAPE_RESIZE && $this->capeProperty !== null && $needSize !== null) {
            $this->canvas = parent::create_canvas_transparent($needSize * 22, $needSize * 17);
            $this->cape_resize($needSize);
        }
        if ($needResult === ResponseTypeEnum::AVATAR && $this->skinProperty !== null && $needSize !== null) {
            $this->canvas = parent::create_canvas_transparent($needSize, $needSize);
            $this->avatar($needSize);
        }
    }
    public function getResultGD(): \GdImage
    {
        return $this->canvas ?? throw new \RuntimeException('Canvas result null');
    }
    public function getResultData(): string
    {
        ob_start();
        imagepng($this->canvas ?? throw new \RuntimeException('Canvas result null'));
        return ob_get_clean();
    }
    /** Transorm skin 2x1 to 2x2 */
    private function skin_resize(): void
    {
        /**
         * @var TextureProperty $this->skinProperty
         * @var \GdImage $this->canvas
         */
        $fraction = $this->skinProperty->fraction;
        /** @var int $f_part */
        $f_part = $fraction / 2;

        $left_leg = $left_arm = parent::create_canvas_transparent($f_part * 3, $f_part * 3); // 12x12
        imagecopy($left_leg, $this->skinProperty->image, 0, 0, 0, $f_part * 5, $f_part * 3, $fraction * 4); // 0, 20 >> 12, 32
        imageflip($left_leg, IMG_FLIP_HORIZONTAL);
        imagecopy($this->canvas, $left_leg, $fraction * 2, $f_part * 13, 0, 0, $f_part * 3, $f_part * 3);

        $left_leg2 = $left_arm2 = parent::create_canvas_transparent($f_part, $f_part * 3); // 4x12
        imagecopy($left_leg2, $this->skinProperty->image, 0, 0, $f_part * 3, $f_part * 5, $fraction * 2, $fraction * 4); // 12, 20 >> 16, 32
        imageflip($left_leg2, IMG_FLIP_HORIZONTAL);
        imagecopy($this->canvas, $left_leg2, $f_part * 7, $f_part * 13, 0, 0, $f_part, $f_part * 3);

        imagecopy($left_arm, $this->skinProperty->image, 0, 0, $fraction * 5, $f_part * 5, $f_part * 13, $fraction * 4); // 40, 20 >> 52, 32
        imageflip($left_arm, IMG_FLIP_HORIZONTAL);
        imagecopy($this->canvas, $left_arm, $fraction * 4, $f_part * 13, 0, 0, $f_part * 3, $f_part * 3);

        imagecopy($left_arm2, $this->skinProperty->image, 0, 0, $f_part * 13, $f_part * 5, $fraction * 7, $fraction * 4); // 52, 20 >> 56, 32
        imageflip($left_arm2, IMG_FLIP_HORIZONTAL);
        imagecopy($this->canvas, $left_arm2, $f_part * 11, $f_part * 13, 0, 0, $f_part, $f_part * 3);

        $square = $square2 = $square3 = $square4 = parent::create_canvas_transparent($f_part, $f_part); //4x4
        imagecopy($square, $this->skinProperty->image, 0, 0, $f_part, $fraction * 2, $fraction, $f_part * 5); // 4, 16 >> 8, 20
        imageflip($square, IMG_FLIP_HORIZONTAL);
        imagecopy($this->canvas, $square, $f_part * 5, $fraction * 6, 0, 0, $f_part, $f_part);

        imagecopy($square2, $this->skinProperty->image, 0, 0, $fraction, $fraction * 2, $f_part * 3, $f_part * 5); // 8, 16 >> 12, 20
        imageflip($square2, IMG_FLIP_HORIZONTAL);
        imagecopy($this->canvas, $square2, $fraction * 3, $fraction * 6, 0, 0, $f_part, $f_part);

        imagecopy($square3, $this->skinProperty->image, 0, 0, $f_part * 11, $fraction * 2, $fraction * 6, $f_part * 5); // 44, 16 >> 48, 20
        imageflip($square3, IMG_FLIP_HORIZONTAL);
        imagecopy($this->canvas, $square3, $f_part * 9, $fraction * 6, 0, 0, $f_part, $f_part);

        imagecopy($square4, $this->skinProperty->image, 0, 0, $fraction * 6, $fraction * 2, $f_part * 13, $f_part * 5); // 48, 16 >> 52, 20
        imageflip($square4, IMG_FLIP_HORIZONTAL);
        imagecopy($this->canvas, $square4, $fraction * 5, $fraction * 6, 0, 0, $f_part, $f_part);
    }
    /**
     * size не меньше 64 сделать и кратным 64
     * ПОМЕНЯТЬ ПРОВЕРКУ РАЗМЕРА БЛОКА
     */
    private function front(int $size): void
    {
        /**
         * @var TextureProperty $this->skinProperty
         * @var \GdImage $this->canvas
         */
        $isSlim = parent::checkSkinSlimFromImage($this->skinProperty->image);

        /** @var int $f_part */
        $f_part = $this->skinProperty->fraction / 2;

        $block_size = (int) ($size / 2); // 128 -> 64
        $block_size_1_2 = (int) ($block_size / 2); // 32

        $block_size_3_2 = $block_size_1_2 * 3; // 96
        $block_size_under = self::size_under($block_size); // 58
        $block_size_indent = (int) (($block_size - $block_size_under) / 2); // 3
        $block_size_indent_1_2 = (int) ($block_size_indent / 2); // 1.5 ERROR
        $canvas_arm_right = parent::create_canvas_transparent($block_size_1_2, $block_size_3_2);
        $canvas_arm_left = parent::create_canvas_transparent($block_size_1_2, $block_size_3_2);
        $canvas_leg_right = parent::create_canvas_transparent($block_size_1_2, $block_size_3_2);
        $canvas_leg_left = parent::create_canvas_transparent($block_size_1_2, $block_size_3_2);

        // BODY 1
        imagecopyresized(
            $this->canvas,
            $this->skinProperty->image,
            $block_size_1_2 + $block_size_indent,
            $block_size,
            $f_part * 5,
            $f_part * 5,
            $block_size_under,
            $block_size_3_2 - $block_size_indent,
            $f_part * 2,
            $f_part * 3
        );
        // ============= LEG'S ============= //
        // RL 1
        imagecopyresized(
            $canvas_leg_right,
            $this->skinProperty->image,
            $block_size_indent_1_2,
            $block_size_indent_1_2,
            $f_part * 1,
            $f_part * 5,
            imagesx($canvas_leg_right) - ($block_size_indent_1_2 * 2),
            imagesy($canvas_leg_right) - ($block_size_indent_1_2 * 2),
            $f_part * 1,
            $f_part * 3
        );
        if ($this->skinProperty->w === $this->skinProperty->h) {
            // LL 1
            imagecopyresized(
                $canvas_leg_left,
                $this->skinProperty->image,
                $block_size_indent_1_2,
                $block_size_indent_1_2,
                $f_part * 5,
                $f_part * 13,
                imagesx($canvas_leg_left) - ($block_size_indent_1_2 * 2),
                imagesy($canvas_leg_left) - ($block_size_indent_1_2 * 2),
                $f_part * 1,
                $f_part * 3
            );
            // RL 2
            imagecopyresized(
                $canvas_leg_right,
                $this->skinProperty->image,
                0,
                0,
                $f_part * 1,
                $f_part * 9,
                imagesx($canvas_leg_right),
                imagesy($canvas_leg_right),
                $f_part * 1,
                $f_part * 3
            );
            // LL 2
            imagecopyresized(
                $canvas_leg_left,
                $this->skinProperty->image,
                0,
                0,
                $f_part * 1,
                $f_part * 13,
                imagesx($canvas_leg_left),
                imagesy($canvas_leg_left),
                $f_part * 1,
                $f_part * 3
            );
        } else {
            imagecopy($canvas_leg_left, $canvas_leg_right, 0, 0, 0, 0, imagesx($canvas_leg_right), imagesy($canvas_leg_right));
            imageflip($canvas_leg_left, IMG_FLIP_HORIZONTAL);
        }
        imagecopy(
            $this->canvas,
            $canvas_leg_right,
            $block_size_1_2 + $block_size_indent_1_2,
            $block_size + $block_size_3_2 - $block_size_indent - $block_size_indent_1_2,
            0,
            0,
            imagesx($canvas_leg_right),
            imagesy($canvas_leg_right)
        );
        imagecopy(
            $this->canvas,
            $canvas_leg_left,
            $block_size - $block_size_indent_1_2,
            $block_size + $block_size_3_2 - $block_size_indent - $block_size_indent_1_2,
            0,
            0,
            imagesx($canvas_leg_left),
            imagesy($canvas_leg_left)
        );

        // ============= ARM'S ============= //
        if (!$isSlim) {
            // RA 1
            imagecopyresized(
                $canvas_arm_right,
                $this->skinProperty->image,
                $block_size_indent_1_2,
                $block_size_indent_1_2,
                $f_part * 11,
                $f_part * 5,
                imagesx($canvas_arm_right) - ($block_size_indent_1_2 * 2),
                imagesy($canvas_arm_right) - ($block_size_indent_1_2 * 2),
                $f_part * 1,
                $f_part * 3
            );
        } else {
            // RA 1 SLIM | ALWAYS X === Y
            imagecopyresized(
                $canvas_arm_right,
                $this->skinProperty->image,
                $block_size_indent_1_2 + ($block_size_1_2 / 4),
                $block_size_indent_1_2,
                $f_part * 11,
                $f_part * 5,
                ($block_size_1_2 / 4 * 3) - $block_size_indent,
                imagesy($canvas_arm_right) - ($block_size_indent_1_2 * 2),
                $f_part / 4 * 3,
                $f_part * 3
            );
        }

        if ($this->skinProperty->w === $this->skinProperty->h) {
            if (!$isSlim) {
                // LA 1
                imagecopyresized(
                    $canvas_arm_left,
                    $this->skinProperty->image,
                    $block_size_indent_1_2,
                    $block_size_indent_1_2,
                    $f_part * 9,
                    $f_part * 13,
                    $block_size_1_2 - $block_size_indent,
                    $block_size_3_2 - $block_size_indent,
                    $f_part * 1,
                    $f_part * 3
                );
                // RA 2
                imagecopyresized(
                    $canvas_arm_right,
                    $this->skinProperty->image,
                    0,
                    0,
                    $f_part * 11,
                    $f_part * 9,
                    imagesx($canvas_arm_right),
                    imagesy($canvas_arm_right),
                    $f_part * 1,
                    $f_part * 3
                );
                // LA 2
                imagecopyresized(
                    $canvas_arm_left,
                    $this->skinProperty->image,
                    0,
                    0,
                    $f_part * 13,
                    $f_part * 13,
                    imagesx($canvas_arm_left),
                    imagesy($canvas_arm_left),
                    $f_part * 1,
                    $f_part * 3
                );
            } else {
                // LA 1 SLIM
                imagecopyresized(
                    $canvas_arm_left,
                    $this->skinProperty->image,
                    $block_size_indent_1_2,
                    $block_size_indent_1_2,
                    $f_part * 9,
                    $f_part * 13,
                    ($block_size_1_2 / 4 * 3) - $block_size_indent,
                    $block_size_3_2 - $block_size_indent,
                    $f_part / 4 * 3,
                    $f_part * 3
                );
                // RA 2 SLIM
                imagecopyresized(
                    $canvas_arm_right,
                    $this->skinProperty->image,
                    $block_size_1_2 / 4,
                    0,
                    $f_part * 13,
                    $f_part * 13,
                    imagesx($canvas_arm_right) / 4 * 3,
                    imagesy($canvas_arm_right),
                    $f_part / 4 * 3,
                    $f_part * 3
                );
                // LA 2 SLIM
                imagecopyresized(
                    $canvas_arm_left,
                    $this->skinProperty->image,
                    0,
                    0,
                    $f_part * 13,
                    $f_part * 13,
                    imagesx($canvas_arm_left) / 4 * 3,
                    imagesy($canvas_arm_left),
                    $f_part / 4 * 3,
                    $f_part * 3
                );
            }
        } else {
            imagecopy($canvas_arm_left, $canvas_arm_right, 0, 0, 0, 0, imagesx($canvas_arm_right), imagesy($canvas_arm_right));
            imageflip($canvas_arm_left, IMG_FLIP_HORIZONTAL);
        }
        imagecopy(
            $this->canvas,
            $canvas_arm_right,
            $block_size_indent + $block_size_indent_1_2,
            $block_size - $block_size_indent_1_2,
            0,
            0,
            imagesx($canvas_arm_right),
            imagesy($canvas_arm_right)
        );
        imagecopy(
            $this->canvas,
            $canvas_arm_left,
            $block_size + $block_size_1_2 - $block_size_indent - $block_size_indent_1_2,
            $block_size - $block_size_indent_1_2,
            0,
            0,
            imagesx($canvas_arm_left),
            imagesy($canvas_arm_left)
        );
        if ($this->skinProperty->w === $this->skinProperty->h) {
            // BODY 2
            imagecopyresized(
                $this->canvas,
                $this->skinProperty->image,
                $block_size_1_2 + ((int) ($block_size_indent / 4)),
                $block_size,
                $f_part * 5,
                $f_part * 9,
                $block_size - $block_size_indent_1_2,
                $block_size_3_2,
                $f_part * 2,
                $f_part * 3
            );
        }
        // AVATAR
        imagecopy($this->canvas, (new self(ResponseTypeEnum::AVATAR, needSize: $block_size, skinProperty: $this->skinProperty))->getResultGD(), $block_size_1_2, $block_size_indent, 0, 0, $block_size, $block_size);
    }
    /**
     * Создано пока что только для скинов по шаблону 64x32
     */
    private function back(int $size): void
    {
        /**
         * @var TextureProperty $this->skinProperty
         * @var \GdImage $this->canvas
         */
        $fraction = $this->skinProperty->fraction;
        /** @var int $f_part */
        $f_part = $fraction / 2;
        $canvas_back = parent::create_canvas_transparent($fraction * 2, $fraction * 4);
        $canvas_arm = parent::create_canvas_transparent($f_part, $f_part * 3);
        $canvas_leg = $canvas_arm;
        // Head
        imagecopy($canvas_back, $this->skinProperty->image, $f_part, 0, $fraction * 3, $fraction, $fraction, $fraction);
        //Helmet
        imagecopy($canvas_back, $this->skinProperty->image, $f_part, 0, $fraction * 7, $fraction, $fraction, $fraction);
        // Torso
        imagecopy($canvas_back, $this->skinProperty->image, $f_part, $f_part * 2, $f_part * 8, $f_part * 5, $f_part * 2, $f_part * 3);
        //Left Arm
        imagecopy($canvas_arm, $this->skinProperty->image, 0, 0, $f_part * 13, $f_part * 5, $f_part, $f_part * 3);
        imagecopy($canvas_back, $canvas_arm, $f_part * 3, $f_part * 2, 0, 0, $f_part, $f_part * 3);
        //Right Arm
        imageflip($canvas_arm, IMG_FLIP_HORIZONTAL);
        imagecopy($canvas_back, $canvas_arm, 0, $f_part * 2, 0, 0, $f_part, $f_part * 3);
        //Left Leg
        imagecopy($canvas_leg, $this->skinProperty->image, 0, 0, $f_part * 3, $f_part * 5, $f_part, $f_part * 3);
        imagecopy($canvas_back, $canvas_leg, $f_part * 2, $f_part * 5, 0, 0, $f_part, $f_part * 3);
        //Right Leg
        imageflip($canvas_leg, IMG_FLIP_HORIZONTAL);
        imagecopy($canvas_back, $canvas_leg, $f_part, $f_part * 5, 0, 0, $f_part, $f_part * 3);
        //Resize
        imagecopyresized($this->canvas, $canvas_back, 0, 0, 0, 0,   $size, $size * 2, $fraction * 2, $fraction * 4);
    }

    private function avatar(int $size): void
    {
        /**
         * @var TextureProperty $this->skinProperty
         * @var \GdImage $this->canvas
         */
        $fraction = $this->skinProperty->fraction;
        $size_under = self::size_under($size);
        /** @var int $size_indent */
        $size_indent = ($size - $size_under) / 2;
        imagecopyresized(
            $this->canvas,
            $this->skinProperty->image,
            $size_indent,
            $size_indent,
            $fraction,
            $fraction,
            $size_under,
            $size_under,
            $fraction,
            $fraction
        );
        imagecopyresized(
            $this->canvas,
            $this->skinProperty->image,
            0,
            0,
            $fraction * 5,
            $fraction,
            $size,
            $size,
            $fraction,
            $fraction
        );
    }
    /** Коэфициент от блока */
    private static function size_under(int $size): int
    {
        $size_under = (int)floor($size / 1.1);
        if ($size_under % 2 !== 0) $size_under--;
        return $size_under;
    }
    /**
     * @param int $size - размер одного пикселя
     */
    private function cape_resize(int $size): void
    {
        /**
         * @var TextureProperty $this->capeProperty
         * @var \GdImage $this->canvas
         * @var int $f_part
         */
        $f_part = $this->capeProperty->fraction / 8;
        imagecopyresized(
            $this->canvas,
            $this->capeProperty->image,
            0,
            0,
            0,
            0,
            $size * 22,
            $size * 17,
            $f_part * 22,
            $f_part * 17
        );
    }
}
