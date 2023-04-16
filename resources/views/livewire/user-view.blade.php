<div>
@push('scripts')

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>



<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />


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
                <section id="userView">
                    @if ($isUser) 
                    <h1>{{ $user->id }}{{ __(': ') }}{{ $user->name }}</h1>
                    <div id="user-information">
                        <div>
                            {{ __('Email: ') }}{{ $user->email }}
                            @if($user->email_verified_at == NULL)
                            (Not Verified)
                            @else
                            (Verified)
                            @endif
                        </div>
                        <div>
                            {{ __('Phone Number: ') }}{{ $user->phone_number }}
                            @if($user->phone_verified_at == NULL)
                            (Not Verified)
                            @else
                            (Verified)
                            @endif
                        </div>
                        <div>{{ __('Date of Birth: ') }}{{ $user->date_of_birth }}</div>

                    </div>

                    <section class="" id="userview-transactions">

                        @foreach($bills_payments as $bill)
                    <div class="mt-2">
                        <div class="font-bold text-xl">Bill Reference #: {{ 1000 + $bill[0]['id'] }}</div>

                    
                        <div class="transaction-sub mt-2"><b>Status</b>: 
                        @if($bill[0]['status'] == NULL)
                            Waiting on Payment
                        @elseif($bill[0]['status'] == 0)
                            Payment Confirmed - Processing
                        @elseif($bill[0]['status'] == 1)
                            Complete
                        @elseif($bill[0]['status'] == 2)
                            Refunded
                        @else
                            Other
                        @endif
                        </div>
                        <b>Change this Bill's Status: </b>
                        <button wire:click="updateBillStatus({{ $bill[0]['id'] }}, null)" class="dropdown-item">Waiting on Payment</button>
                        <button wire:click="updateBillStatus({{ $bill[0]['id'] }}, 0)" class="dropdown-item">Payment Received - Processing</button>
                        <button wire:click="updateBillStatus({{ $bill[0]['id'] }}, 1)" class="dropdown-item">Completed</button>
                        <button wire:click="updateBillStatus({{ $bill[0]['id'] }}, 2)" class="dropdown-item">Refunded</button>
                        <button wire:click="updateBillStatus({{ $bill[0]['id'] }}, 3)" class="dropdown-item">Other</button>

                        <div class="transaction-sub mt-2">
                            <b>(Optional) Status Message to Display for User:</b>
                            <textarea wire:model.defer="newMessage" placeholder="{{ $bill[0]['comments']}}" class="form-control"></textarea>
                            <button class="btn btn-primary mt-2" wire:click.prevent="updateBillMessage({{ $bill[0]['id'] }})">Update</button>
                        </div>
                        <div class="transaction-sub mt-2"><b>Request Creation Date</b>: {{ date('m/d/Y h:i:s', strtotime($bill[0]['created_at'])) }}</div>

                        <div class="transaction-sub mt-2"><b>Picture:</b></div>

                        <iframe class="mt-2" src="/storage/bills/{{ $bill[0]['bill'] }}" width="100%" height="500"></iframe>

                        <div class="transaction-sub mt-2"><b>Preferred Dates:</b></div>

                        @livewire('view-calendar', ['events_and_fees' => $bill[1], 'name' => $bill[0]['id']])

                    </div>
                    @endforeach

                        

                    </div>




                    @else
                        <h1>{{ __('No user with ID ') }}{{ $userID }}<h1>
                    @endif
                </section>
            </div>
        </div>
    </div>
</div>

</div>