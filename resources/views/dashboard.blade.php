<x-app-layout>
    @push('scripts')
    <script
    src="https://code.jquery.com/jquery-3.3.1.js"
    integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
    crossorigin="anonymous"></script>


    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>



    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />


    @endpush

    <x-slot name="header">
    <div class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Uconomy') }}
    </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                {{-- <x-jet-welcome /> --}}
                {{-- @if(Session::has('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        {{Session::get('success')}}

                    </div>
                @endif --}}
                @if(request()->get('verified') == "0")
                    <script>window.location = "/user/profile?verified=0";</script>


                @elseif(request()->get('verified') == "1")
                    <script>window.location = "/user/profile?verified=1";</script>
                  
                @elseif(request()->get('verified') == "2")
                    <script>window.location = "/user/profile?verified=2";</script>

                @endif
                



                  
                
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200" id="dash">
                    @livewire('start-u-pay')
                </div>

            </div>
        </div>
    </div>
    

</x-app-layout>
