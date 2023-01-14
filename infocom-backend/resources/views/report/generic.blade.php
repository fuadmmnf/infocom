@extends('reportlayout')

@section('title')
    Complain Service Time Report
@endsection

@section('report_title')
    {{$title}}
{{--    ({{ date('F d, Y', strtotime($start)) }}-{{ date('F d, Y', strtotime($end)) }})--}}
@endsection

@section('content')

    @if($logs->count() > 0)
        <table class="bordertable">
            <thead>
            <tr>
                @foreach(array_keys($logs[0]) as $header)
                    <th>{{$header}}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>

            @foreach($logs as $log)
                <tr>
                    @foreach(array_keys($logs[0]) as $header)
                        <th>{{$log[$header]?? ''}}</th>
                    @endforeach
                </tr>
            @endforeach
            {{-- <tr>
              <td colspan="2" align="right">মোট বর্তমান স্টক</td>
              <td align="center">{{ $stocks->sum('current_quantity') }}</td>
              <td colspan="3"></td>
            </tr> --}}
            {{--        <tr>--}}
            {{--            --}}{{--          <td></td>--}}
            {{--            --}}{{--          <td></td>--}}
            {{--            --}}{{--          <td></td>--}}
            {{--            --}}{{--          <td></td>--}}
            {{--            <td colspan="5" align="right">মোট</td>--}}
            {{--            <td align="right"> ৳</td>--}}
            {{--        </tr>--}}
            </tbody>
        </table>


    @endif

@endsection
