@push('scripts')
<script src="https://cdn.plaid.com/link/v2/stable/link-initialize.js"></script>
@endpush

<x-jet-form-section submit="createLink">
    <x-slot name="title">
        {{ __('Plaid') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Link a Bank Account.') }}
    </x-slot>

    <x-slot name="form">
     

        <div class="col-span-6 sm:col-span-4">

        <div id="card-container">
            <div class="panel">
                <div class=""> Connect your bank account with Plaid! </div>
            {{ $message }}
            @if($link_token != null)
                <script>
                const handler = Plaid.create({
                    token: @this.link_token,
                    onSuccess: async (public_token, metadata) => {
                        console.log('success');
                        console.log(public_token);
                        console.log(metadata);
                        @this.createAccount(public_token, metadata);
                    },
                    onLoad: async () => {
                        console.log('load');
                    },
                    onExit: async (err, metadata) => {
                        console.log('exit');
                        console.log(err);
                        console.log(metadata);


                    },
                    onEvent: async (metadata) => {
                        console.log('event');
                        console.log(metadata);


                    },
                    env: 'sandbox',
                    //required for OAuth; if not using OAuth, set to null or omit:
                    // receivedRedirectUri: window.location.href,
                    });
                console.log('handler created');   
                handler.open();
                </script>
            @endif

            </div>
        </div>
        </div>
        
        



    </x-slot>
    <x-slot name="actions">
   
        
        <x-jet-button>
            {{ __('Start') }}
        </x-jet-button>

    </x-slot>
</x-jet-form-section>

