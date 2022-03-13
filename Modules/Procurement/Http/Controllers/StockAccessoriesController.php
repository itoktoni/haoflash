<?php

namespace Modules\Procurement\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Item\Dao\Repositories\ProductRepository;
use Modules\Procurement\Dao\Repositories\BranchRepository;
use Modules\Procurement\Dao\Repositories\StockAccesoriesRepository;
use Modules\Procurement\Dao\Repositories\StockAccessoriesRepository;
use Modules\Procurement\Dao\Repositories\StockBdpRepository;
use Modules\Procurement\Dao\Repositories\StockRepository;
use Modules\Procurement\Dao\Repositories\SupplierRepository;
use Modules\System\Http\Requests\DeleteRequest;
use Modules\System\Http\Requests\GeneralRequest;
use Modules\System\Http\Services\CreateService;
use Modules\System\Http\Services\DataService;
use Modules\System\Http\Services\DeleteService;
use Modules\System\Http\Services\SingleService;
use Modules\System\Http\Services\UpdateService;
use Modules\System\Plugins\Alert;
use Modules\System\Plugins\Helper;
use Modules\System\Plugins\Response;
use Modules\System\Plugins\Views;

class StockAccessoriesController extends Controller
{
    public static $template;
    public static $service;
    public static $model;

    public function __construct(StockAccessoriesRepository $model, SingleService $service)
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
        return view(Views::index(config('page'), config('folder')))->with([
            'fields' => Helper::listData(self::$model->datatable),
        ]);
    }

    // public function create()
    // {
    //     return view(Views::create())->with($this->share());
    // }

    public function save(GeneralRequest $request, CreateService $service)
    {
        $data = $service->save(self::$model, $request);
        return Response::redirectBack($data);
    }

    public function data(DataService $service)
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

    public function edit($code)
    {
        return view(Views::update())->with($this->share([
            'model' => $this->get($code),
        ]));
    }

    public function update($code, GeneralRequest $request, UpdateService $service)
    {
        $data = $service->update(self::$model, $request, $code);
        return Response::redirectBack($data);
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

    public function delete(DeleteRequest $request, DeleteService $service)
    {
        Alert::error('Delete tidak diperbolehkan !');
        $data = [];
        return Response::redirectBack($data);
    }
}
