<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Document</title>
</head>
<style>
    .table{
        width: 90%;
        border-collapse: collapse;
        font-family: DejaVu Sans;
        font-size: 10px;
        margin: 0 auto;
        font-weight: 700;
    }
    .table thead tr td{
        border: none;
    }
    .table tbody{
        font-weight: 700;
        text-align: center;
    }
    .table tbody tr:last-child{
        text-align: right;
    }
    .table tr td{
        padding: 10px;
    }
</style>
<body>
    <table class="table" border="1">
        @if (!empty($data))
            <thead>
                <tr style="text-align: center;">
                    <td colspan="2">
                        <img style="width:100%;" src="{{public_path('test_logo.png')}}" alt="logo">
                    </td>
                    <td colspan="3" style="text-align: left">
                        <p style="font-size: 12px;">Công ty Cổ phần DIAMONDS</p>
                        <p style="font-size: 10px;">SĐT: 0946636842</p>
                        <p style="font-size: 10px;">Email: shopdiamondsofficial@gmail.com</p>
                        <p style="font-size: 10px;">Website: diamonds.net</p>
                        <p style="font-size: 16px;">HÓA ĐƠN BÁN HÀNG</p>
                    </td>
                    <td>
                        <img style="width:150%;" src="{{public_path('stamp_paid2.png')}}" alt="paid">
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align:left;">
                        <p>Thông tin khách hàng</p>
                    </td>
                    <td colspan="3" style="text-align:left; border-left: 1.5px solid rgb(167, 167, 167);">
                        <p>Thông tin đơn hàng</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <p>Người nhận: {{$data['user']['name']}}</p>
                        <p>SĐT: {{$data['phone']}}</p>
                        <p>Email: {{$data['email'] ?? null}}</p>
                    </td>
                    <td colspan="3" style="border-left: 1.5px solid rgb(167, 167, 167);">
                        <p>Mã đơn hàng: {{$data['code']}}</p>
                        <p>Địa chỉ giao hàng: {{$data['address'].', '.$data['ward']['name'].', '.$data['district']['name'].', '.$data['province']['name']}}</p>
                        <p>Phương thức vận chuyển: {{$data['getShippingMethod']['name']}}</p>
                        <p>Phương thức thanh toán: {{$data['getPaymentMethod']['name']}}</p>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width:5%;">#</td>
                    <td>Mã sản phẩm</td>
                    <td>Tên sản phẩm</td>
                    <td>Đơn giá</td>
                    <td style="width:5%;">SL</td>
                    <td>Thành tiền</td>
                </tr>
                @foreach($data['details'] as $key => $item)
                    <tr>
                        <td style="width:5%;">{{$key+1}}</td>
                        <td>{{$item['product']['code']}}</td>
                        <td>{{$item['product']['name'].', '.$item['variant']['variant']['variant_name'].', '.$item['variant']['pro_variant']['color']['name']}}</td>
                        <td>{{number_format($item['price']).'đ'}}</td>
                        <td style="width:5%;">{{$item['quantity']}}</td>
                        <td>{{number_format($item['price'] * $item['quantity']).'đ'}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="6">Tổng: {{number_format($data['total']).'đ'}}</td>
                </tr>
            </tbody>
            <tr>
                <td colspan="6">
                    <table border="0" style="width:100%;">
                        <tbody>
                            <tr>
                                <td style="vertical-align:top">
                                    <p>NV Bán hàng <br/> (ký, họ tên)</p>
                                    <br><br>
                                    <p>Nguyễn Văn Toàn</p>
                                </td>
                                <td style="vertical-align:top">
                                    <p>NV Thu ngân <br/> (ký, họ tên)</p>
                                    <br><br>
                                    <p>Nguyễn Thị Thái Nguyên</p>
                                </td>
                                <td style="vertical-align:top">
                                    <p>Khách hàng <br/> (ký, họ tên)</p>
                                    <br><br>
                                    <p>{{$data['user']['name']}}</p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align:center; border-top: 1px solid rgb(167, 167, 167);">
                                    <p>Cảm ơn quý khách đã mua hàng tại Diamonds ^^ !</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        @else
            <thead>
                <tr>
                    <td>Trống</td>
                </tr>
            </thead>
        @endif
    </table>
</body>
</html>

