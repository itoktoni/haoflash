<?php

namespace Modules\Procurement\Dao\Enums;

use BenSampo\Enum\Enum;
use Modules\System\Dao\Enums\ColorType;
use Modules\System\Dao\Traits\StatusTrait;

class PurchasePayment extends Enum
{
    use StatusTrait;

    const Paid              =  1;
    const Unpaid            =  2;
    const instalment      =  3;

    public static function colors()
    {
        return [
            self::Paid => ColorType::Success,
            self::Unpaid => ColorType::Warning,
            self::instalment => ColorType::Primary,
        ];
    }

    public static function name()
    {
        return 'Purchase Payment';
    }

    public static function getDescription($value): string
    {
        return parent::getDescription($value);
    }
}
