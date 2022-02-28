<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Master\Dao\Enums\PaymentType;
use Modules\Master\Dao\Facades\ProductFacades;
use Modules\Master\Dao\Repositories\CompanyRepository;
use Modules\Master\Dao\Repositories\ProductRepository;
use Modules\Master\Dao\Repositories\SupplierRepository;
use Modules\Report\Dao\Repositories\ReportFinanceSummary;
use Modules\Report\Dao\Repositories\ReportPiutang;
use Modules\Report\Dao\Repositories\ReportPiutangSummary;
use Modules\Report\Dao\Repositories\ReportSoDetail;
use Modules\Report\Dao\Repositories\ReportSoSummary;
use Modules\Report\Dao\Repositories\ReportSummarySo;
use Modules\Report\Dao\Repositories\ReportUtang;
use Modules\Report\Dao\Repositories\SoSummaryExcel;
use Modules\System\Dao\Repositories\TeamRepository;
use Modules\System\Http\Services\PreviewService;
use Modules\System\Http\Services\ReportService;
use Modules\System\Http\Services\SingleService;
use Modules\System\Plugins\Views;
use Modules\Transaction\Dao\Repositories\SoRepository;

class FinanceController extends Controller
{
    public static $template;
    public static $service;
    public static $model;
    public static $history;
    public static $summary;

    private function share($data = [])
    {
        $user = Views::option(new TeamRepository());
        $product = Views::option(new ProductRepository());
        $company = Views::option(new CompanyRepository());
        $customer = Views::option(new TeamRepository());
        $supplier = Views::option(new SupplierRepository());
        
        $type = PaymentType::getOptions();

        $view = [
            'supplier' => $supplier,
            'product' => $product,
            'company' => $company,
            'customer' => $customer,            
            'type' => $type,
            'user' => $user,
        ];

        return array_merge($view, $data);
    }

    public function inOut(ReportFinanceSummary $repository)
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

    public function inOutExport(ReportService $service, ReportFinanceSummary $repository)
    {
        return $service->generate($repository, 'export_in_out');
    }

    public function utang(ReportUtang $repository)
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

    public function utangExport(ReportService $service, ReportUtang $repository)
    {
        return $service->generate($repository, 'export_utang');
    }

    public function piutang(ReportPiutang $repository)
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

    public function piutangExport(ReportService $service, ReportPiutang $repository)
    {
        return $service->generate($repository, 'export_piutang');
    }
}
