<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="{{ url('uconomy-logo.svg') }}">

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->

        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <meta name="theme-color" content="#000000" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Uconomy</title>
        <meta name="description"
            content="A flexible and secure transaction method that allows you to customize your payment plan when acquiring items, paying bills, and receiving services." />
        <!-- <link rel="stylesheet" href="./index.css??php echo time(); ?"> -->
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-85K5CY8ZJ7');
        </script>




        <link rel="stylesheet" href="{{ asset('css/home.css') }}">



        @livewireStyles
        @stack('scripts')

        


        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>

    
    </head>
    <body class="font-sans antialiased">
        <x-jet-banner />

        <div class="">

        <header>
    
            <div id="container">
            <nav class="nav">
                <a class="scroll" href="#top">
                <img class="logo" src="/storage/home-photos/Logo.svg" alt="Uconomy Logo" width="196" height="45.5">
                </a>
                <div class="header-right">
                    <div class="login"><a  href=""><b>Login</b></a></div>
                    <div><button class="button1 button-text" id="homenav" onclick="location.href='mailto:help@uconomy.com'" type="button">Contact Us</button></div>
                </div>
            </nav>
            </div>
        </header>

            <!-- Page Content -->
                {{ $slot }}
        </div>

        @stack('modals')

        @livewireScripts
        <script>
          window.addEventListener('scroll', function () {
              let header = document.querySelector('header');
              let windowPosition = window.scrollY > 0;
              header.classList.toggle('scrolling-active', windowPosition);
          })
          
        </script>

    </body>

</html>
