<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FormController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Form $form)
    {
        $form->load('formFields');

        return view('forms.show', compact('form'));
    }

    public function submit(Request $request, Form $form)
    {
        $form->load('formFields');

        $rules = [];

        foreach ($form->formFields as $field) {
            $fieldName = Str::slug($field->name, '_');
            $rules[$fieldName] = 'required';
        }

        $validated = $request->validate($rules);

        FormSubmission::create([
            'form_id' => $form->id,
            'data' => json_encode($validated),
        ]);

        return response()->json(['success' => true, 'message' => 'Form submitted successfully']);
    }

}
