<?php

namespace Modules\Procurement\Http\Services;

use Illuminate\Support\Facades\DB;
use Modules\Item\Dao\Enums\CategoryType;
use Modules\Procurement\Dao\Enums\DeliveryStatus;
use Modules\Procurement\Dao\Facades\DeDetailFacades;
use Modules\Procurement\Dao\Facades\DeFacades;
use Modules\Procurement\Dao\Facades\DePrepareFacades;
use Modules\Procurement\Dao\Facades\StockFacades;
use Modules\System\Plugins\Alert;
use Modules\System\Plugins\Helper;

class DeliveryPrepareService
{
    public function save($repository, $data)
    {
        $check = false;
        try {
            DB::beginTransaction();
            $check = $repository->create($data->all());
            if ($data->do_prepare_type == CategoryType::Accesories) {

                $stock = StockFacades::select(
                    [
                        StockFacades::getKeyName(), StockFacades::mask_qty(),
                    ])
                    ->where(StockFacades::mask_product_id(), $data->do_prepare_product_id)
                    ->where(StockFacades::mask_supplier_id(), $data->do_prepare_supplier_id)
                    ->where(StockFacades::mask_branch_id(), env('BRANCH_ID'))
                    ->where(StockFacades::mask_transfer(), 0);

                    $stock_sum = $stock->sum(StockFacades::mask_qty());
                    $pengurangan = $stock_sum - $data->do_prepare_prepare;

                $array = [
                    'stock_code' => Helper::autoNumber(StockFacades::getTable(), StockFacades::mask_code(), 'SN' . date('Ym'), 20),
                    'stock_primary_code' => $data->do_prepare_do_code,
                    'stock_reference_code' => $data->do_prepare_code,
                    'stock_branch_id' => env('BRANCH_ID'),
                    'stock_product_id' => $data->do_prepare_product_id,
                    'stock_supplier_id' => $data->do_prepare_supplier_id,
                    'stock_qty' => $data->do_prepare_prepare,
                    'stock_sell' => $data->do_prepare_sell,
                    'stock_buy' => $data->do_prepare_buy,
                    'stock_transfer' => 1,
                    'stock_type' => CategoryType::Accesories,
                ];

                $stock->delete();
                StockFacades::create($array);

                if ($pengurangan != 0) {

                    $array = [
                        'stock_code' => Helper::autoNumber(StockFacades::getTable(), StockFacades::mask_code(), 'SN' . date('Ym'), 20),
                        'stock_primary_code' => $data->do_prepare_do_code,
                        'stock_reference_code' => $data->do_prepare_code,
                        'stock_branch_id' => env('BRANCH_ID'),
                        'stock_product_id' => $data->do_prepare_product_id,
                        'stock_supplier_id' => $data->do_prepare_supplier_id,
                        'stock_qty' => $pengurangan,
                        'stock_sell' => $data->do_prepare_sell,
                        'stock_buy' => $data->do_prepare_buy,
                        'stock_transfer' => 0,
                        'stock_type' => CategoryType::Accesories,
                    ];

                    StockFacades::create($array);
                }
            } elseif ($data->do_prepare_type == CategoryType::Virtual) {

                StockFacades::whereIn(StockFacades::mask_code(), $data->serial)->update([
                    'stock_sell' => $data->do_prepare_sell,
                    'stock_sell' => $data->do_prepare_sell,
                    'stock_primary_code' => $data->do_prepare_do_code,
                    'stock_reference_code' => $data->do_prepare_code,
                    'stock_transfer' => 1,
                ]);
            }

            $total_prepare_qty = DePrepareFacades::where(DePrepareFacades::mask_do_code(), $data->do_prepare_do_code)->sum(DePrepareFacades::mask_prepare());
            $total_detail_qty = DeDetailFacades::where(DeDetailFacades::mask_do_code(), $data->do_prepare_do_code)->sum(DeDetailFacades::mask_qty());

            $po = [DeFacades::mask_status() => DeliveryStatus::Prepare, DeFacades::getUpdatedAtColumn() => date('Y-m-d H:i:s')];
            if ($total_prepare_qty == $total_detail_qty) {
                $po = [DeFacades::mask_status() => DeliveryStatus::Ready, DeFacades::getUpdatedAtColumn() => date('Y-m-d H:i:s')];
            }

            DeFacades::where(DeFacades::getKeyName(), $data->do_prepare_do_code)->update($po);

            if ($check->count() > 0) {

                DB::commit();
                Alert::create();
            } else {
                DB::rollBack();
                $message = env('APP_DEBUG') ? $check['data'] : $check['message'];
                Alert::error($message);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            if (isset($ex->errorInfo[0]) && $ex->errorInfo[0] == 23000) {

                $error = 'Serial Number sudah ada di database !';
                Alert::error($error);
                return $error;
            }
            DB::rollBack();
            Alert::error($ex->getMessage());
            return $ex->getMessage();
        } catch (\Throwable $th) {
            if (isset($th->errorInfo[0]) && $th->errorInfo[0] == 23000) {

                $error = 'Serial Number sudah ada di database !';
                Alert::error($error);
                return $error;
            }
            DB::rollBack();
            Alert::error($th->getMessage());
            return $th->getMessage();
        }

        return $check;
    }
}
