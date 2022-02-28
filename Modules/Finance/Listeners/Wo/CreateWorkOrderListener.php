<?php

namespace Modules\Finance\Listeners\Wo;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Finance\Dao\Facades\LinenDetailFacades;
use Modules\Finance\Events\RegisterLinenEvent;
use Modules\Linen\Dao\Enums\LinenStatus;
use Modules\Finance\Events\PaymentApproveEvent;

class CreateWorkOrderListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(PaymentApproveEvent $event)
    {
        if($event->detail){
            $grouping = $event->detail->mapToGroups(function($item){
                return [$item->mask_supplier_id => $item];
            });
        }
    }
}
