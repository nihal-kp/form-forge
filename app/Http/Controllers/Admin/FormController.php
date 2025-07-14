<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CustomFormRequest;
use App\Enums\FormStatus;
use App\Enums\FormFieldType;
use App\Jobs\SendFormCreatedNotificationJob;
use Illuminate\Support\Facades\Auth;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, DataTables $dataTables)
    {
        if($request->ajax()){
        
            $query = Form::select('label', 'status', 'created_at', 'id')->orderBy('id','DESC');
     
            return $dataTables->eloquent($query)
            ->editColumn('status', function (Form $form) {
                if ($form->status === FormStatus::INACTIVE) {
                    return '<span class="font-weight-bold text-danger">' . FormStatus::INACTIVE->label() . '</span>';
                }

                return '<span class="font-weight-bold text-success">' . FormStatus::ACTIVE->label() . '</span>';
            })
            ->addColumn('actions', function (Form $form) {
                return
                    '<a href="' . route('admin.forms.show', $form) . '" class="btn btn-sm" title="View"><i class="fa fa-eye"></i></a> ' .
                    '<a href="' . route('admin.forms.edit', $form) . '" class="btn btn-sm" title="Edit"><i class="fa fa-edit"></i></a> ' .
                    '<a data-toggle="modal" href="#delete-form-modal" data-href="' . route('admin.forms.destroy', $form) . '" class="btn btn-sm form-delete" title="Delete"><i class="fa fa-trash"></i></a>';
            })      
           ->rawColumns(['status','actions'])
           ->make(true);
        }
        return view('admin.forms.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $form = new Form();
        return view('admin.forms.form', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomFormRequest $request)
    {
        DB::beginTransaction();

        try {
            $form = Form::create([
                'label' => $request->label,
                'status' => FormStatus::from($request->status),
            ]);
            
            foreach ($request->name as $index => $name) {
                $form->formFields()->create([
                    'name' => $name,
                    'type' => FormFieldType::from($request->type[$index]),
                    'options' => $request->type[$index] === FormFieldType::SELECT->value
                            ? json_encode(array_filter(explode(',', $request->options[$index] ?? '')))
                            : null,
                ]);
            }

            DB::commit();
            
            SendFormCreatedNotificationJob::dispatch($form, Auth::user()->email);

            return redirect()->route('admin.forms.index')->with('success', 'Form created successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Form $form)
    {
        $form->load('formFields');
        return view('admin.forms.show', compact('form'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Form $form)
    {
        $form->load('formFields');
        return view('admin.forms.form', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomFormRequest $request, Form $form)
    {
        DB::beginTransaction();

        try {
            $form->update([
                'label' => $request->label,
                'status' => FormStatus::from($request->status),
            ]);

            $existingFieldIds = $form->formFields->pluck('id')->toArray();
            $submittedFieldIds = array_filter($request->formField_id ?? []);

            // Delete removed fields
            $toDelete = array_diff($existingFieldIds, $submittedFieldIds);
            if ($toDelete) {
                $form->formFields()->whereIn('id', $toDelete)->delete();
            }

            foreach ($request->name as $index => $name) {
                $form->formFields()->updateOrCreate(
                    ['id' => $request->formField_id[$index] ?? null],
                    [
                        'name' => $name,
                        'type' => $request->type[$index],
                        'options' => $request->type[$index] === FormFieldType::SELECT->value
                                ? json_encode(array_filter(explode(',', $request->options[$index] ?? '')))
                                : null,
                    ]
                );
            }

            DB::commit();

            return redirect()->route('admin.forms.index')->with('success', 'Form updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Form $form)
    {
        $form->delete();

        return response()->json(['status'=>'success', 'message'=>'Data deleted successfully!']);
    }
}
