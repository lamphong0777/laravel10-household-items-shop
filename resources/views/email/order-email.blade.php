<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #4CAF50;
            color: #ffffff;
            padding: 10px 20px;
            text-align: center;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .invoice-info {
            padding: 20px;
            line-height: 1.6;
        }

        .invoice-info h2 {
            margin-top: 0;
            font-size: 20px;
        }

        .invoice-info p {
            margin: 5px 0;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .invoice-table th,
        .invoice-table td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }

        .invoice-table th {
            background-color: #f2f2f2;
        }

        .total {
            font-weight: bold;
            font-size: 18px;
            text-align: right;
        }

        .footer {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 10px;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            font-size: 14px;
        }

        .footer a {
            color: #ffffff;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Mã hóa đơn #{{ $mailData['order']->id }}</h1>
        </div>

        <!-- Invoice Information -->
        <div class="invoice-info">
            <h2>Chi tiết hóa đơn</h2>
            <p><strong>Khách hàng:</strong> {{ $mailData['order']->name }}</p>
            <p><strong>Ngày đặt:</strong> {{ date_format($mailData['order']->created_at, 'd/m/Y H:i:s') }}</p>
            <p><strong>Email:</strong> {{ $mailData['order']->email }}</p>
            <p><strong>Điện thoại:</strong> {{ $mailData['order']->phone }}</p>
            <p><strong>Địa chỉ:</strong> {{ $mailData['order']->address }}</p>
            <p><strong></strong> {{ $mailData['order']->ward->name }}, {{ $mailData['order']->district->name }},
                Tp: {{ $mailData['order']->province->name }}</p>
        </div>

        <!-- Invoice Table -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mailData['order']->items as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>{{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>{{ number_format($item->total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="total">Tạm tính</td>
                    <td>{{ number_format($mailData['order']->subtotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="total">Vận chuyển</td>
                    <td>{{ number_format($mailData['order']->shipping, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="total">Giảm giá</td>
                    <td>{{ number_format($mailData['order']->discount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="total">Tổng</td>
                    <td>{{ number_format($mailData['order']->grand_total, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p>Thế giới gia dụng xin cảm ơn quý khách!</p>
            <p>Liên hệ với chúng tôi: <a href="thegioigiadung@example.com">thegioigiadung@example.com</a> Nếu bạn có
                thắc mắc.</p>
        </div>
    </div>
</body>

</html>
