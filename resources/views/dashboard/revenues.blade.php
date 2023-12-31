@extends('layouts.dashboard.app')

@section('content')
    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.revenues')
            </h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.revenues')</li>
            </ol>
        </section>

        <section class="content">

            <div class="row">

                <div class="col-md-12">

                    <div class="box box-primary">

                        <div class="box-header">

                            <h3 class="box-title" style="margin-bottom: 10px">@lang('site.revenues')</h3>

                            <br>
                            <hr>

                            <form action="{{ route('dashboard.revenues.index') }}" method="get">

                                <div class="row">

                                    <div class="col-md-3">
                                        <select name="day" class="report-day">
                                            <option value="">@lang('site.all-days')</option>

                                            @foreach ($days as $index => $day)
                                                <option {{ request('day') == $index ? 'selected' : '' }}
                                                    value="{{ $index }}">{{ $day }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="month" class="report-month">
                                            <option value="">@lang('site.all-months')</option>

                                            @foreach ($months as $index => $month)
                                                <option {{ request('month') == $index ? 'selected' : '' }}
                                                    value="{{ $index }}">{{ $month }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="year" class="report-year">
                                            <option value="">@lang('site.all-years')</option>

                                            @foreach ($years as $index => $year)
                                                <option {{ request('year') == $year ? 'selected' : '' }}
                                                    value="{{ $year }}">{{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>
                                            @lang('site.search')</button>
                                    </div>

                                </div><!-- end of row -->

                            </form><!-- end of form -->

                        </div><!-- end of box header -->

                        @if ($order_count > 0)
                            <div class="box-body table-responsive" id="div-to-export">

                                <table class="table table-hover revenues-table" id="table-to-export">
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('site.client-name')</th>
                                        <th>@lang('site.orders-number')</th>
                                        <th>@lang('site.products')</th>
                                        <th>@lang('site.all-sales')</th>
                                        <th>@lang('site.status')</th>
                                        <th>@lang('site.action')</th>
                                    </tr>
                                    @foreach ($orders as $index => $order)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $order->client_name }}</td>
                                            <td>{{ $order->order_id }}</td>
                                            <td>{{ $order->quantity }} @lang('site.product')</td>
                                            <td>{{ $order->total_price }}</td>
                                            <td>@lang("site.$order->status")</td>
                                            <td><a href="{{route("dashboard.orders.show",$order->order_id)}}" class="btn btn-primary btn-sm">@lang('site.show')</a></td>
                                        </tr>
                                    @endforeach

                                </table><!-- end of table -->

                                <div class="card">
                                    <div class="card-body">
                                        <hr>
                                        <h3 class="card-title">المجموع الكلى: <span>{{ number_format($total_sales, 2) }}
                                                جنيها </span></h3>
                                        <hr>
                                        <h3 class="card-title">المكسب الكلى: <span>{{ number_format($total_profit, 2) }}
                                                جنيها </span></h3>
                                        <hr>
                                        <h3 class="card-title"> عدد المنتجات التى تم بيعها : <span>{{ $total_product }}
                                                منتج </span></h3>
                                        <hr>
                                        <button class="btn btn-success " onclick="exportToExcel()">Export to Excel</button>
                                        <hr>
                                    </div>
                                </div>



                            </div>
                        @else
                            <div class="box-body">
                                <h3>@lang('site.no_records')</h3>
                            </div>
                        @endif


                    </div><!-- end of box -->


                </div><!-- end of col -->

            </div><!-- end of row -->

        </section><!-- end of content section -->

    </div><!-- end of content wrapper -->
@endsection

@push('scripts')
    <script>
        function exportToExcel() {
            var divContent = document.getElementById("div-to-export").innerHTML;
            var workbook = XLSX.utils.table_to_book(document.getElementById("table-to-export"));
            XLSX.writeFile(workbook, 'exported-data.xlsx');
        }
    </script>
@endpush
