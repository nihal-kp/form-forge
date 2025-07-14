@extends('admin.layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Update Profile</h1>
    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
        @csrf
        {{ method_field('PUT') }}
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-6">
                        <label>Name*</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', auth()->user()->name) }}">
                        @error('name') <p class="text-danger">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group col-6">
                        <label>Email*</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email', auth()->user()->email) }}">
                        @error('email') <p class="text-danger">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group col-6">
                        <label>Phone*</label>
                        <input type="text" class="form-control" name="phone" value="{{ old('phone', auth()->user()->phone) }}">
                        @error('phone') <p class="text-danger">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group col-6">
                        <label>Password (leave blank to keep current)</label>
                        <input type="password" class="form-control" name="password">
                        @error('password') <p class="text-danger">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group col-6">
                        <label>Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation">
                        @error('password_confirmation') <p class="text-danger">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group col-6">
                        <label>Image (500 x 500 px)</label>
                        <input type="file" name="image" class="form-control-file">
                        @if(auth()->user()->image)
                            <img src="{{ asset('uploads/admins/' . auth()->user()->image) }}" width="100" class="mt-2" />
                        @endif
                        @error('image') <p class="text-danger">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-success">Save</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('style')
<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush

@push('script')
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif

    @if(session('warning'))
        toastr.warning("{{ session('warning') }}");
    @endif

    @if(session('info'))
        toastr.info("{{ session('info') }}");
    @endif
</script>
@endpush