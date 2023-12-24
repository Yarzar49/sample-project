<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ItemsExport implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize, WithEvents, WithColumnWidths
{
    /*
     * Excel sheet for Download results
     *
     * @author yarzartinshwe
     *
     * @created 2023-6-23
     *
     */

    private $itemsToDownload;

    /**
     * Constructor to assign interface to variable
     */
    public function __construct($itemsToDownload)
    {
        $this->itemsToDownload = $itemsToDownload;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        //
        return $this->itemsToDownload;
    }


    /**
     *Excel sheet title for Download results
     *
     * @author yarzartinshwe
     * @created 2023-6-23
     *
     */
    public function title(): string
    {
        return 'Items';
    }

    /**
     *Excel sheet headings  for Download results
     *
     * @author yarzartinshwe
     * @created 2023-6-23
     *
     */
    public function headings(): array
    {
        return ['Item ID', 'Item Code', 'Item Name', 'Category', 'Safety Stock', 'Received Date', 'Description'];
    }

    /**
     *Excel sheet styling  for Download results
     *
     * @author yarzartinshwe
     * @created 2023-6-23
     *
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRangeHeader = 'A1:G1'; // Cell range for headers
                $cellRangeData = 'A2:G999'; // Cell range for data (replace 999 with the last row number)

                // Set dark blue background and white font color for A1 and B1
                $darkBlueColor = new Color();
                $darkBlueColor->setRGB('9DB2BF');
                $borderColor = new Color();
                $borderColor->setRGB('DDE6ED');
                $fontColor = new Color();
                $fontColor->setRGB('27374D');
                $event->sheet->getDelegate()->getStyle($cellRangeHeader)->applyFromArray([
                    'font' => [
                        'color' => ['rgb' => 'FFFFFF'], // White font color
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '009688'], // Dark blue background
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER, // Center align text horizontally
                        'vertical' => Alignment::VERTICAL_CENTER, // Center align text vertically
                    ],
                ]);


                $event->sheet->getDelegate()->getStyle('A2:B2')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['rgb' => $borderColor->getRGB()], // Dark blue border color
                        ],
                    ]
                ]);

                // Set dark blue font color and white background for A2 and B2 onwards
                $event->sheet->getDelegate()->getStyle($cellRangeData)->applyFromArray([
                    'font' => [
                        'color' => ['rgb' => $fontColor->getRGB()], // Dark blue font color
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['rgb' => $borderColor->getRGB()], // Dark blue border color
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER, // Center align text horizontally
                        'vertical' => Alignment::VERTICAL_CENTER, // Center align text vertically
                    ],
                ]);

                // Apply line breaks to the "Description" column
                $descriptionColumn = 'G'; // Column index of the "Description" column
                $event->sheet->getDelegate()->getStyle($descriptionColumn)->getAlignment()->setWrapText(true);


                $event->sheet->getDelegate()->getStyle($cellRangeHeader)->getFont()->setSize(14);

                // Adjust row height to accommodate the border
                $worksheet = $event->sheet->getDelegate();
                $rowHeight = 55; // Adjust this value as needed
                $worksheet->getDefaultRowDimension()->setRowHeight($rowHeight);
            },
        ];
    }

    /**
     *Excel column widths
     *
     * @author yarzartinshwe
     * @created 2023-7-12
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
            'G' => 50,
        ];
    }
}
