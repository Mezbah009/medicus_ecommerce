<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            font-size: 24px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        p.color-text, p.size-text {
            font-size: small;
            color: #212529;
            margin: 0 0;
            padding-bottom: 2px;
        }
        th {
            background-color: #ccc;
        }
        /*.card-body p {*/
        /*    display: table-cell;*/
        /*}*/
    </style>

</head>
<body>
<h1>Order Details</h1>
<h2>Shipping Address</h2>
<address>
    <strong>{{$order->first_name.' '.$order->last_name}}</strong><br>
    {{$order->address}}<br>
    {{$order->city}}, {{$order->zip}}, {{getCountryInfo($order->country_id)->name}}<br>
    Phone: {{$order->mobile}}<br>
    Email: {{$order->email}}
</address>
<table>
    <tr>
        <th>Order ID</th>
        <td>{{ $order->id }}</td>
    </tr>
    <tr>
        <th>Order Date</th>
        <td>{{ $order->created_at }}</td>
    </tr>
    <tr>
        <th>Country</th>
        <td>{{ $order->countryName }}</td>
    </tr>
</table>

<h1>Order Items</h1>
<table>
    <tr>
        <th>Product</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Total</th>
    </tr>
    @foreach ($orderItems as $item)
        <tr>
            <td class="card-body">{{$item->name}}
                @if($item->product_item != null)
                    @php
                        $decodedColor = !empty($item->product_item->variation_color) ? json_decode($item->product_item->variation_color, true) : null;
                        $decodedSize = !empty($item->product_item->variation_size) ? json_decode($item->product_item->variation_size, true) : null;
                    @endphp
                    @if (!empty($decodedColor[0]['name']))
                        <p class="color-text">Color: {{$decodedColor[0]['name']}},</p>
                    @endif

                    @if (!empty($decodedSize[0]['name']))
                        <p class="size-text">Size: {{$decodedSize[0]['name']}}</p>
                    @endif
                @endif
            </td>
            <td>{{$item->qty}}</td>
            <td><img src="{{ public_path('taka.png') }}" width="20px" class="filament-link-icon w-4 h-4 mr-1" alt="Icon" />{{number_format($item->price,2)}}</td>
            <td ><img src="{{ public_path('taka.png') }}" width="20px" class="filament-link-icon w-4 h-4 mr-1" alt="Icon" />{{ number_format($item->total, 2) }}</td>
        </tr>
        @endforeach
<tr>
    <th colspan="3" align="right">Subtotal:</th>
    <td><img src="{{ public_path('taka.png') }}" width="20px" class="filament-link-icon w-4 h-4 mr-1" alt="Icon" />{{number_format($order->subtotal,2)}}</td>
</tr>
<tr>
    <th colspan="3" align="right">Discount:{{(!empty($order->cupon_code)) ? '('.$order->cupon_code.')' : '' }}</th>
    <td><img src="{{ public_path('taka.png') }}" width="20px" class="filament-link-icon w-4 h-4 mr-1" alt="Icon" />{{number_format($order->discount,2)}}</td>
</tr>

<tr>
    <th colspan="3" align="right">Shipping:</th>
    <td><img src="{{ public_path('taka.png') }}" width="20px" class="filament-link-icon w-4 h-4 mr-1" alt="Icon" />{{number_format($order->shipping,2)}}</td>
</tr>
<tr>
    <th colspan="3" align="right">Grand Total:</th>
    <td><img src="{{ public_path('taka.png') }}" width="20px" class="filament-link-icon w-4 h-4 mr-1" alt="Icon" />{{number_format($order->grand_total,2)}}</td>
</tr>
</table>
</body>
</html>
