<?php

namespace Modules\Procurement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Item\Dao\Enums\CategoryType;
use Modules\Item\Dao\Facades\CategoryFacades;
use Modules\Item\Dao\Facades\ProductFacades;
use Modules\Item\Dao\Repositories\CategoryRepository;
use Modules\Item\Dao\Repositories\ProductRepository;
use Modules\Procurement\Dao\Enums\DeliveryStatus;
use Modules\Procurement\Dao\Enums\SupplierPpn;
use Modules\Procurement\Dao\Enums\SupplierType;
use Modules\Procurement\Dao\Facades\BranchFacades;
use Modules\Procurement\Dao\Facades\DeDetailFacades;
use Modules\Procurement\Dao\Facades\DeFacades;
use Modules\Procurement\Dao\Facades\DePrepareFacades;
use Modules\Procurement\Dao\Facades\DeReceiveFacades;
use Modules\Procurement\Dao\Facades\PoDetailFacades;
use Modules\Procurement\Dao\Facades\PoReceiveFacades;
use Modules\Procurement\Dao\Facades\RoDetailFacades;
use Modules\Procurement\Dao\Facades\RoFacades;
use Modules\Procurement\Dao\Models\DePrepare;
use Modules\Procurement\Dao\Models\PoReceive;
use Modules\Procurement\Dao\Repositories\BranchRepository;
use Modules\Procurement\Dao\Repositories\DeRepository;
use Modules\Procurement\Dao\Repositories\RequestRepository;
use Modules\Procurement\Dao\Repositories\RoRepository;
use Modules\Procurement\Dao\Repositories\SupplierRepository;
use Modules\Procurement\Http\Requests\DeliveryPrepareRequest;
use Modules\Procurement\Http\Requests\DeliveryReceiveRequest;
use Modules\Procurement\Http\Requests\DeliveryRequest;
use Modules\Procurement\Http\Requests\PaymentRequest;
use Modules\Procurement\Http\Requests\PurchaseReceiveRequest;
use Modules\Procurement\Http\Requests\PurchaseRequest;
use Modules\Procurement\Http\Requests\RequestReceiveRequest;
use Modules\Procurement\Http\Requests\RequestRequest;
use Modules\Procurement\Http\Services\DeletePrepareService;
use Modules\Procurement\Http\Services\DeleteRequestService;
use Modules\Procurement\Http\Services\DeleteReceiveService;
use Modules\Procurement\Http\Services\DeliveryCreateService;
use Modules\Procurement\Http\Services\DeliveryPrepareService;
use Modules\Procurement\Http\Services\DeliveryReceiveService;
use Modules\Procurement\Http\Services\DeliveryUpdateService;
use Modules\Procurement\Http\Services\PurchaseReceiveService;
use Modules\Procurement\Http\Services\PurchaseUpdateService;
use Modules\Procurement\Http\Services\RequestCreateService;
use Modules\Procurement\Http\Services\RequestReceiveService;
use Modules\Procurement\Http\Services\RequestUpdateService;
use Modules\System\Dao\Enums\GroupUserType;
use Modules\System\Http\Requests\DeleteRequest;
use Modules\System\Http\Requests\GeneralRequest;
use Modules\System\Http\Services\CreateService;
use Modules\System\Http\Services\DataService;
use Modules\System\Http\Services\SingleService;
use Modules\System\Plugins\Adapter;
use Modules\System\Plugins\Alert;
use Modules\System\Plugins\Helper;
use Modules\System\Plugins\Response;
use Modules\System\Plugins\Views;

class DeliveryOrderController extends Controller
{
    public static $template;
    public static $service;
    public static $model;

    public function __construct(DeRepository $model, SingleService $service)
    {
        self::$model = self::$model ?? $model;
        self::$service = self::$service ?? $service;
    }

