<?php

namespace Modules\Finance\Dao\Enums;

use BenSampo\Enum\Enum;
use Modules\System\Dao\Enums\ColorType;
use Modules\System\Dao\Traits\StatusTrait;

class PaymentType extends Enum
{
    use StatusTrait;

    const PENDING       =  null;
    const IN            =  1;
    const OUT           =  2;

    public static function colors()
    {
        return [
            self::PENDING => ColorType::Primary,
            self::IN => ColorType::Success,
            self::OUT => ColorType::Danger,
        ];
    }

    public static function name()
    {
        return 'Payment Status';
    }
}
