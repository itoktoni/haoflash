<?php

namespace Modules\Procurement\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Finance\Dao\Enums\PaymentStatus;
use Modules\Finance\Dao\Repositories\BankRepository;
use Modules\Finance\Dao\Repositories\PaymentRepository;
use Modules\Item\Dao\Repositories\CategoryRepository;
use Modules\Item\Dao\Repositories\ProductRepository;
use Modules\Procurement\Dao\Enums\PurchasePayment;
use Modules\Procurement\Dao\Enums\PurchaseStatus;
use Modules\Procurement\Dao\Enums\SupplierType;
use Modules\Procurement\Dao\Facades\PoDetailFacades;
use Modules\Procurement\Dao\Facades\PoReceiveFacades;
use Modules\Procurement\Dao\Models\PoReceive;
use Modules\Procurement\Dao\Repositories\BranchRepository;
use Modules\Procurement\Dao\Repositories\PurchaseRepository;
use Modules\Procurement\Dao\Repositories\SupplierRepository;
use Modules\Procurement\Http\Requests\PaymentRequest;
use Modules\Procurement\Http\Requests\PurchaseReceiveRequest;
use Modules\Procurement\Http\Requests\PurchaseRequest;
use Modules\Procurement\Http\Services\DeletePurchaseService;
use Modules\Procurement\Http\Services\DeleteReceiveService;
use Modules\Procurement\Http\Services\PurchaseCreateService;
use Modules\Procurement\Http\Services\PurchaseReceiveService;
use Modules\Procurement\Http\Services\PurchaseUpdateService;
use Modules\System\Http\Requests\DeleteRequest;
use Modules\System\Http\Services\CreateService;
use Modules\System\Http\Services\DataService;
use Modules\System\Http\Services\SingleService;
use Modules\System\Plugins\Helper;
use Modules\System\Plugins\Response;
use Modules\System\Plugins\Views;

class PurchaseOrderController extends Controller
{
    public static $template;
    public static $service;
    public static $model;

    public function __construct(PurchaseRepository $model, SingleService $service)
    {
        self::$model = self::$model ?? $model;
        self::$service = self::$service ?? $service;
    }

    private function share($data = [])
    {
        // $product = Views::option(new ProductRepository());
        // $supplier = Views::option(new SupplierRepository());
        
        $supplier = Views::option(new SupplierRepository(),false,true)->mapWithKeys(function($item){
            $data[$item->supplier_id] = $item->supplier_name.' - '.strtoupper(SupplierType::getDescription($item->supplier_ppn));
            return $data;
        })->toArray();
        
        $status = PurchaseStatus::getOptions();
        $category = Views::option(new CategoryRepository());

        $view = [
            // 'product' => $product,
            'category' => $category,
            'supplier' => $supplier,
            'status' => $status,
            'model' => self::$model,
        ];
        return array_merge($view, $data);
    }

    public function index()
    {
        return view(Views::index())->with([
            'fields' => Helper::listData(self::$model->datatable),
        ]);
    }

    public function create()
    {
        $product = Views::option(new ProductRepository());
        return view(Views::create())->with($this->share([
            'product' => $product,
        ]));
    }

    public function save(PurchaseRequest $request, PurchaseCreateService $service)
    {
        $data = $service->save(self::$model, $request);
        return Response::redirectBack($data);
    }

    public function data(DataService $service)
    {
        return $service
            ->setModel(self::$model)
            ->EditStatus([
                self::$model->mask_status() => PurchaseStatus::class,
                self::$model->mask_payment() => PurchasePayment::class,
            ])->EditColumn([
                self::$model->mask_value() => 'mask_value_format',
                self::$model->mask_tax() => 'mask_tax_format',
                self::$model->mask_total() => 'mask_total_rupiah',
            ])->EditAction([
                'page'      => config('page'),
                'folder'    => config('folder'),
            ])->make();
    }

    public function edit($code)
    {
        $data = $this->get($code);
        $product = Views::option(new ProductRepository());

        return view(Views::update())->with($this->share([
            'model' => $data,
            'product' => $product,
            'detail' => $data->has_detail,
        ]));
    }

    public function update($code, PurchaseRequest $request, PurchaseUpdateService $service)
    {
        $data = $service->update(self::$model, $request, $code);
        return Response::redirectBack($data);
    }

    public function show($code)
    {
        $data = $this->get($code);
        return view(Views::show())->with($this->share([
            'fields' => Helper::listData(self::$model->datatable),
            'model' => $data,
            'detail' => $data->detail ?? []
        ]));
    }

    public function get($code = null, $relation = null)
    {
        $relation = $relation ?? request()->get('relation');
        if ($relation) {
            return self::$service->get(self::$model, $code, $relation);
        }
        return self::$service->get(self::$model, $code);
    }

