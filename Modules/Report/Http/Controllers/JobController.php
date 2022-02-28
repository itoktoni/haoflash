<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Master\Dao\Facades\ProductFacades;
use Modules\Master\Dao\Repositories\CompanyRepository;
use Modules\Master\Dao\Repositories\ProductRepository;
use Modules\Report\Dao\Repositories\ReportJoDetail;
use Modules\Report\Dao\Repositories\ReportJoSummary;
use Modules\Report\Dao\Repositories\ReportSoDetail;
use Modules\Report\Dao\Repositories\ReportSoSummary;
use Modules\Report\Dao\Repositories\ReportSummarySo;
use Modules\Report\Dao\Repositories\SoSummaryExcel;
use Modules\System\Dao\Repositories\TeamRepository;
use Modules\System\Http\Services\PreviewService;
use Modules\System\Http\Services\ReportService;
use Modules\System\Http\Services\SingleService;
use Modules\System\Plugins\Views;
use Modules\Transaction\Dao\Repositories\SoRepository;

class JobController extends Controller
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
        $customer = Views::option(new TeamRepository());

        $view = [
            'product' => $product,
            'company' => $company,
            'customer' => $customer,
        ];

        return array_merge($view, $data);
    }

    public function detail(ReportJoDetail $repository)
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

    public function detailExport(ReportService $service, ReportJoDetail $repository)
    {
        return $service->generate($repository, 'export_detail');
    }

    public function summary(ReportJoSummary $repository)
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

    public function summaryExport(ReportService $service, ReportJoSummary $repository)
    {
        return $service->generate($repository, 'export_summary');
    }
}
