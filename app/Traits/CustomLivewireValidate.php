<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;

trait CustomLivewireValidate
{

    public function validate($rules = null, $messages = [], $attributes = [])
    {
        [$rules, $messages, $attributes] = $this->providedOrGlobalRulesMessagesAndAttributes($rules, $messages, $attributes);

        $data = $this->prepareForValidation(
            $this->getDataForValidation($rules)
        );

        $validator = Validator::make($data, $rules, $messages, $attributes);

        $this->shortenModelAttributes($data, $rules, $validator);

        foreach ($validator->errors()->getMessages() as $error){
            $this->alert('error', __("Error"), $error[0]);
        }

        $this->resetErrorBag();

        return $validator;
    }

    public function alert($type, $title, $message): bool
    {
        $this->dispatchBrowserEvent('formAlert', [
            'title' => $title,
            'type' => $type,
            'message' => $message
        ]);
        return 1;
    }

    public function addError($code, $message): bool
    {
        $this->dispatchBrowserEvent('formAlert', [
            'title' => '',
            'type' => 'error',
            'message' => $message
        ]);
        return 1;
    }
}
