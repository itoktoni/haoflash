<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Item\Dao\Repositories\ProductRepository;
use Modules\Procurement\Dao\Repositories\BranchRepository;
use Modules\Report\Dao\Repositories\ReportDeliveryDetail;
use Modules\Report\Dao\Repositories\ReportDeliverySummary;
use Modules\System\Http\Services\ReportService;
use Modules\System\Plugins\Views;

class DeliveryController extends Controller
{
    public static $template;
    public static $service;
    public static $model;
    public static $history;
    public static $summary;

    private function share($data = [])
    {
        $product = Views::option(new ProductRepository());
        $branch = Views::option(new BranchRepository());

        $view = [
            'product' => $product,
            'branch' => $branch,
        ];

        return array_merge($view, $data);
    }

    public function detail(ReportDeliveryDetail $repository)
    {
        $preview = false;
        if ($name = request()->get('name')) {
            $preview = $repository->generate($name)->data();
        }
        return view(Views::form(__FUNCTION__, config('page'), config('folder')))
            ->with($this->share([
                'model' => $repository,
                'preview' => $preview,
            ]));
    }

    public function detailExport(ReportService $service, ReportDeliveryDetail $repository)
    {
        return $service->generate($repository, 'export_detail');
    }

    public function summary(ReportDeliverySummary $repository)
    {
        $preview = false;
        if ($name = request()->get('name')) {
            $preview = $repository->generate($name)->data();
        }

        return view(Views::form(__FUNCTION__, config('page'), config('folder')))
            ->with($this->share([
                'model' => $repository,
                'preview' => $preview,
            ]));
    }

    public function summaryExport(ReportService $service, ReportDeliverySummary $repository)
    {
        return $service->generate($repository, 'export_summary');
    }
}
