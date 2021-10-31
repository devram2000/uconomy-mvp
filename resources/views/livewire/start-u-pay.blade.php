<section id="upay">
    <section id="upay-buy">
        <div id="upay-title"> 
            {{ __('UPay') }}
        </div>
        <div> 
            {{ __('Your amount available to spend is $') }}{{ $spending_amount }}
        </div>
        <div>
            <x-jet-button id="upay-button" type="button" wire:click="redirectUPay">
                {{ __('Create a Payment Plan') }}
            </x-jet-button>  
        </div>
    </section>
    @if($remaining_balance)
    <section id="upay-balance">
       
        <div id="payment-title"> 
            {{ __('Your upcoming payment dates') }} 
        </div> 
         <div>
            {{ __('Total Remaining Balance: $') }}{{ $remaining_balance }}
        </div></br>
        <div id="calendar"> 
        </div>
    </section>

    @endif
    @if($has_transactions)
    <section id="upay-transactions">
        <div id="transactions-title" class="text-center "> 
            {{ __('Your transactions') }}
        </div> </br>
        <table class="table-fixed w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2">Start Date</th>
                        <th class="px-4 py-2">Category</th>
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 py-2">Estimated Completion</th>
                        <th class="px-4 py-2">Remaining Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $item)
                    <tr>
                        <td class="text-center border px-4 py-2">{{ date('m/d/Y', strtotime($item->start_date)) }}</td>
                        <td class="text-center border px-4 py-2">{{ $item->category }}</td>
                        <td class="text-center border px-4 py-2">{{ $item->description}}</td>
                        <td class="text-center border px-4 py-2">{{ date('m/d/Y', strtotime($item->due_date))}}</td>
                        <td class="text-center border px-4 py-2">{{ __('$') }}{{ $item->amount}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

    </div>
    @endif
</div>    



<?php /* 
    <div class="p-6 border-t border-gray-200 md:border-l bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2">
    <div class="flex justify-between">
        <div class="p-6">
            <div class="flex items-center">
                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-400"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold">{{ __('UPay') }}</div>
            </div>
    
            <div class="ml-12">
                <div class="mt-2 text-sm text-gray-500">
                    {{ __('Start Beta Testing with UPay today!') }}
                </div>
            </div>
            
        </div>

                                        
    </div>  
    <div id="upay">
        <x-jet-button id="upay-button" type="button" wire:click="redirectUPay">
            {{ __('Start Today') }}
        </x-jet-button>  
    
    </div>    
</div>

*/ ?> 


