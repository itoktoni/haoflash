<?php

namespace Modules\Report\Dao\Repositories;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Modules\Procurement\Dao\Models\Stock;
use Modules\Procurement\Dao\Repositories\StockRepository;
use Modules\Report\Dao\Interfaces\GenerateReport;

class ReportStockDetail extends StockRepository implements FromView, WithColumnFormatting, WithColumnWidths, ShouldAutoSize, GenerateReport
{
    public $name;

    public function generate($name)
    {
        $this->name = $name;
        return $this;
    }

    public function data()
    {
        $query = Stock::with(['has_supplier', 'has_product', 'has_branch']);

        if ($from = request()->get('from')) {
            $query->whereDate('stock_created_at', '>=', $from);
        }

        if ($to = request()->get('to')) {
            $query->whereDate('stock_created_at', '<=', $to);
        }

        if ($branch = request()->get('stock_branch_id')) {
            $query->where('stock_branch_id', $branch);
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
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_TEXT,
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
            'F' => 10,
            'G' => 15,
            'H' => 20,
        ];
    }
}