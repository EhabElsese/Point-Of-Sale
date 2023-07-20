@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.reports')
            </h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.reports')</li>
            </ol>
        </section>

        <section class="content">

            <div class="row">

                <div class="col-md-12">

                    <div class="box box-primary">

                        <div class="box-header">

                            <h3 class="box-title" style="margin-bottom: 10px">@lang('site.reports')</h3>

                            <form action="{{ route('dashboard.reports.index') }}" method="get">

                                <div class="row">

                                    <div class="col-md-3">
                                        <select name="day" class="report-day">
                                            <option value="" >@lang('site.all-days')</option>

                                            @foreach($days as $index => $day)
                                                <option   {{request('day') == $index ? "selected": ""}} value="{{$index}}" >{{$day}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="month" class="report-month">
                                            <option value="" >@lang('site.all-months')</option>

                                            @foreach($months as $index => $month)
                                                <option   {{request('month') == $index ? "selected": ""}} value="{{$index}}" >{{$month}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="year" class="report-year">
                                            <option value="" >@lang('site.all-years')</option>

                                            @foreach($years as $index => $year)
                                                <option   {{request('year') == $year ? "selected": ""}} value="{{$year}}" >{{$year}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('site.search')</button>
                                    </div>

                                </div><!-- end of row -->

                            </form><!-- end of form -->

                        </div><!-- end of box header -->

                        @if ($order > 0)

                            <div class="box-body table-responsive">

                                <table class="table table-hover">
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('site.month')</th>
                                        <th>@lang('site.orders')</th>
                                        <th>@lang('site.products')</th>
                                        <th>@lang('site.all-sales')</th>
                                        <th>@lang('site.all-profit')</th>
                                    </tr>


                                        <tr>
                                            <td>1</td>
                                            <td>test</td>
                                            <td>{{$order}}</td>
                                            <td>{{$product}}</td>
                                            <td>{{ $sales[0]->total_sales}}</td>


                                        </tr>



                                </table><!-- end of table -->



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
