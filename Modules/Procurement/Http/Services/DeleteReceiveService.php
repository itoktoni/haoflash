<?php

namespace Modules\Procurement\Http\Services;

use Illuminate\Validation\Rule;
use Modules\Procurement\Dao\Facades\PoReceiveFacades;
use Modules\Procurement\Dao\Facades\StockFacades;
use Modules\System\Dao\Interfaces\CrudInterface;
use Modules\System\Http\Services\DeleteService;
use Modules\System\Plugins\Alert;

class DeleteReceiveService
{
    public function delete($code)
    {
        $check = PoReceiveFacades::find($code);
        if ($check) {
            StockFacades::where(StockFacades::mask_receive_code(), $code)->delete();
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
