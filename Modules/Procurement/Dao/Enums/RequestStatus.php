<?php

namespace Modules\Procurement\Dao\Enums;

use BenSampo\Enum\Enum;
use Modules\System\Dao\Enums\ColorType;
use Modules\System\Dao\Traits\StatusTrait;

class RequestStatus extends Enum
{
    use StatusTrait;

    const Create            =  1;
    const Done            =  2;
    const Cancel            =  3;

    public static function colors()
    {
        return [
            self::Create => ColorType::Warning,
            self::Done => ColorType::Primary,
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