    public function delete(DeleteRequest $request, DeletePurchaseService $service)
    {
        $master = $request->get('master');
        $code = $request->get('code');

        if ($request->has('transaction')) {
            $data = $service->deleteTransaction(self::$model, $master, $code);
        }
        $data = $service->delete(self::$model, $code);
        return Response::redirectBack($data);
    }

    public function formPayment($code)
    {
        $data = $this->get($code);
        $supplier = $data->has_supplier;
        $bank = Views::option(new BankRepository(), false, true)
            ->pluck('bank_name', 'bank_name')->prepend('- Select Bank - ', '')->toArray();

        return view(Views::form(Helper::snake(__FUNCTION__), config('page'), config('folder')))
            ->with($this->share([
                'model' => $data,
                'bank' => $bank,
                'supplier' => $supplier,
                'payment' => PaymentStatus::class,
                'detail' => $data->has_payment ?? false
            ]));
    }

    public function postPayment(PaymentRequest $request, CreateService $service, PaymentRepository $repository)
    {
        $data = $service->save($repository, $request);
        return Response::redirectBack($data);
    }

    public function formReceive($code)
    {
        $data = $this->get($code);
        $product = Views::option(new ProductRepository());

        return view(Views::form(Helper::snake(__FUNCTION__), config('page'), config('folder')))
            ->with($this->share([
                'model' => $data,
                'detail' => $data->has_detail,
                'product' => $product,
            ]));
    }

    public function postReceive($code, PurchaseRequest $request, PurchaseUpdateService $service)
    {
        $data = $service->update(self::$model, $request, $code);
        return Response::redirectBack($data);
    }

    public function formReceiveDetail($code)
    {
        $branch = Views::option(new BranchRepository());
        $detail = request()->get('detail');

        $receive = PoReceive::with(['has_branch'])->where(PoReceiveFacades::mask_po_code(), $code)
            ->where(PoReceiveFacades::mask_product_id(), $detail)->get();

        $model = $receive->first();

        $po = $this->get($code, ['has_supplier']);

        if (empty($model)) {

            $model = PoDetailFacades::where(PoDetailFacades::mask_po_code(), $code)
                ->where(PoDetailFacades::mask_product_id(), request()->get('detail'))->firstOrFail();

            $master = $model->has_master;
            $supplier = $po->has_supplier;

            $data = [
                'purchase_date' => $master->po_date_order ?? null,
                'purchase_status' => $master->po_status ?? '',
                'purchase_supplier' => $supplier ? $supplier->supplier_name.' - '.strtoupper(SupplierType::getDescription($supplier->supplier_ppn)) : '',
                'purchase_notes' => $master->po_notes ?? '',
                'purchase_product_name' => $model->has_product->mask_name ?? '',
                'po_receive_date' => date('Y-m-d'),
                'po_receive_po_code' => $code,
                'po_receive_product_id' => $detail,
                'po_receive_supplier_id' => $master->po_supplier_id ?? null,
                'po_receive_type' => $model->has_product->has_category->category_type ?? null,
                'po_receive_qty' => $model->po_detail_qty,
                'po_receive_receive' => null,
                'po_receive_start' => null,
                'po_receive_end' => null,
                'po_receive_buy' => $model->po_detail_price,
                'po_receive_sell' => 0,
            ];

            $model = array_merge($model->toArray(), $data);
        } else {

            $master = $model->has_master;
            $supplier = $po->has_supplier;

            $data = [
                'purchase_product_name' => $model->has_product->mask_name ?? '',
                'purchase_date' => $master->po_date_order ?? null,
                'purchase_status' => $master->po_status ?? '',
                'purchase_supplier' => $supplier->supplier_name.' - '.strtoupper(SupplierType::getDescription($supplier->supplier_ppn)) ?? '',
                'purchase_notes' => $master->po_notes ?? '',
            ];

            $model = array_merge($model->toArray(), $data);
        }

        // dd((Object) $model);

        return view(Views::form(Helper::snake(__FUNCTION__), config('page'), config('folder')))
            ->with($this->share([
                'model' => (object) $model,
                'branch' => [1 => $branch[1]],
                'detail' => $receive,
            ]));
    }

    public function postReceiveDetail(PurchaseReceiveRequest $request, PurchaseReceiveService $service, PoReceive $receive)
    {
        $data = $service->save($receive, $request);
        return Response::redirectBack($data);
    }

    public function showReceiveDetail($code)
    {
        $model = PoReceiveFacades::with(['has_detail', 'has_detail.has_product'])->findOrFail($code);

        return view(Views::form(Helper::snake(__FUNCTION__), config('page'), config('folder')))
            ->with($this->share([
                'model' => $model,
                'detail' => $model->has_detail ?? false,
            ]));
    }

    public function deleteReceiveDetail($code, DeleteReceiveService $service)
    {
        $check = $service->delete($code);
        return Response::redirectBack();
    }
}
