<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Domains\Feedback\Models\Feedback;

class FeedbackViewPopup extends Component
{
    public $isFeedbackViewOpen = false;
    public $feedback = null;

    protected $listeners = ['openPopupFeedback'];

    public function render()
    {
        return view('livewire.feedback-view-popup');
    }

    public function openPopupFeedback($id)
    {
        $this->feedback = Feedback::findOrFail($id);
        $this->isFeedbackViewOpen = true;
    }
}
