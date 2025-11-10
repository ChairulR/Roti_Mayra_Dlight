<?php
 
namespace App\Livewire;
 
use Livewire\Attributes\Url;
use Livewire\Component;
use App\Models\Bread;
 
class Livesearch extends Component
{
    #[Url(as: 'q')]
    public $search = '';
 
    public function render()
    {
        $breads = $this->search 
            ? Bread::where('name', 'like', '%' . $this->search . '%')
                   ->orWhere('description', 'like', '%' . $this->search . '%')
                   ->orderBy('created_at', 'desc')
                   ->get()
            : Bread::orderBy('created_at', 'desc')->get();

        return view('livewire.livesearch', [
            'breads' => $breads,
        ]);
    }
}