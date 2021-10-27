<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Uconomy') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200" id="dash">
                <section id="transact1">
                    <form wire:submit.prevent="submit">
                        <div class="form-group">
                            <label for="purchaseAmount">Enter  Amount: </label>
                            <input type="text" class="form-control" id="purchaseAmount" placeholder="{{ $amount }}" wire:model="amount">
                            @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    
                        <div class="form-group">
                            <label for="category">Category</label>
                            <input type="text" class="form-control" id="category" placeholder="{{ $category }}" wire:model="category">
                            @error('category') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" placeholder="{{ $description }}" wire:model="description"></textarea>
                            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="zelle">Zelle</label>
                            <textarea class="form-control" id="zelle" placeholder="{{ $zelle }}" wire:model="description"></textarea>
                            @error('zelle') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <section id="">
                            Suggested payment dates:
                            <div id="calendar"> 
                            </div>
                        </section>                            
                        </div>

                        <x-jet-button id="upay-button" type="submit" wire:click="">
                            {{ __('Continue') }}
                        </x-jet-button>  

                    </form>
                </section>
            </div>
        </div>
    </div>
</div>