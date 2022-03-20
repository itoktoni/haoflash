<?php

namespace Modules\Procurement\Dao\Enums;

use BenSampo\Enum\Enum;
use Modules\System\Dao\Enums\ColorType;
use Modules\System\Dao\Traits\StatusTrait;

class DeliveryStatus extends Enum
{
    use StatusTrait;

    const Create            =  1;
    const Prepare           =  2;
    const Ready             =  3;
    const Receive           =  4;
    const Finish            =  5;
    const Cancel            =  6;

    public static function colors()
    {
        return [
            self::Create => ColorType::Warning,
            self::Prepare => ColorType::Primary,
            self::Ready => ColorType::Grey,
            self::Receive => ColorType::Brown,
            self::Finish => ColorType::Success,
            self::Cancel => ColorType::Danger,
        ];
    }

    public static function name()
    {
        return 'Delivery Status';
    }

    public static function getDescription($value): string
    {
        return parent::getDescription($value);
    }
}
