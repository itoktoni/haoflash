<?php

namespace Modules\Report\Dao\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Modules\Procurement\Dao\Repositories\StockRepository as RepositoriesStockRepository;
use Modules\Report\Dao\Interfaces\GenerateReport;
use Modules\Transaction\Dao\Repositories\StockRepository;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ReportStockSummary extends RepositoriesStockRepository implements FromView, WithColumnFormatting, WithColumnWidths, GenerateReport
{
    public $name;

    public function generate($name)
    {
        $this->name = $name;
        return $this;
    }

    public function data()
    {
        // $query = $this->dataRepository()->with(['has_customer', 'has_product', 'has_warehouse', 'has_location'])->filter();
        $query = DB::table('view_summary_stock');

        if ($product = request()->get('stock_product_id')) {
            $query = $query->where('stock_product_id', $product);
        }

        if ($supplier = request()->get('stock_supplier_id')) {
            $query = $query->where('stock_supplier_id', $supplier);
        }

        // if ($from = request()->get('from')) {
        //     $query->orWhere('stock_expired', '>=', $from);
        // }

        // if ($to = request()->get('to')) {
        //     $query->where('stock_expired', '<=', $to);
        // }

        if ($product = request()->get('stock_product_id')) {
            $query = $query->where('stock_product_id', $product);
        }

        if ($supplier = request()->get('stock_supplier_id')) {
            $query = $query->where('stock_supplier_id', $supplier);
        }
        return $query->get();
    }

    public function view(): View
    {
        return view('Report::page.' . config('page') . '.' . $this->name, [
            'preview' => $this->data()
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 20,
            'C' => 30,
            'D' => 30,
            'E' => 20,
        ];
    }
}
