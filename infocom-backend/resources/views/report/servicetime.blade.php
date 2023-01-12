@extends('reportlayout')

@section('title')
    Complain Service Time Report
@endsection

@section('report_title')
    Complain Service Time Report ({{ date('F d, Y', strtotime($start)) }}-{{ date('F d, Y', strtotime($end)) }})
@endsection

@section('content')


    <table class="bordertable">
        <thead>
        <tr>
            <th>বিক্রয় রশিদ নং</th>
            <th>তারিখ</th>
            <th>কাস্টমার</th>

            <th>মূল্য</th>
        </tr>
        </thead>
        <tbody>

{{--        @foreach($saleitems as $saleitem)--}}
{{--            <tr>--}}
{{--                <td>{{ $saleitem->sale->code }}</td>--}}
{{--                <td align="center">{{ bangla(date('F d, Y', strtotime(display_local_time($saleitem->created_at)))) }}</td>--}}
{{--                <td>{{ $saleitem->sale->customer->name }}</td>--}}
{{--                <td align="center">--}}
{{--                    {{ $saleitem->quantity }}--}}
{{--                    @if($saleitem->product_code != '')<br/>--}}
{{--                    <small>কোডঃ {{ $saleitem->product->code }}</small> @endif--}}
{{--                    @if($saleitem->warrenty != '' && $saleitem->warrenty != ' ')<br/> <small>ওয়ারেন্টিঃ {{ $saleitem->warrenty }}</small> @endif--}}
{{--                </td>--}}
{{--                <td align="right">--}}
{{--                    {{ $saleitem->selling_price }} ৳--}}
{{--                    @if($saleitem->vat > 0)<br/> <small>ভ্যাটঃ{{ $saleitem->vat }}%</small> @endif--}}
{{--                    @if($saleitem->discount > 0)<br/>--}}
{{--                    <small>ডিস্কাউন্টঃ {{ $saleitem->discount }} {{ code_to_discount_unit($saleitem->discount_unit) }}</small> @endif--}}
{{--                </td>--}}
{{--                <td align="right">{{ calculate_item_total($saleitem) }} ৳</td>--}}
{{--                {{$total = $total + calculate_item_total($saleitem)}}--}}

{{--            </tr>--}}
{{--        @endforeach--}}
        {{-- <tr>
          <td colspan="2" align="right">মোট বর্তমান স্টক</td>
          <td align="center">{{ $stocks->sum('current_quantity') }}</td>
          <td colspan="3"></td>
        </tr> --}}
        <tr>
            {{--          <td></td>--}}
            {{--          <td></td>--}}
            {{--          <td></td>--}}
            {{--          <td></td>--}}
            <td colspan="5" align="right">মোট</td>
            <td align="right"> ৳</td>
        </tr>
        </tbody>
    </table>

@endsection
