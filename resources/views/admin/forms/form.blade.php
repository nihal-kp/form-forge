@extends("admin.layouts.app")
@section('title', $form->id ? 'Edit' : 'Add' . ' Form')
@section("content")

    @php
        use App\Enums\FormStatus;
        use App\Enums\FormFieldType;
    @endphp

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">{{ $form->id ? 'Edit' : 'Add' }} Form</h1>
        
        <form method="POST" action="{{ $form->id ? route('admin.forms.update',$form) : route('admin.forms.store') }}" enctype="multipart/form-data">
        @csrf
        {{ $form->id ? method_field('PUT') : '' }}
        <div class="card shadow mb-4">
            <div class="card-body">
                
                <h3 class="font-size-lg text-dark font-weight-bold mb-3">Form</h3>
                @php
                    $nameError = $errors->first('name');

                    if (!$nameError) {
                        $nameFieldKey = collect($errors->keys())->first(fn($key) => str_starts_with($key, 'name.'));
                        $nameError = $errors->first($nameFieldKey);
                    }
                @endphp

                @if ($nameError)
                    <div class="alert alert-danger">
                        <ul>
                            <li>{{ $nameError }}</li>
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="form-group col-6">
                        <label>Label* :</label>
                        <input type="text" class="form-control" name="label" value="{{old('label', ($form->label ? $form->label : '' ))}}">
                        @error("label")
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group col-6">
                        <label class="" style="">Status* :</label>
                        <select class="form-control" name="status">
                            @foreach(FormStatus::cases() as $status)
                                <option value="{{ $status->value }}" {{ old('status', $form->status ? $form->status->value : '') == $status->value ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error("status")
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div id="formField-container">
                                
                    @if($form->formFields->count() > 0)
                    @foreach($form->formFields as $formField)
                    @php $loopIndex = $loop->index; @endphp
                    <div class="row">
                        <div class="col-12">
                            <hr style="height: 1px; background-color: #000;">
                        </div>
                        <input type="hidden" name="formField_id[{{ $loopIndex }}]" value="{{ $formField->id }}" >
                        
                        <div class="form-group col-4">
                            <label>Name* :</label>
                            <input type="text" class="form-control" name="name[{{ $loopIndex }}]" value="{{ old('name.' . $loopIndex, $formField->name) }}">
                            @error("name.$loopIndex")
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-4"> 
                            <label>Type :</label>
                            <select class="form-control" name="type[{{ $loopIndex }}]">
                                @foreach(FormFieldType::cases() as $type)
                                    <option value="{{ $type->value }}" {{ old("type.$loopIndex", $formField->type?->value) == $type->value ? 'selected' : '' }}>{{ $type->label() }}</option>
                                @endforeach
                            </select>
                            @error("type.$loopIndex")
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="form-group col-3">
                            <div class="field-options" style="{{ old("type.$loopIndex", $formField->type->value ?? '') === FormFieldType::SELECT->value ? '' : 'display:none;' }}">
                            <label>Options (comma-separated)* :</label>
                            <input type="text" class="form-control" name="options[{{ $loopIndex }}]"
                                value="{{ old("options.$loopIndex", is_array(json_decode($formField->options, true)) ? implode(',', json_decode($formField->options, true)) : ($formField->options ?? '')) }}">
                            @error("options.$loopIndex")
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                            </div>
                        </div>

                        <div class="col-1">
                            <button type="button" class="btn btn-danger btn-icon float-right remove-formField mt-5"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
                
                <!-- Hidden template for cloning -->
                <div id="formField-template" class="d-none">
                    <div class="row">
                        <div class="col-12">
                            <hr style="height: 1px; background-color: #000;">
                        </div>
                        <input type="hidden" data-name="formField_id" value="">
                        
                        <div class="form-group col-4">
                            <label>Name :</label>
                            <input type="text" class="form-control" data-name="name">
                        </div>

                        <div class="form-group col-4"> 
                            <label>Type :</label>
                            <select class="form-control" data-name="type">
                                @foreach(FormFieldType::cases() as $type)
                                    <option value="{{ $type->value }}">{{ $type->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group col-3">
                            <div class="field-options" style="display: none;">
                            <label>Options (comma-separated)* :</label>
                            <input type="text" class="form-control" data-name="options">
                            </div>
                        </div>

                        <div class="col-1">
                            <button type="button" class="btn btn-danger btn-icon float-right remove-formField mt-5"><i class="fa fa-times"></i></button>
                        </div>
                        
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-1">
                        <button type="button" class="btn btn-success btn-icon float-left mt-5" id="add-formField"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
            </div>
            
            <div class="card-footer">
                <div class="row">
                    <div class="form-group col-6">
                    <button type="submit" class="btn btn-success mr-3">Save</button>
                    <a class="btn btn-secondary ml-3" href="{{ route('admin.forms.index') }}">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
        </form>
        
        
    </div>
    <!-- /.container-fluid -->
@endsection

@push('style')

@endpush

@push('script')
<script>
$(document).ready(function() {
    
    let formFieldIndex = $('#formField-container .row').length;
     
    $('#add-formField').on('click', function () {
        let template = $('#formField-template').html();
        let newRow = $(template);
        newRow.find('[data-name="name"]').attr('name', `name[${formFieldIndex}]`);
        newRow.find('[data-name="type"]').attr('name', `type[${formFieldIndex}]`);
        newRow.find('[data-name="formField_id"]').attr('name', `formField_id[${formFieldIndex}]`);
        newRow.find('[data-name="options"]').attr('name', `options[${formFieldIndex}]`);
        $('#formField-container').append(newRow);
        
        formFieldIndex++;
    });
    
    $('#formField-container').on('click', '.remove-formField', function () {
        $(this).closest('.row').remove();
        
        formFieldIndex = $('#formField-container .row').length;
    });

    $('#formField-container').on('change', 'select[name^="type"]', function () {
        let selected = $(this).val();
        let container = $(this).closest('.row');
        if (selected === 'select') {
            container.find('.field-options').show();
        } else {
            container.find('.field-options').hide();
        }
    });

});
</script>
@endpush