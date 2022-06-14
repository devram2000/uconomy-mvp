<div class="mt-10 sm:mt-0">
<x-jet-form-section submit="saveDefault">
    <x-slot name="title">
        {{ __('Default Payment Schedule') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Please pick a default payment schedule for your transactions. You can customize your payment dates after a transaction is made as well.') }}
    </x-slot>

    <x-slot name="form">
     

        <div class="col-span-6 sm:col-span-4">

        <div id="">
            <div class="">
                <div id="" class="flex flex-col flex-nowrap justify-center">
                    

                    <div class="" >
                        {{ __('How would you like your payments to be set?') }} 
                    </div>

                    <x-jet-input-error for="payment_length" class="mt-2" />

                    <select class="mt-2" name="payment_length" id="" wire:model="payment_length" wire:change="">
                        <option selected="selected">{{ __('') }}</option>
                        <option value="weekly">{{ __('Weekly') }}</option>
                        <option value="biweekly">        {{ __('Biweekly') }}</option>
                        <option value="monthly">        {{ __('Monthly') }}</option>
                    </select>
                    @if($payment_length == "monthly")
                    <div class="mt-4">
                        {{ __('On which day would you like your payments to be on every month?') }} 
                    </div>
                    <x-jet-input-error for="monthly" class="mt-2" />

                    <select class="mt-2" name="monthly" id="" wire:model="monthly" wire:change="">
                        <option selected="selected">{{ __('') }}</option>
                        <option value="1">{{ __('1st') }}</option>
                        <option value="2">{{ __('2nd') }}</option>
                        <option value="3">{{ __('3rd') }}</option>
                        <option value="4">{{ __('4th') }}</option>
                        <option value="5">{{ __('5th') }}</option>
                        <option value="6">{{ __('6th') }}</option>
                        <option value="7">{{ __('7th') }}</option>
                        <option value="8">{{ __('8th') }}</option>
                        <option value="9">{{ __('9th') }}</option>
                        <option value="10">{{ __('10th') }}</option>
                        <option value="11">{{ __('11th') }}</option>
                        <option value="12">{{ __('12th') }}</option>
                        <option value="13">{{ __('13th') }}</option>
                        <option value="14">{{ __('14th') }}</option>
                        <option value="15">{{ __('15th') }}</option>
                        <option value="16">{{ __('16th') }}</option>
                        <option value="17">{{ __('17th') }}</option>
                        <option value="18">{{ __('18th') }}</option>
                        <option value="19">{{ __('19th') }}</option>
                        <option value="20">{{ __('20th') }}</option>
                        <option value="21">{{ __('21st') }}</option>
                        <option value="22">{{ __('22nd') }}</option>
                        <option value="23">{{ __('23rd') }}</option>
                        <option value="24">{{ __('24th') }}</option>
                        <option value="25">{{ __('25th') }}</option>
                        <option value="26">{{ __('26th') }}</option>
                        <option value="27">{{ __('27th') }}</option>
                        <option value="28">{{ __('28th') }}</option>
                        <option value="-1">{{ __('Last Day of the Month') }}</option>
                    </select>

                    @elseif($payment_length == "biweekly")
                    <div class="mt-4">
                        {{ __('On which day would you like your payments to be on every other week?') }} 
                    </div>
                    <x-jet-input-error for="biweekly" class="mt-2" />

                    <select class="mt-2" name="biweekly" id="" wire:model="biweekly" wire:change="">
                        <option selected="selected">{{ __('') }}</option>
                        <option value="monday">{{ __('Monday') }}</option>
                        <option value="tuesday">{{ __('Tuesday') }}</option>
                        <option value="wednesday">{{ __('Wednesday') }}</option>
                        <option value="thursday">{{ __('Thursday') }}</option>
                        <option value="friday">{{ __('Friday') }}</option>
                        <option value="saturday">{{ __('Saturday') }}</option>
                        <option value="sunday">{{ __('Sunday') }}</option>
                    </select>

                    @elseif($payment_length == "weekly")
                    <div class="text-sm mt-4">
                        <x-jet-input-error for="days" class="mb-4" />
                        <div class='flex flex-wrap justify-center'>
                        <div class='flex flex-col items-center h-12 m-2'>
                        <input type="checkbox" id="date0" name="date0" wire:model="days.0" >
                        <label for="date0"> Monday</label><br>
 
                        </div>
                        <div class='flex flex-col items-center h-12 m-2'>
                        <input type="checkbox" id="date1" name="date1" wire:model="days.1">
                        <label for="date1"> Tuesday</label><br>

                        </div>
                        <div class='flex flex-col items-center h-12 m-2'>
                        <input type="checkbox" id="date2" name="date2" wire:model="days.2">
                        <label for="date2"> Wednesday</label><br><br>

                        </div>
                        <div class='flex flex-col items-center h-12 m-2'>
                        <input type="checkbox" id="date3" name="date3" wire:model="days.3" >
                        <label for="date3"> Thursday</label><br>

                        </div>
                        <div class='flex flex-col items-center h-12 m-2'>
                        <input type="checkbox" id="date4" name="date4" wire:model="days.4">
                        <label for="date4"> Friday</label><br>

                        </div>
                        <div class='flex flex-col items-center h-12 m-2'>
                        <input type="checkbox" id="date5" name="date5" wire:model="days.5">
                        <label for="date5"> Saturday</label><br><br>

                        </div>
                        <div class='flex flex-col items-center h-12 m-2'>
                        <input type="checkbox" id="date6" name="date6" wire:model="days.6">
                        <label for="date6"> Sunday</label><br><br>

                        </div></div>

                        </div>

                    @endif

                    <div class="mt-2" >
                        {{ __('How long would you like your payments to default over? Uconomy charges a subscription fee per month you hold a balance.') }} 
                    </div>
                    <x-jet-input-error for="payment_months" class="mt-2" />

                    <select class="mt-2" name="payment_months" id="" wire:model="payment_months" wire:change="">
                        <option selected="selected">{{ __('') }}</option>
                        <option value="1">{{ __('~1 Month') }}</option>
                        <option value="2">        {{ __('~2 Months') }}</option>
                        <option value="3">        {{ __('~3 Months') }}</option>
                    </select>
                </div>

            </div>
        </div>
        </div>
        
        



    </x-slot>
    <x-slot name="actions">
   
        
        @if($saved)
        <div class="mr-3 text-sm text-gray-600">
            {{ __('Saved.') }}
        </div>
        @endif
        
        <x-jet-button>
            {{ __('Save') }}
        </x-jet-button>

    </x-slot>
</x-jet-form-section>


</div>
<x-jet-section-border />
