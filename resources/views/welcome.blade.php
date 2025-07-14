@extends('layouts.app')
@section('title', 'Home | ')
@section('content')
<div class="bg-light py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold text-primary">Welcome to Form Forge</h1>
        </div>

        <div>
            <h3 class="mb-4">Available Forms</h3>
            <div class="row">
            @foreach ($forms as $form)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $form->label }}</h5>
                            <p class="card-text text-muted">Fill out this form to get started</p>
                            <div class="mt-auto">
                                <a href="{{ route('forms.show', $form) }}" class="btn btn-primary">Fill Form</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')

@endpush