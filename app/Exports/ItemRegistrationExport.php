<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ItemRegistrationExport implements ShouldAutoSize, WithHeadings, WithEvents, WithColumnWidths
{
    /**
     * Excel sheet for item registration
     *
     * @author yarzartinshwe
     *
     * @created 2023-6-23
     *
     */

    /**
     *Category name sheet headings
     *
     * @author yarzartinshwe
     * @created 2023-6-23
     *
     */
    public function headings(): array
    {
        return [
            ["The Excel rows limit is 100 rows. Please ensure that the category name you set is included in the category name sheet."],           
            ['Item Code',
            'Item Name',
            'Category Name',
            'Safety Stock',
            'Received Date',
            'Description']
            
        ];
    }

    /**
     *Category name sheet export styling
     *
     * @author yarzartinshwe
     * @created 2023-6-23
     *
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->setTitle('Student Registration');

                $event->sheet->getRowDimension(1)->setRowHeight(40);
                $cell = ["A", "B", "C", "D", "E", "F"];
                for ($i = 0; $i < count($cell); $i++) {
                    $event->sheet->getStyle($cell[$i])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                }

                $event->sheet->getStyle('A1'.':F1')->applyFromArray([
                    
                    'font' => [
                        'bold' => true,
                        'color' => [
                            'rgb' => 'FF0000' 
                        ],
                    ],
                    'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                ]);

                $event->sheet->mergeCells('A1:F1');
                
                $event->sheet->getStyle('A2'.':E2')->applyFromArray([
                    
                    'font' => [
                        'bold' => true,
                        'color' => [
                            'rgb' => 'FF0000' 
                        ],
                    ],

                    'alignment' => ['horizontal' => 'center'],
                    
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '87CEEB'],
                    ],

                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                $event->sheet->getStyle('F2')->applyFromArray([
                    
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => ['horizontal' => 'center'],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '87CEEB'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                
                $event->sheet->getRowDimension(2)->setRowHeight(30);
                
            },
        ];
    }

    /**
     *Excel column widths
     *
     * @author yarzartinshwe
     * @created 2023-6-23
     *
     */
    public function columnWidths(): array
    {
        return [
            'A' => 20,  // Width of column A
            'B' => 20, 
            'C' => 20,  
            'D' => 20, 
            'E' => 20,  
            'F' => 20, 
        ];
    }
}
