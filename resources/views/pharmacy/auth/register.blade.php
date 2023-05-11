@extends('pharmacy.auth.layout.app')
@section('title', 'Register')
@section('style')
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endsection
@section('content')
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Register</h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('pharmacy.register') }}" class="needs-validation"
                                novalidate="">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="name">Pharmacy Name</label>
                                        <input id="name" type="text" class="form-control" name="pharmacy_name"
                                            value="{{ old('pharmacy_name') }}" tabindex="1" placeholder="Name" autofocus>
                                        @error('pharmacy_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="email">Email</label>
                                        <input id="email" type="email" class="form-control" name="email"
                                            value="{{ old('email') }}" tabindex="1" placeholder="Email" autofocus>
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="email">Country</label>
                                        <select name="country" id="country-dropdown" class="form-control"
                                            value="{{ old('country') }}">
                                            <option value="" disabled selected>Select Country</option>
                                            @foreach ($country as $data)
                                                <option value="{{ $data->name }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('country')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="City">City</label>
                                        <input  type="text" class="form-control" name="city"
                                            value="{{ old('city') }}" tabindex="1" placeholder="City" autofocus>
                                        @error('city')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input id="phone" type="number" class="form-control" name="phone" tabindex="1"
                                        value="{{ old('phone') }}" placeholder="Phone Number" autofocus>
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="password" class="control-label">Password</label>
                                        <input id="password" type="password" class="form-control" name="password"
                                            placeholder="Password" tabindex="2">
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="password" class="control-label">Password Confirmation</label>
                                        <input id="password" type="password" class="form-control" name="confirm_password"
                                            placeholder="Password Confirmation" tabindex="2">
                                        @error('confirm_password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" tabindex="3" id="remember-me"
                                            name="remember">
                                        <div class="d-block">
                                            {{-- <label class="custom-control-label" for="remember-me">Remember Me</label> --}}
                                            <div class="float-right">
                                                <a href={{ route('forgot_password') }} class="text-small">
                                                    Forgot Password?
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                        Register
                                    </button>
                                </div>
                            </form>
                            {{-- <div class="text-center mt-4 mb-3">
                                <div class="text-job text-muted">Login With Social</div>
                            </div>
                            <div class="row sm-gutters">
                                <div class="col-6">
                                    <a class="btn btn-block btn-social btn-facebook">
                                        <span class="fab fa-facebook"></span> Facebook
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a class="btn btn-block btn-social btn-twitter">
                                        <span class="fab fa-twitter"></span> Twitter
                                    </a>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    {{-- <div class="mt-5 text-muted text-center">
                        Don't have an account? <a href="{{ route('admin.register') }}">Create One</a>
                    </div> --}}
                </div>
            </div>
        </div>
    </section>
@endsection
