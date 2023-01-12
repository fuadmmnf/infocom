<html>
<head>
    {{--    <title>@yield('title')</title>--}}

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 5px;
            /*font-family: 'kalpurush', 'adorsholipi', sans-serif;*/
            font-size: 14px;
        }

        th {
            font-size: 18px;
            font-weight: bold;
        }

        .bordertable td, th {
            border: 1px solid #A8A8A8;
        }

        tr.border_bottom td {
            border-bottom: 1.5px solid black;
        }

        .storeWaterMark {
            text-align: center;
            font-size: 20px;
            color: #b8cee3;
            opacity: 0.01 !important;
        }

        @page {
            header: page-header;
            footer: page-footer;
        {{--background: url({{ public_path('images/logo.png') }});--}}
        {{--:0.7;--}}
        {{--background-size: cover;--}}
        {{--background-repeat: no-repeat;--}}
        {{--background-position: center center;--}}

        }
    </style>
</head>
<body>

<main>
    <p align="center" style="line-height: 1.2;">
        {{--        @if($store->monogram != null)--}}
        @if(file_exists( public_path('images/maillogo.png')))
            <img src="{{ public_path('images/maillogo.png') }}" style="height: 40px; width: auto;">

        @endif
        {{--        @endif--}}
        {{--        <br/>--}}
        {{--        <span style="font-size: 25px">{{ $store->name }}</span>--}}
        {{--        <br/>--}}
        {{--        <small>ঠিকানাঃ {{ $store->address }} @if($store->upazilla !== '')--}}
        {{--                , {{ $store->upazilla }} @endif @if($store->district !== ''), {{ $store->district }} @endif</small>--}}
        {{--        <br/>--}}
        {{--    @if($store->proprietor)--}}
        {{--        <small>প্রোঃ {{ $store->proprietor }}</small>--}}
        {{--    @endif--}}
        {{--        <br/>--}}
        {{--        <span align="center" style="color: #397736; border-bottom: 1px solid #397736;">--}}
        @yield('report_title')
        {{--    </span>--}}
    </p>
    <div>@yield('content')</div>

</main>

<htmlpageheader name="page-header" name="page-header">

</htmlpageheader>
<htmlpagefooter name="page-footer" style="margin-top: 5px">
    {{--    <div class="storeWaterMark" style="opacity: 0.1;">--}}
    {{--        <big>{{ $store->name }}</big>--}}
    {{--        @if($store->slogan)--}}
    {{--            <br/>** {{ $store->slogan }} **--}}
    {{--        @endif--}}
    {{--    </div>--}}
    <table>
        <tr>
            <td width="50%">
                <small style="font-size: 12px; color: #525659;">Download Time: <span
                        style="font-family: Calibri; font-size: 12px;">{{ date('F d, Y, h:i A') }}</span></small>
            </td>
            <td align="right" style="color: #525659;">
                <small>
                    <span class="page-number">| পাতাঃ {PAGENO}/{nbpg} </span>
                </small>
            </td>
        </tr>
    </table>
    {{--    <table>--}}
    {{--        <tr>--}}
    {{--            <td width="70%" align="left">--}}
    {{--                <span style="font-size: 11px; color: #525659;">{{ $store->receipt_footer }}</span>--}}
    {{--            </td>--}}
    {{--            <td align="right">--}}
    {{--                <small style="font-family: Calibri; font-size: 11px; color: #3f51b5;">Powered by:--}}
    {{--                    http://dokankhata.com<br/>(01515297658)</small>--}}
    {{--            </td>--}}
    {{--        </tr>--}}
    {{--    </table>--}}
</htmlpagefooter>
</body>
</html>
Footer
