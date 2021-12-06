@push('scripts')

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>



@endpush
<x-slot name="header">
    <div class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Uconomy') }}
    </div>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200" id="dash">
                <section id="waitlist">
                    <div id="waitlist-head">
                        <h2> 
                        {{ __('Waitlist Count: ') }}{{ $waitlist_count }}
                        </h2>
                    </div>

                    <div id="waitlist-data">
                        <div id ="waitlist-emails">
                            <b>{{ __('Email:') }}</b>
                            @foreach($waitlist as $u)
                                <div>
                                    {{ $u->email }}
                                </div>
                            @endforeach
                        </div>
                        <div id ="waitlist-dates">
                            <b>{{ __('Date:') }}</b>
                            @foreach($waitlist as $u)
                                <div>
                                    {{ $u->date }}
                                </div>
                            @endforeach
                        </div>

                    </div>
                </section>
            </div>
        </div>
    </div>
</div>