<?php
 ?>
<!DOCTYPE html>
<html>
<head>
    <title>Excel Template</title>
</head>
<body>
    <table>
        <tr>
            <td colspan="6">
                {!! nl2br('*Do not allow the file that the number of rows is greater than 100 rows.' . "\n" . '*Please look at the Category Name sheet and enter that id in the Category Name ID column.') !!}
            </td>
        </tr>
        <tr>
        $headings = ['Item Code', 'Item Name', 'Category Name', 'Safety Stock', 'Received Date', 'Description'];
                $columns = ['A', 'B', 'C', 'D', 'E', 'F'];

                for ($i = 0; $i < count($headings); $i++) { 
                    $event->sheet->setCellValue($columns[$i]."2", $headings[$i]);
                }
        </tr>
    </table>
</body>
</html>
