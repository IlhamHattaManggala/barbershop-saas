<?php

namespace App\Livewire\Feedback;

use App\Models\AppFeedback;
use Livewire\Component;

class UserFeedbackForm extends Component
{
    public $rating = 5;

    public $feedback_text = '';

    public $success_message = '';

    public function submitFeedback()
    {
        $user = auth()->user();

        if ($user->hasSubmittedFeedback()) {
            $this->addError('rating', 'Anda sudah pernah memberikan penilaian sebelumnya.');

            return;
        }

        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback_text' => 'nullable|string|max:1000',
        ]);

        AppFeedback::create([
            'user_id' => $user->id,
            'rating' => $this->rating,
            'feedback_text' => $this->feedback_text,
        ]);

        $this->success_message = 'Terima kasih! Penilaian dan masukan Anda berhasil dikirim.';

        // Refresh page so sidebar menu automatically disappears
        $this->redirect(request()->header('Referer') ?? route('dashboard'), navigate: true);
    }

    public function render()
    {
        $hasSubmitted = auth()->user()->hasSubmittedFeedback();
        $userFeedback = auth()->user()->feedback;

        return view('livewire.feedback.user-feedback-form', [
            'hasSubmitted' => $hasSubmitted,
            'userFeedback' => $userFeedback,
        ])->layout('layouts.app');
    }
}
