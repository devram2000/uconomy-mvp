<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ViewCalendar extends Component
{
    public $events_and_fees;

    public function render()
    {
        return view('livewire.view-calendar');
    }
}
