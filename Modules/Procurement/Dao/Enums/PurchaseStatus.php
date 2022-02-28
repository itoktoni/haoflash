<?php

namespace Modules\Procurement\Dao\Enums;

use BenSampo\Enum\Enum;
use Modules\System\Dao\Enums\ColorType;
use Modules\System\Dao\Traits\StatusTrait;

class PurchaseStatus extends Enum
{
    use StatusTrait;

    const Create            =  1;
    const Process           =  2;
    const Receive           =  3;
    const Finish            =  4;
    const Cancel            =  5;

    public static function colors()
    {
        return [
            self::Create => ColorType::Warning,
            self::Process => ColorType::Primary,
            self::Receive => ColorType::Brown,
            self::Finish => ColorType::Success,
            self::Cancel => ColorType::Danger,
        ];
    }

    public static function name()
    {
        return 'Purchase Status';
    }

    public static function getDescription($value): string
    {
        return parent::getDescription($value);
    }
}
