<?php

namespace Modules\Procurement\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Item\Dao\Enums\CategoryType;
use Modules\Item\Dao\Facades\CategoryFacades;
use Modules\Item\Dao\Facades\ProductFacades;
use Modules\Item\Dao\Repositories\CategoryRepository;
use Modules\Item\Dao\Repositories\ProductRepository;
use Modules\Procurement\Dao\Enums\PurchaseStatus;
use Modules\Procurement\Dao\Enums\RequestStatus;
use Modules\Procurement\Dao\Facades\BranchFacades;
use Modules\Procurement\Dao\Facades\DeDetailFacades;
use Modules\Procurement\Dao\Facades\DeFacades;
use Modules\Procurement\Dao\Repositories\BranchRepository;
use Modules\Procurement\Dao\Repositories\DeRepository;
use Modules\Procurement\Dao\Repositories\RoRepository;
use Modules\Procurement\Http\Requests\RequestRequest;
use Modules\Procurement\Http\Services\DeliveryCreateService;
use Modules\Procurement\Http\Services\RequestCreateService;
use Modules\Procurement\Http\Services\RequestUpdateService;
use Modules\System\Dao\Enums\GroupUserType;
use Modules\System\Http\Requests\DeleteRequest;
use Modules\System\Http\Services\DataService;
use Modules\System\Http\Services\SingleService;
use Modules\System\Plugins\Alert;
use Modules\System\Plugins\Helper;
use Modules\System\Plugins\Response;
use Modules\System\Plugins\Views;

class RequestOrderController extends Controller
{
    public static $template;
    public static $service;
    public static $model;

    public function __construct(RoRepository $model, SingleService $service)
    {
        self::$model = self::$model ?? $model;
        self::$service = self::$service ?? $service;
    }

    private function share($data = [])
    {
        $get = auth()->user()->branch;
        $branch = Views::option(new BranchRepository(), false, true);
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
            'product' => $product,
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
        $status = [RequestStatus::Create => RequestStatus::getDescription(RequestStatus::Create)];

        return view(Views::create())->with($this->share([
            'status' => $status,
        ]));
    }

    public function save(RequestRequest $request, RequestCreateService $service)
    {
        $data = $service->save(self::$model, $request);
        return Response::redirectBack($data);
    }

    public function data(DataService $service)
    {
        return $service
            ->setModel(self::$model)
            ->EditStatus([
                self::$model->mask_status() => RequestStatus::class,
            ])->EditColumn([
            'ro_updated_at' => 'ro_updated_at',
            self::$model->mask_total() => 'mask_total_rupiah',
        ])->EditAction([
            'page' => config('page'),
            'folder' => config('folder'),
        ])->make();
    }

    public function copy($code, DeliveryCreateService $service)
    {
        $autonumber = Helper::autoNumber(DeFacades::getTable(), DeFacades::getKeyName(), 'DO' . date('Ym'), env('WEBSITE_AUTONUMBER'));
        $data = $this->get($code, ['has_detail']);
        // $detail = $data->has_detail;
        // $delivery_detail = [];
        // $grand = 0;
        // $counter = 0;
        // if ($detail) {
        //     foreach ($detail as $item) {
        //         $counterPlus = $counter++;
        //         $total = Helper::filterInput($item->ro_detail_product_price) * Helper::filterInput($item->ro_detail_qty) ?? 0;
        //         $grand = $grand + $total;
        //         $delivery_detail[$counterPlus][DeDetailFacades::mask_do_code()] = $autonumber;
        //         $delivery_detail[$counterPlus][DeDetailFacades::mask_product_id()] = $item->ro_detail_product_id ?? null;
        //         $delivery_detail[$counterPlus][DeDetailFacades::mask_notes()] = $item->ro_detail_notes ?? null;
        //         $delivery_detail[$counterPlus][DeDetailFacades::mask_product_price()] = $item->ro_detail_product_price ?? null;
        //         $delivery_detail[$counterPlus][DeDetailFacades::mask_qty()] = Helper::filterInput($item->ro_detail_qty);
        //         $delivery_detail[$counterPlus][DeDetailFacades::mask_sell()] = Helper::filterInput($item->ro_detail_product_price) ?? 0;
        //         $delivery_detail[$counterPlus][DeDetailFacades::mask_total()] = $total;
        //     }
        // }
        $delivery = [
            'do_code' => $autonumber,
            'do_request_id' => $data['ro_code'],
            'do_branch_id' => $data['ro_branch_id'],
            'do_date_order' => $data['ro_date_order'],
            'do_status' => PurchaseStatus::Create,
            'do_notes' => $data['do_notes'],
            // 'do_sum_value' => $grand,
            // 'do_sum_total' => $grand,
            // 'detail' => $delivery_detail,
        ];

        $delivery = collect($delivery);

        $check = $service->save(new DeRepository(), $delivery);
        return redirect()->route('procurement_delivery_order_edit', ['code' => $autonumber]);
    }

    public function edit($code)
    {
        $data = $this->get($code, ['has_detail']);

        $status = RequestStatus::getOptions();
        if (auth()->user()->group_user != GroupUserType::Developer) {
            $status = [$data->mask_status => RequestStatus::getDescription($data->mask_status)];
        }

        return view(Views::update(config('page'), config('folder')))->with($this->share([
            'model' => $data,
            'status' => $status,
            'detail' => $data->has_detail,
        ]));
    }

    public function update($code, RequestRequest $request, RequestUpdateService $service)
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
            'detail' => $data->detail ?? [],
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
}
