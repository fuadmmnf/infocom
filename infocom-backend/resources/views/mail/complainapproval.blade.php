<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Complain Approval</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>
<body class="antialiased">
<div class="relative  items-top min-h-screen bg-gray-100 dark:bg-gray-900  py-4 sm:pt-0">
    <p>Dear Sir,</p>
    <p>Thank you for staying with us.</p>

    <p>This email is to notify you that we believe this ticket (TT#{{$complain->id}}) has been resolved. Please visit
        the following link to provide your valuable feedback:<br/>
        <a href="{{config('app.frontend_url')}}/#complain/{{$complain->code}}"
           target="_blank">{{config('app.frontend_url')}}/#complain/{{$complain->code}}</a>
    </p>
    <p>To receive prompt attention if this is not the case, please reply to this email within next two days./Please
        feel welcome to re-open this ticket or open a new one if you need any further assistance.
    </p>
    <span>
        <b>
            We strive to provide excellent customer service. If you have any comments or questions about the
            handling of this ticket, please feel free to send an email message to
            <a href="mailto:help@aktelecom.net">help@aktelecom.net</a>
        </b>
    </span>

    <br/><br/>
    <span>Regards, <br/>
        Customer Service Department <br/>
        <img src="/images/maillogo.png" alt="mail logo" width="190px"/><br/>
        A.K. Khan Telecom Limited
    </span>
</div>
</body>
</html>
