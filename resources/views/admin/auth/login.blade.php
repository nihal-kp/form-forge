@extends('admin.auth.layouts.app')

@section('content')

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9 mx-auto">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-4 d-none d-lg-block bg-login-image"><img class="" src="{{ asset('images/learning.jpg') }}" style="height: 200px;width: 245px;margin-left: 30px;"></div>
                            <div class="col-lg-8">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Admin!</h1>
                                    </div>
                                    <form class="user" action="{{ route('admin.login') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <input type="email" name="email" class="form-control form-control-user @error('email') is-invalid @enderror"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Email Address" value="{{old('email')}}">
                                        @error("email")
                						<p style="color:red">{{ $message }}</p>
                					    @enderror
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" class="form-control form-control-user @error('password') is-invalid @enderror"
                                                id="exampleInputPassword" placeholder="Password">
                                        @error("password")
                						<p style="color:red">{{ $message }}</p>
                					    @enderror
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                    
                                        <!--<a href="index.html" class="btn btn-facebook btn-user btn-block">-->
                                        <!--    <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook-->
                                        <!--</a>-->
                                    </form>
                                    
                                    <!--<div class="text-center">-->
                                    <!--    <a class="small" href="forgot-password.html">Forgot Password?</a>-->
                                    <!--</div>-->
                                    
                                    <div class="text-center">
                                       <a class="small" href="{{ route('admin.register') }}">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

@endsection