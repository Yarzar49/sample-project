<?php

namespace App\Exports;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class CategoryNameExport implements ShouldAutoSize, WithEvents, WithColumnWidths, FromView
{
    /*
     * Category name sheet export
     *
     * @author yarzartinshwe
     *
     * @created 2023-6-23
     *
     */

     /**
     *Excel headings in category name sheet export
     *
     * @author yarzartinshwe
     * @created 2023-6-23
     * @return category name headings view
     *
     */
    public function view(): View
    {
        return view('exports.categoryHeader');
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

                $event->sheet->setTitle('Category Name');

                $categories = Category::all();

                $event->sheet->getRowDimension(1)->setRowHeight(30);
                $cell = ["A", "B"];
                for($i = 0; $i < count($cell); $i++) {
                    $event->sheet->getStyle($cell[$i])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                }
                
                $event->sheet->getStyle('A1'.':B1')->applyFromArray([
                    
                        'font' => [
                            'bold' => true,
                            'size' => 16,
                            'underline' => true,
                        ],
                        'alignment' => ['vertical' => 'center'],
                        
                ]);

                $event->sheet->getStyle('A2'.':B2')->applyFromArray([
                    
                        'font' => ['bold' => true,],
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

                $event->sheet->getRowDimension(2)->setRowHeight(20);
                $event->sheet->setCellValue('A2', "ID");
                $event->sheet->setCellValue('B2', "Category Name");

                
                for($i = 3; $i < count($categories)+3; $i++) {
                    $event->sheet->getRowDimension($i)->setRowHeight(20);
                    $event->sheet->getStyle('A'.$i.':'.'B'.$i)->applyFromArray([
                        'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                        ],
                        
                    ]);
                    $event->sheet->setCellValue('A'.$i, $i-2);
                    $event->sheet->setCellValue('B'.$i, $categories[$i-3]->category_name);
                }
                
            },
        ];
    }

    /**
     *Category name sheet's column widths
     *
     * @author yarzartinshwe
     * @created 2023-6-23
     *
     */
    public function columnWidths(): array
    {
        return [
            'A' => 20, 
            'B' => 20, 
            'C' => 20,  
            'D' => 20, 
            'E' => 20,  
            'F' => 20, 
          
        ];
    }

    
}
