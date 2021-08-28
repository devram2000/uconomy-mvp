<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
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
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        {{ __('There was a problem with your verification. Please try again after 15 minutes. Contact us if this issue persists.') }}
                    </div>
                @elseif(request()->get('verified') == "1")
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        {{ __('Email Verified!') }}
                    </div>
                @elseif(request()->get('verified') == "2")
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        {{ __('Phone Number Verified!') }}
                    </div>
                @endif


                {{-- @if(Session::has('fail'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        {{Session::get('fail')}}
                    </div>
                @endif --}}


                  
                
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">

                </div>

            </div>
        </div>
    </div>
    

</x-app-layout>
