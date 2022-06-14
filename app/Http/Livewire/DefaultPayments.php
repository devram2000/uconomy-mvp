<?php

namespace App\Http\Livewire;

use Auth;
use Livewire\Component;
use Illuminate\Validation\ValidationException;
use App\Models\DefaultSchedule;

class DefaultPayments extends Component
{
    public $payment_length;
    public $saved;
    public $days;
    public $biweekly;
    public $monthly;
    public $payment_months;

    public function __construct() {   
        $this->saved = false;
        $this->days = [false, false, false, false, false, false, false];
        $schedule = DefaultSchedule::where('user', Auth::id())->first();
        if($schedule != null) {

            $this->payment_length = $schedule['payment_length'];
            $this->payment_months = $schedule['payment_months'];
            if ($this->payment_length == "weekly") {
                $this->days = [$schedule['monday'], $schedule['tuesday'], $schedule['wednesday'], $schedule['thursday'], $schedule['friday'], $schedule['saturday'], $schedule['sunday'], ];
            } else if ($this->payment_length == "biweekly") {
                if($schedule['monday']) {
                    $this->biweekly = 'monday';
                } else if($schedule['tuesday']) {
                    $this->biweekly = 'tuesday';
                } else if($schedule['wednesday']) {
                    $this->biweekly = 'wednesday';
                } else if($schedule['thursday']) {
                    $this->biweekly = 'thursday';
                } else if($schedule['friday']) {
                    $this->biweekly = 'friday';
                } else if($schedule['saturday']) {
                    $this->biweekly = 'saturday';
                } else if($schedule['sunday']) {
                    $this->biweekly = 'sunday';
                }
            } else if ($this->payment_length == "monthly") {
                $this->monthly = $schedule['date'];
            }

            
        }

    }

    public function saveDefault() {
        $validatedData = $this->validate([
            'payment_length' => "required",
            'payment_months' => "required",
        ]);
        if ($this->payment_length == "monthly"){
            $validatedData = $this->validate([
                'monthly' => "required",
            ]);
            DefaultSchedule::where('user', Auth::id())->delete();
            DefaultSchedule::create([
                'user' => Auth::id(),
                'payment_months' => $this->payment_months,
                'payment_length' => $this->payment_length,
                'date' => $this->monthly,
            ]);
        } else if ($this->payment_length == "biweekly"){
            $validatedData = $this->validate([
                'biweekly' => "required",
            ]);

            DefaultSchedule::where('user', Auth::id())->delete();
            DefaultSchedule::create([
                'user' => Auth::id(),
                'payment_months' => $this->payment_months,
                'payment_length' => $this->payment_length,
                $this->biweekly => 1,
            ]);
        } else if($this->payment_length == "weekly") {
            $filled = false;
            $last = false;
            $repeat = false;
            $i = 0;
            while ($i < count($this->days)) {
                if($last && $this->days[$i]) {
                    $repeat = true;
                }
                $filled = $filled || $this->days[$i];
                $last = $this->days[$i];
                $i++;
            }
            if (!$filled) {
                throw ValidationException::withMessages(['days' => 'Please pick at least one day.']);
            } else if ($repeat || ($this->days[0] && $this->days[6])) {
                throw ValidationException::withMessages(['days' => 'Please space your payments out by at least one day.']);
            } else {
                DefaultSchedule::where('user', Auth::id())->delete();
                DefaultSchedule::create([
                    'user' => Auth::id(),
                    'payment_months' => $this->payment_months,
                    'payment_length' => $this->payment_length,
                    'monday' => $this->days[0],
                    'tuesday' => $this->days[1],
                    'wednesday' => $this->days[2],
                    'thursday' => $this->days[3],
                    'friday' => $this->days[4],
                    'saturday' => $this->days[5],
                    'sunday' => $this->days[6],
                ]);
            }

        }
        $this->saved = true;
    }

    public function render()
    {
        $this->debit = Auth::user()->debit;
        if($this->debit == null) {
            return view('livewire.empty');
        } else {
            return view('livewire.default-payments');
        }
    }
}
