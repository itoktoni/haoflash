<?php

namespace Modules\System\Dao\Enums;

use BenSampo\Enum\Enum;
use Modules\System\Dao\Enums\ColorType;
use Modules\System\Dao\Traits\StatusTrait;

class ActionStatus extends Enum
{
    const Create = 'Create';   
    const Update = 'Update';  
    const Edit = 'Edit';  
    const Show = 'Show';  
    const Delete = 'Delete';  
    const Data = 'Data';  
    const Index = 'Index';  
}
