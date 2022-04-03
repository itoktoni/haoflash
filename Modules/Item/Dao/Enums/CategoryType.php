<?php

namespace Modules\Item\Dao\Enums;

use BenSampo\Enum\Enum;
use Modules\System\Dao\Enums\ColorType;
use Modules\System\Dao\Traits\StatusTrait;

class CategoryType extends Enum
{
    use StatusTrait;

    const Type                  =  null;
    const Virtual               =  1;
    const BDP                   =  2;
    const Accesories            =  3;

    public static function colors()
    {
        return [
            self::Type => ColorType::Primary,
            self::Virtual => ColorType::Success,
            self::BDP => ColorType::Danger,
            self::Accesories => ColorType::Danger,
        ];
    }

    public static function getDescription($value): string
    {
        if ($value === self::Type) {
            return '- Pilih Type -';
        }

        if ($value === self::Virtual) {
            return 'Voucher Perdana';
        }

        return parent::getDescription($value);
    }

    public static function name()
    {
        return 'Category Type';
    }
}
