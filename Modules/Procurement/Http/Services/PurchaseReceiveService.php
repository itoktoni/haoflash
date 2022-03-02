<?php

namespace Modules\Procurement\Http\Services;

use Illuminate\Support\Facades\DB;
use Modules\Item\Dao\Enums\CategoryType;
use Modules\Procurement\Dao\Enums\PurchaseStatus;
use Modules\Procurement\Dao\Facades\PoDetailFacades;
use Modules\Procurement\Dao\Facades\PoFacades;
use Modules\Procurement\Dao\Facades\StockFacades;
use Modules\System\Dao\Interfaces\CrudInterface;
use Modules\System\Plugins\Alert;
use Modules\System\Plugins\Helper;

class PurchaseReceiveService
{
    public function save($repository, $data)
    {
        $check = false;
        try {
            DB::beginTransaction();
            $check = $repository->create($data->all());

            if ($data->po_receive_type == CategoryType::Accesories) {

                StockFacades::create([
                    'stock_code' => Helper::autoNumber(StockFacades::getTable(), StockFacades::getKeyName(), 'SN' . date('Ym'), 20),
                    'stock_po_code' => $data->po_receive_po_code,
                    'stock_po_receive_code' => $data->po_receive_code,
                    'stock_branch_id' => $data->po_receive_branch_id,
                    'stock_product_id' => $data->po_receive_product_id,
                    'stock_qty' => $data->po_receive_receive,
                    'stock_sell' => $data->po_receive_sell,
                    'stock_buy' => $data->po_receive_buy,
                ]);
            } elseif ($data->po_receive_type == CategoryType::Virtual) {
                foreach (range($data->po_receive_start, $data->po_receive_end) as $selisih) {
                    StockFacades::create([
                        'stock_code' => $selisih,
                        'stock_po_code' => $data->po_receive_po_code,
                        'stock_po_receive_code' => $data->po_receive_code,
                        'stock_branch_id' => $data->po_receive_branch_id,
                        'stock_product_id' => $data->po_receive_product_id,
                        'stock_qty' => 1,
                        'stock_expired' => $data->po_receive_expired_date,
                        'stock_sell' => $data->po_receive_sell,
                        'stock_buy' => $data->po_receive_buy,
                    ]);
                }
            } elseif ($data->po_receive_type == CategoryType::BDP) {
                foreach (range($data->po_receive_start, $data->po_receive_end) as $selisih) {
                    StockFacades::create([
                        'stock_po_code' => $data->po_receive_po_code,
                        'stock_po_receive_code' => $data->po_receive_code,
                        'stock_branch_id' => $data->po_receive_branch_id,
                        'stock_product_id' => $data->po_receive_product_id,
                        'stock_qty' => 1,
                        'stock_expired' => $data->po_receive_expired_date,
                        'stock_sell' => $data->po_receive_sell,
                        'stock_buy' => $data->po_receive_buy,
                    ]);
                }
            }

            // $po = [PoFacades::mask_status() => PurchaseStatus::Receive];
            // if ($data->complete == 1) {
            //     $po = [PoFacades::mask_status() => PurchaseStatus::Finish];
            // }

            // PoFacades::where(PoFacades::getKeyName(), $data->po_receive_po_code)->update($po);

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
