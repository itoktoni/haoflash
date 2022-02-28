<?php

namespace Modules\Finance\Dao\Enums;

use BenSampo\Enum\Enum;
use Modules\System\Dao\Enums\ColorType;
use Modules\System\Dao\Traits\StatusTrait;

class PaymentModel extends Enum
{
    use StatusTrait;

    const PaymentModel                  =  '';
    const PaymentPurchase               =  1;

    public static function colors()
    {
        return [
            self::PaymentModel => ColorType::Info,
            self::PaymentPurchase => ColorType::Brown,
        ];
    }

    public static function name()
    {
        return 'Payment Status';
    }
}