    private function share($data = [])
    {
        $get = auth()->user()->branch;
        $branch = Views::option(new BranchRepository(), false, true)->where(BranchFacades::getKeyName(), '!=', env('BRANCH_ID'));
        if (auth()->user()->mask_group_user != GroupUserType::Developer) {
            $branch = $branch->where(BranchFacades::getKeyName(), auth()->user()->branch);
        }
        $branch = $branch->pluck(BranchFacades::mask_name(), BranchFacades::getKeyName())
            ->prepend('- Select Branch -', '');

        $category = Views::option(new CategoryRepository(), false, true)
            ->where(CategoryFacades::mask_type(), '!=', CategoryType::BDP)
            ->pluck(CategoryFacades::mask_name(), CategoryFacades::getKeyName())
            ->prepend('- Select Category -', '');

        $product = Views::option(new ProductRepository(), false, true)
            ->where(CategoryFacades::mask_type(), '!=', CategoryType::BDP)
            ->pluck(ProductFacades::mask_name(), ProductFacades::getKeyName())
            ->prepend('- Select Product -', '');

        $view = [
            // 'product' => $product,
            'category' => $category,
            'branch' => $branch,
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
        $status = [DeliveryStatus::Create => DeliveryStatus::getDescription(DeliveryStatus::Create)];

        return view(Views::create())->with($this->share([
            'status' => $status,
        ]));
    }

    public function save(DeliveryRequest $request, DeliveryCreateService $service)
    {
        $data = $service->save(self::$model, $request);
        return Response::redirectBack($data);
    }

    public function data(DataService $service)
    {
        return $service
            ->setModel(self::$model)
            ->EditStatus([
                self::$model->mask_status() => DeliveryStatus::class,
            ])->EditColumn([
                'do_updated_at' => 'do_updated_at',
                self::$model->mask_total() => 'mask_total_rupiah',
            ])->EditAction([
                'page'      => config('page'),
                'folder'    => config('folder'),
            ])->make();
    }

    public function edit($code)
    {
        $data = $this->get($code, ['has_detail']);
        $dataRo = false;
        $ro = $data->do_request_id ?? request()->get('ro');

        if($ro){
            $dataRo = RoDetailFacades::with('has_product')->where('ro_detail_ro_code', $ro)->get();
        }

        $status = DeliveryStatus::getOptions();
        if (auth()->user()->group_user != GroupUserType::Developer) {
            $status = [$data->mask_status => DeliveryStatus::getDescription($data->mask_status)];
        }

        return view(Views::update(config('page'), config('folder')))->with($this->share([
            'model' => $data,
            'status' => $status,
            'data_ro' => $dataRo,
            'detail' => $data->has_detail,
        ]));
    }

    public function update($code, DeliveryRequest $request, DeliveryUpdateService $service)
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

    public function delete(DeleteRequest $request)
    {
        Alert::error('Delete tidak diperbolehkan !');
        $data = [];
        return Response::redirectBack($data);
    }

    public function formPrepare($code)
    {
        $data = $this->get($code);
        $product = Views::option(new ProductRepository());

        $status = DeliveryStatus::getOptions();
        if (auth()->user()->group_user != GroupUserType::Developer) {
            $status = [$data->mask_status => DeliveryStatus::getDescription($data->mask_status)];
        }

        return view(Views::form(Helper::snake(__FUNCTION__), config('page'), config('folder')))
            ->with($this->share([
                'model' => $data,
                'detail' => $data->has_detail,
                'product' => $product,
                'status' => $status,
            ]));
    }

    public function postPrepare($code, PurchaseRequest $request, PurchaseUpdateService $service)
    {
        $data = $service->update(self::$model, $request, $code);
        return Response::redirectBack($data);
    }

    public function formPrepareDetail($code)
    {
        $id = request()->get('detail');
        $key = request()->get('key');

        $split = Adapter::splitKey($key);
        $split_product = $split[0];
        $split_supplier = $split[1];
        $split_buy = $split[2];
        $split_expired = $split[3];

        $query = DePrepareFacades::with(['has_master', 'has_product', 'has_product.has_category'])
            ->where(DePrepareFacades::mask_do_code(), $code)
            ->where(DePrepareFacades::mask_supplier_id(), $split_supplier)
            ->where(DePrepareFacades::mask_price(), $split_buy)
            ->where(DePrepareFacades::mask_expired(), $split_expired)
            ->where(DePrepareFacades::mask_product_id(), $split_product);

        $total = $query->sum(DePrepareFacades::mask_qty());
        $detail = $query->get();
        $prepare = $query->first();

        $supplier = $prepare->mask_supplier_id ?? null;

        if (!$prepare) {

            $prepare = DeDetailFacades::with(['has_master', 'has_product', 'has_product.has_category'])
                ->where(DeDetailFacades::mask_do_code(), $code)
                ->where(DeDetailFacades::mask_key(), $key)
                ->where(DeDetailFacades::mask_product_id(), $id)->first();

            $data_supplier = DB::table('view_summary_stock')->where('id', $prepare->mask_key)->first();
            $supplier = $data_supplier->stock_supplier_id ?? null;
        }

        $model = $prepare->has_master ?? false;

        return view(Views::form(Helper::snake(__FUNCTION__), config('page'), config('folder')))
            ->with($this->share([
                'model' => (object) $model,
                'detail' => $detail,
                'prepare' => $prepare,
                'total' => $total,
                'supplier' => $supplier,
            ]));
    }

    public function postPrepareDetail(DeliveryPrepareRequest $request, DeliveryPrepareService $service, DePrepare $model)
    {
        $data = $service->save($model, $request);
        return Response::redirectBack($data);
    }

    public function showPrepareDetail($code)
    {
        $model = DePrepareFacades::with(['has_detail', 'has_detail.has_product', 'has_detail.has_supplier'])->findOrFail($code);

        return view(Views::form(Helper::snake(__FUNCTION__), config('page'), config('folder')))
            ->with($this->share([
                'model' => $model,
                'detail' => $model->has_detail ?? false,
            ]));
    }

    public function deletePrepareDetail($code, DeletePrepareService $service)
    {
        $check = $service->delete($code);
        return Response::redirectBack();
    }

    public function formReceive($code)
    {
        $data = $this->get($code);
        if ($data->mask_status == DeliveryStatus::Ready || $data->mask_status == DeliveryStatus::Receive) {
            $product = Views::option(new ProductRepository());

            $status = DeliveryStatus::getOptions([DeliveryStatus::Ready, DeliveryStatus::Receive, DeliveryStatus::Cancel]);
            if (auth()->user()->group_user != GroupUserType::Developer) {
                $status = [$data->mask_status => DeliveryStatus::getDescription($data->mask_status)];
            }

            return view(Views::form(Helper::snake(__FUNCTION__), config('page'), config('folder')))
                ->with($this->share([
                    'model' => $data,
                    'detail' => $data->has_detail,
                    'product' => $product,
                    'status' => $status,
                ]));
        }

        Alert::error('Status Must Ready for Pickup');
        return Response::redirectBack('Not Otorized');
    }

    public function postReceive(DeliveryReceiveRequest $request, DeliveryReceiveService $service)
    {
        $data = $service->save(self::$model, $request);
        return Response::redirectBack($data);
    }

    public function formReceiveDetail($code)
    {
        $data_branch = BranchFacades::first();
        $branch = [];
        if ($data_branch) {
            $branch[$data_branch->{$data_branch->getKeyName()}] = $data_branch->mask_name;
        }
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
                'purchase_supplier' => $supplier ? $supplier->supplier_name . ' - ' . strtoupper(SupplierPpn::getDescription($supplier->supplier_ppn)) : '',
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
                'purchase_supplier' => $supplier->supplier_name . ' - ' . strtoupper(Supplierppn::getDescription($supplier->supplier_ppn)) ?? '',
                'purchase_notes' => $master->po_notes ?? '',
            ];

            $model = array_merge($model->toArray(), $data);
        }

        return view(Views::form(Helper::snake(__FUNCTION__), config('page'), config('folder')))
            ->with($this->share([
                'model' => (object) $model,
                'branch' => $branch,
                'detail' => $receive,
            ]));
    }

    public function postReceiveDetail(DeliveryReceiveRequest $request, DeliveryReceiveService $service, PoReceive $receive)
    {
        $data = $service->save($receive, $request);
        return Response::redirectBack($data);
    }

    public function showReceiveDetail($code)
    {
        $model = PoReceiveFacades::with(['has_detail', 'has_detail.has_product', 'has_detail.has_supplier'])->findOrFail($code);

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
