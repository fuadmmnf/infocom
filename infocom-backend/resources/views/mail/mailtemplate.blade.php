<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Complain Acknowledged</title>

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
    <p>Greetings from Infocom. Thank you for writing to us.</p>

    <p>We have acknowledged and forwarded your complain/requirement (TT#{{$complain->id}}) to our
        concern team for investigation. We aim to get back to you with an update at the shortest
        possible time. We highly appreciate your patience and cooperation in this issue.
    </p>
    <p>We are available 24/7 to assist you, please feel free to call us at <a href="tel:+09614224466">+09614224466</a>
        or email –
        <a href="mailto: help@aktelecom.net">help@aktelecom.net</a>.</p>

    <br/><br/>
    <span>Sincerely, <br/>
        Customer Service Department <br/>
        <img src="{{asset('images/maillogo.png')}}" alt="mail logo" width="190px"/><br/>
        A.K. Khan Telecom Limited
    </span>
</div>
</body>
</html>
