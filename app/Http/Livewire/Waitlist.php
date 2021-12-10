<?php

namespace App\Http\Livewire;

use Auth;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Waitlist extends Component
{
    public $waitlist;
    public $waitlist_count;

    public function render()
    {
        
        $this->waitlist = DB::connection('mysql2')->select('select email, date from wait_list_emails');
    
        $this->waitlist = array_map(function ($value) {
            if($value->date == NULL) {
                $value->date = 'N/A';
            }
            return $value;
        }, $this->waitlist);

        $this->waitlist_count = count($this->waitlist);


        

        return view('livewire.waitlist');

    }
}
