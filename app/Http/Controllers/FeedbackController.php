<?php

namespace App\Http\Controllers;

use App\Domains\Directory\Models\Entity;
use Illuminate\Http\Request;
use App\Domains\Feedback\Models\Feedback;
use App\Helpers\ImageSaver;
use PDF;

class FeedbackController extends Controller
{
    public function feedbacks()
    {
        return view('feedbacks');
    }

    public function feedback($id)
    {
        $entity = Entity::query()->findOrFail($id);
        return view('feedback', compact('entity'));
    }

    public function sendFeedback($id, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'rating' => 'required|digits_between:0,5'
        ]);

        $feedback = Feedback::create([
            'entity_id' => $id,
            'fullname' => $request->name,
            'phone' => $request->phone,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        if($request->hasfile('photo')){
            foreach($request->file('photo') as $file){
                $path = ImageSaver::save($file);
                $feedback->attachPhoto($path);
            }
         }

        return redirect()->route('thankyou');
    }

    public function showQRCode($id)
    {
        $name = Entity::findOrFail($id)->name;

        $path = route('feedback', $id);
        $pdf = PDF::loadView('pdf', compact('name', 'path'));

        return $pdf->stream();
    }

    public function thankyou()
    {
        return view('thankyou');
    }
}
