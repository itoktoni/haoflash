<?php

namespace Modules\Report\Dao\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Modules\Report\Dao\Interfaces\GenerateReport;
use Modules\Transaction\Dao\Repositories\StockRepository;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ReportStockSummary extends StockRepository implements FromView, WithColumnFormatting, WithColumnWidths, GenerateReport
{
    public $name;

    public function generate($name)
    {
        $this->name = $name;
        return $this;
    }

    public function data()
    {
        $query = $this->dataRepository()->with(['has_customer', 'has_product', 'has_warehouse', 'has_location'])->filter();

        if ($from = request()->get('from')) {
            $query->whereDate('stock_created_at', '>=', $from);
        }

        if ($to = request()->get('to')) {
            $query->whereDate('stock_created_at', '<=', $to);
        }

        $data = $query->groupBy(['stock_product_id', 'stock_customer_id', 'stock_so_code']);
        return $data->get();
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
