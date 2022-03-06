<?php

namespace Modules\Procurement\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Item\Dao\Repositories\ProductRepository;
use Modules\Procurement\Dao\Repositories\BranchRepository;
use Modules\Procurement\Dao\Repositories\StockRepository;
use Modules\Procurement\Dao\Repositories\StockSummaryRepository;
use Modules\Procurement\Dao\Repositories\SupplierRepository;
use Modules\Procurement\Http\Services\DataStockVoucherService;
use Modules\Procurement\Http\Services\DataSummaryStockVoucherService;
use Modules\System\Http\Requests\DeleteRequest;
use Modules\System\Http\Requests\GeneralRequest;
use Modules\System\Http\Services\CreateService;
use Modules\System\Http\Services\DataService;
use Modules\System\Http\Services\DeleteService;
use Modules\System\Http\Services\SingleService;
use Modules\System\Http\Services\UpdateService;
use Modules\System\Plugins\Helper;
use Modules\System\Plugins\Response;
use Modules\System\Plugins\Views;

class StockVoucherController extends Controller
{
    public static $template;
    public static $service;
    public static $model;

    public function __construct(StockRepository $model, SingleService $service)
    {
        self::$model = self::$model ?? $model;
        self::$service = self::$service ?? $service;
    }

    private function share($data = [])
    {
        $branch = Views::option(new BranchRepository());
        $product = Views::option(new ProductRepository());

        $view = [
            'branch' => $branch,
            'product' => $product,
            'model' => self::$model,
        ];

        return array_merge($view, $data);
    }

    public function index()
    {
        return view(Views::form(Helper::snake(__FUNCTION__), config('page'), config('folder')))->with([
            'fields' => Helper::listData(self::$model->datatable),
        ]);
    }

    public function data(DataStockVoucherService $service)
    {
        return $service
            ->setModel(self::$model)
            ->EditAction([
                'page'      => config('page'),
                'folder'    => config('folder'),
            ], false)
            ->EditColumn([
                self::$model->mask_buy() => 'mask_buy_format',
                'product_description' => 'mask_product_description',
            ])
            ->make();
    }

    public function indexSummary(StockSummaryRepository $model)
    {
        return view(Views::form(Helper::snake(__FUNCTION__), config('page'), config('folder')))->with([
            'fields' => Helper::listData($model->datatable),
        ]);
    }

    public function dataSummary(DataSummaryStockVoucherService $service, StockSummaryRepository $model)
    {
        return $service
            ->setModel($model)
            ->EditAction([
                'page'      => config('page'),
                'folder'    => config('folder'),
            ], false)
            ->EditColumn([
                self::$model->mask_buy() => 'mask_buy_format',
                'product_description' => 'mask_product_description',
            ])
            ->make();
    }

    public function show($code)
    {
        return view(Views::show())->with($this->share([
            'fields' => Helper::listData(self::$model->datatable),
            'model' => $this->get($code),
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
}
