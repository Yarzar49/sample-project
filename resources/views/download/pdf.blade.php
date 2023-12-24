<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
<style>
/* Custom table styles */
  .custom-table {
    border-collapse: collapse;
    width: 100%;
  }

  .custom-table thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    padding: 12px;
    text-align: left;
    font-weight: bold;
  }

  .custom-table tbody td {
    border-bottom: 1px solid #dee2e6;
    padding: 12px;
  }

  .custom-table tbody tr:last-child td {
    border-bottom: none;
  }
</style>
</head>
<body>
<div class="container">
  <h1>Items List</h1>
    <table class="custom-table">
    <thead>
        <tr>
        <th class="text-danger">No</th>
        <th>Item ID</th>
        <th>Item Code</th>
        <th>Item Name</th>
        <th>Category Name</th>
        <th>Safety Stock</th>
        <th>Received Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->item_id }}</td>
            <td>{{ $item->item_code }}</td>
            <td>{{ $item->item_name }}</td>
            <td>{{ $item->category_name }}</td>
            <td>{{ $item->safety_stock }}</td>
            <td>{{ $item->received_date }}</td>
        </tr>
        @endforeach
    </tbody>
    </table>
</div>
    
</body>
</html>