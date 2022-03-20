<?php

namespace Modules\Procurement\Http\Services;

use Illuminate\Validation\Rule;
use Modules\Procurement\Dao\Facades\DePrepareFacades;
use Modules\Procurement\Dao\Facades\PoReceiveFacades;
use Modules\Procurement\Dao\Facades\StockFacades;
use Modules\System\Dao\Interfaces\CrudInterface;
use Modules\System\Http\Services\DeleteService;
use Modules\System\Plugins\Alert;

class DeletePrepareService
{
    public function delete($code)
    {
        $check = DePrepareFacades::with(['has_detail'])->find($code);
        
        if ($check) {
            $stock = $check->has_detail()->update([
                StockFacades::mask_transfer() => null
            ]);
            $check->delete();
        }

        if ($check) {
            Alert::delete();
        } else {
            Alert::error($check);
        }

        if (request()->wantsJson()) {

            return response()->json($check)->getData();
        }

        return $check;
    }

    public function deleteTransaction($repository, $master, $code)
    {
        $rules = [
            'master' => 'required',
            'code' => 'required'
        ];

        request()->validate($rules, ['code.required' => 'Please select any data !']);

        $check = $repository->deleteTransactionRepository($master, $code);

        if (request()->wantsJson()) {

            if ($check) {
                return response()->json($check)->getData();
            }
            return response()->json($check)->getData();
        }

        if ($check) {
            Alert::delete();
        } else {
            Alert::error($check);
        }

        return $check;
    }
}
