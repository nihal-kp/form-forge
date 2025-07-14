@extends('admin.layouts.app')

@section('title', 'View Form: ' . $form->label)

@section('content')

@php
    use App\Enums\FormStatus;
    use App\Enums\FormFieldType;
@endphp
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">View Form</h1>

    <div class="card shadow mb-4">
        <div class="card-body">

            <h3>{{ $form->label }}</h3>
            <p><strong>Status:</strong> 
                @if($form->status === FormStatus::ACTIVE)
                    <span class="badge badge-success">{{ $form->status->label() }}</span>
                @else
                    <span class="badge badge-danger">{{ $form->status->label() }}</span>
                @endif
            </p>

            <p><strong>Created At:</strong> {{ $form->created_at->format('d M Y, H:i') }}</p>

            <hr>

            <h4>Form Fields</h4>

            @if($form->formFields->isEmpty())
                <p>No form fields added.</p>
            @else
                <ul class="list-group">
                    @foreach($form->formFields as $field)
                        <li class="list-group-item">
                            <strong>Name:</strong> {{ $field->name }}<br>
                            <strong>Type:</strong> {{ $field->type->label() }}<br>
                            @if($field->type === FormFieldType::SELECT && $field->options)
                                @php
                                    $options = json_decode($field->options, true);
                                @endphp
                                <strong>Options:</strong> {{ implode(', ', $options) }}
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif

            <div class="mt-4">
                <a href="{{ route('admin.forms.index') }}" class="btn btn-secondary">Back to List</a>
                <a href="{{ route('admin.forms.edit', $form) }}" class="btn btn-primary">Edit Form</a>
            </div>

        </div>
    </div>

</div>

@endsection