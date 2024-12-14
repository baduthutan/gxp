<?php

namespace App\Exports;

use App\Models\Booking;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;




class BookingShuttleByAreaReport extends DefaultValueBinder implements WithCustomValueBinder, FromView, ShouldAutoSize, WithColumnWidths, WithStyles, WithEvents
{

    private $count_cell, $request;

    public function __construct($request)
    {
        $this->count_cell = 0;
        $this->request = $request;
    }

    public function bindValue(Cell $cell, $value)
    {
        $numericalColumns = ['d']; // columns with numerical values

        if (!in_array($cell->getColumn(), $numericalColumns)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }
        // else return default behavior
        return parent::bindValue($cell, $value);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 3,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // $sheet->getStyle('A1:L1')->getFont()->setBold(true);
    }

    public function view(): View
    {
        $data = $this->_query()->orderBy('created_at', 'desc')->get();

        return view('exports.booking_shuttle_by_area', [
            'data' => $data,
        ]);
    }

    private function _query()
    {
        $query = Booking::selectRaw("*");
        if (count($this->request->all()) >= 1) {
            $query = Booking::where("schedule_type", 'shuttle')
                ->where('payment_status', 'paid')
                ->where('booking_status', 'active')
                ->when($this->request, function ($q, $request) {

                    if ($request->area_type) {
                        $q->where('area_type', $request['area_type']);
                    }

                    if ($request->datetime_departure) {
                        $q->whereRaw("date_format(datetime_departure, '%Y-%m-%d') = '{$request['datetime_departure']}'");
                    }

                    if ($request->trip_number) {
                        $q->where("trip_number", $request['trip_number']);
                    }
                });

            if (!$this->request->trip_number) {
                $query->orderBy("trip_number",'asc');
            }
        } else {
            $query->where('id', 0);
        }


        return $query;
    }

    public function registerEvents(): array
    {
        $count = count($this->_query()->get()->toArray());

        // dd(count($this->_query()->get()->toArray()) + 1);

        $row = !empty($count) ? (int)$count + 1 : 2;
        return [
            AfterSheet::class => function (AfterSheet $event) use ($row) {
                $last_col = $event->sheet->getHighestColumn();
                // dump($last_col);
                // dd($row);
                $event->sheet->getStyle("A1:{$last_col}{$row}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ]
                ]);

                // dd($event);
                // $event->sheet->format(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
                $event->sheet->getStyle("A1:{$last_col}1")->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            },
        ];
    }
}
