<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/pagelogin.css')}}">

</head>
<body>
    <section id="about" class="about">
        <div class="container" data-aos="fade-up">
            @yield("isi")
        </div>
    </section>
</body>
</html>
@stack('script')
