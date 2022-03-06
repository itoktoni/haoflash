<?php

namespace Modules\Procurement\Dao\Enums;

use BenSampo\Enum\Enum;
use Modules\System\Dao\Enums\ColorType;
use Modules\System\Dao\Traits\StatusTrait;

class SupplierPph extends Enum
{
    use StatusTrait;

    const Type                  =  null;
    const PPH                   =  1;
    const NON_PPH               =  2;

    public static function colors()
    {
        return [
            self::Type => ColorType::Primary,
            self::PPH => ColorType::Success,
            self::NON_PPH => ColorType::Danger,
        ];
    }

    public static function getDescription($value): string
    {
        if ($value === self::Type) {
            return '- Type -';
        }

        return parent::getDescription($value);
    }

    public static function name()
    {
        return 'Supplier Type';
    }
}
