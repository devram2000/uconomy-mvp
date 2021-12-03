<?php

namespace App\Http\Livewire;

use Auth;
use Livewire\Component;
use App\Models\Identification;
use Livewire\WithFileUploads;



class AddIdentity extends Component
{
    use WithFileUploads;
    public $identity;
    public $front_photo;
    public $back_photo;

    public function removePhotos() {
        $this->front_photo = NULL;
        $this->back_photo = NULL;
    }

    public function submitIdentification() {
        if($this->identity == "passport") {

            $validatedData = $this->validate([
                'front_photo' => "required|image|mimes:jpeg,png,jpg|max:5048",
            ]);
    
        
            $image1Name = Auth::id().'.'.$this->front_photo->extension();  
         
            $this->front_photo->storeAs('ids',  $image1Name, 'public');

            $transaction = Identification::create([
                'user' => Auth::id(),
                'type' => $this->identity,
                'photo1' => $image1Name,
            ]);
        } else {

            $validatedData = $this->validate([
                'front_photo' => "required|image|mimes:jpeg,png,jpg|max:10048",
                'back_photo' => "required|image|mimes:jpeg,png,jpg|max:10048",
            ]);
        
            $image1Name = Auth::id().'_1.'.$this->front_photo->extension();  
            $image2Name = Auth::id().'_2.'.$this->back_photo->extension();  
         
            $this->front_photo->storeAs('ids',  $image1Name, 'public');
            $this->back_photo->storeAs('ids',  $image2Name, 'public');

            $transaction = Identification::create([
                'user' => Auth::id(),
                'type' => $this->identity,
                'photo1' => $image1Name,
                'photo2' => $image2Name,
            ]);
        }

        return redirect('/user/profile?verified=3');

    }

    public function render()
    {
        return view('livewire.add-identity');
    }
}
