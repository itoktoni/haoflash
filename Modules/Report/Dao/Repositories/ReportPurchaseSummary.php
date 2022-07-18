<?php

namespace Modules\Report\Dao\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Modules\Procurement\Dao\Repositories\PurchaseRepository;
use Modules\Report\Dao\Interfaces\GenerateReport;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ReportPurchaseSummary extends PurchaseRepository implements FromView, WithColumnFormatting, WithColumnWidths, GenerateReport
{
    public $name;

    public function generate($name)
    {
        $this->name = $name;
        return $this;
    }

    public function data()
    {
        $query = $this->dataRepository()->with(['has_supplier'])->filter();

        if ($from = request()->get('from')) {
            $query->whereDate('po_created_at', '>=', $from);
        }

        if ($to = request()->get('to')) {
            $query->whereDate('po_created_at', '<=', $to);
        }

        // dd($query->get());

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
            'J' => NumberFormat::FORMAT_TEXT,
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
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 15,
            'J' => 15,
        ];
    }
}
