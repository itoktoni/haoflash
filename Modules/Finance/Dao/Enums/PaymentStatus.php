<?php

namespace Modules\Finance\Dao\Enums;

use BenSampo\Enum\Enum;
use Modules\System\Dao\Enums\ColorType;
use Modules\System\Dao\Traits\StatusTrait;

class PaymentStatus extends Enum
{
    use StatusTrait;

    const Waiting           =  null;
    const Submit            =  1;
    const Approve           =  2;
    const Reject            =  3;

    public static function colors()
    {
        return [
            self::Waiting => ColorType::Warning,
            self::Submit => ColorType::Primary,
            self::Approve => ColorType::Success,
            self::Reject => ColorType::Danger,
        ];
    }

    public static function name()
    {
        return false;
    }
}
