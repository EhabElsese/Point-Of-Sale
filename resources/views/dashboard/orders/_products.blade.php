<div id="print-area">

    <h3 class="text-center" >@lang('site.Purches_Bill')</h3>
        <div class="row">
            <div class="col-lg-6">
                <p style="margin-bottom: 10px">@lang('site.client-name') : <span>{{ $order->client->name }}</span></p>
                <p style="margin-bottom: 10px">@lang('site.date-bill') : <span>{{ date_format($order->created_at,'Y-m-d') }}</span></p>

            </div>
            <div class="col-lg-6">
                <p style="margin-bottom: 10px">@lang('site.Bill-number') : <span>{{ $order->id }}</span></p>
                <p style="margin-bottom: 10px">@lang('site.clock') : <span>{{ date_format($order->created_at,' H : i : s ') }}</span></p>
            
            </div>
        </div>
        
    <table class="table table-hover table-bordered">

        <thead>
        <tr>
            <th>@lang('site.name')</th>
            <th>@lang('site.quantity')</th>
            <th>@lang('site.price')</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->pivot->quantity }}</td>
                <td>{{ number_format($product->pivot->quantity * $product->sale_price, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <h3>@lang('site.total') <span>{{ number_format($order->total_price, 2) }}</span></h3>

</div>

<button class="btn btn-block btn-primary print-btn"><i class="fa fa-print"></i> @lang('site.print')</button>
