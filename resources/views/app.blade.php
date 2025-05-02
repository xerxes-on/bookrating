<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <link rel="icon" href="/images/logo.png"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Lets Rate</title>
    <link rel="stylesheet" href="src/assets/css/output.css"/>
    <!--      fontawesome-->
    <script src="https://kit.fontawesome.com/9c18f8edbf.js" crossorigin="anonymous"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        window.mainAppUrl = @json(config('app.url')) + "/api/v1";
    </script>
</head>
<body class="bg-primary ">
    <div id="app" class="overflow-hidden"></div>
</body>
</html>
