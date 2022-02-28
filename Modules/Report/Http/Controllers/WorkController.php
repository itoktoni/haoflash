<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Master\Dao\Repositories\CompanyRepository;
use Modules\Master\Dao\Repositories\ProductRepository;
use Modules\Master\Dao\Repositories\SupplierRepository;
use Modules\Report\Dao\Repositories\ReportSoDetail;
use Modules\Report\Dao\Repositories\ReportSoSummary;
use Modules\Report\Dao\Repositories\ReportWoDetail;
use Modules\Report\Dao\Repositories\ReportWoSummary;
use Modules\System\Http\Services\ReportService;
use Modules\System\Plugins\Views;

class WorkController extends Controller
{
    public static $template;
    public static $service;
    public static $model;
    public static $history;
    public static $summary;

    private function share($data = [])
    {
        $product = Views::option(new ProductRepository());
        $company = Views::option(new CompanyRepository());
        $supplier = Views::option(new SupplierRepository());

        $view = [
            'product' => $product,
            'company' => $company,
            'supplier' => $supplier,
        ];

        return array_merge($view, $data);
    }

    public function detail(ReportWoDetail $repository)
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

    public function detailExport(ReportService $service, ReportWoDetail $repository)
    {
        return $service->generate($repository, 'export_detail');
    }

    public function summary(ReportWoSummary $repository)
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

    public function summaryExport(ReportService $service, ReportWoSummary $repository)
    {
        return $service->generate($repository, 'export_summary');
    }
}
