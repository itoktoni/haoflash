<?php

namespace Modules\Finance\Dao\Enums;

use BenSampo\Enum\Enum;
use Modules\System\Dao\Enums\ColorType;
use Modules\System\Dao\Traits\StatusTrait;

class PaymentMethod extends Enum
{
    use StatusTrait;

    const PaymentMethod       =  null;
    const Cash            =  1;
    const Transfer           =  2;

    public static function colors()
    {
        return [
            self::PaymentMethod => ColorType::Primary,
            self::Cash => ColorType::Success,
            self::Transfer => ColorType::Danger,
        ];
    }

    public static function name()
    {
        return 'Payment Status';
    }
}
