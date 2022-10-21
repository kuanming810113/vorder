<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>vueorder</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <script src="{{ asset(mix('js/app.js')) }}" defer></script>

    <style type="text/css">
        .v-messages__wrapper{
            padding-bottom: 10px;
        }
    </style>

</head>

<body>
    <noscript>
        <strong>很抱歉，管理模板在沒有啟用 JavaScript 的情況下無法正常工作。請啟用它以繼續。</strong>
    </noscript>
    <div id="app">
    </div>
</body>

</html>

