<div>
    @if($kyc == null)
        @livewire('k-y-c')
    @elseif($plaid == null)
        @livewire('plaid')
    @else
        Congrats Uconomer, you're verified and have connected a bank account!
    @endif
</div>
