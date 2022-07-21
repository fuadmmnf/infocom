<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Infocom Notice</title>

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
    <p>{{$message}}</p>

    <br/><br/>
    <span>Regards, <br/>
        Customer Service Department <br/>
        <img src="{{asset('images/maillogo.png')}}" alt="mail logo" width="190px"/><br/>
        A.K. Khan Telecom Limited
    </span>
</div>
</body>
</html>
