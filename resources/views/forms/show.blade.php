@extends('layouts.app')

@section('content')

@php
    use App\Enums\FormStatus;
    use App\Enums\FormFieldType;
    use Illuminate\Support\Str;
@endphp

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="text-center mb-4">{{ $form->label }}</h3>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('forms.submit', $form) }}" method="POST" id="public-form">
                        @csrf

                        <div class="row">
                            @foreach($form->formFields as $field)
                                @php
                                    $fieldName = Str::slug($field->name, '_');
                                    $oldValue = old($fieldName);
                                @endphp

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ ucfirst($field->name) }}</label>

                                    @if($field->type->value === FormFieldType::TEXT->value)
                                        <input type="text" name="{{ $fieldName }}" class="form-control" value="{{ $oldValue }}">
                                    @elseif($field->type->value === FormFieldType::NUMBER->value)
                                        <input type="number" name="{{ $fieldName }}" class="form-control" value="{{ $oldValue }}">
                                    @elseif($field->type->value === FormFieldType::TEXTAREA->value)
                                        <textarea name="{{ $fieldName }}" class="form-control" rows="3">{{ $oldValue }}</textarea>
                                    @elseif($field->type->value === FormFieldType::SELECT->value)
                                        <select name="{{ $fieldName }}" class="form-control">
                                            <option value="">Select</option>
                                            @foreach(json_decode($field->options ?? '[]') as $option)
                                                <option value="{{ $option }}" {{ $oldValue === $option ? 'selected' : '' }}>{{ $option }}</option>
                                            @endforeach
                                        </select>
                                    @endif

                                    <div class="text-danger small error_msg error-{{ $fieldName }}" style="display: none;"></div>

                                </div>
                            @endforeach
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary px-5">Submit</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$("#public-form").on("submit", function(e){
    e.preventDefault();
    $this = $(this);
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, 
        type: 'POST',
        dataType: 'JSON',
        data: $this.serialize(),
        url : $this.attr('action'),
        beforeSend: function() {
            $this.find('button').attr('disabled', true);
            $this.find('button').html('<i class="fa fa-spinner fa-spin"></i>');
        },

        success: function(response){
            $('.error_msg').hide();
            Swal.fire({
                title: response.message,
                icon: 'success',
                showConfirmButton: false,
                timer: 2000,
            }).then(() => {
                window.location.href = '/';
            });
            $this.trigger('reset');
        },
        
        complete: function() {
            $this.find('button').attr('disabled', false);
            $this.find('button').html('Submit');
        },

        error: function(response) {
            $('.error_msg').hide();
            $.each(response.responseJSON.errors, function(key,value) {
                $this.find('.error-'+key).html(value).show();
                if(key=='failed') {
                    Swal.fire({
                        title: value,
                        icon: 'error',
                        showConfirmButton: false,
                        timer: 3000,
                    });
                }
            });
        }
    });
});
</script>
@endpush