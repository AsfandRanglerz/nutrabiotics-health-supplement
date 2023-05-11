@extends('pharmacy.layout.app')
@section('title', 'Profile')
@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="row mt-sm-4">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="padding-20">
                                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#about" role="tab"
                                            aria-selected="false">About</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" id="profile-tab2" data-toggle="tab" href="#settings"
                                            role="tab" aria-selected="true">Setting</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#account"
                                            role="tab" aria-selected="false">Account Detail</a>
                                    </li>
                                </ul>
                                <div class="tab-content tab-bordered" id="myTab3Content">
                                    <div class="tab-pane fade" id="about" role="tabpanel" aria-labelledby="home-tab2">

                                        <h5>Profile Detail</h5>
                                        <div class="row">
                                            <div class="col-md-3 col-6 b-r">
                                                <strong>Full Name</strong>
                                                <br>
                                                <p class="text-muted">{{ $data->name }}</p>
                                            </div>
                                            <div class="col-md-3 col-6 b-r">
                                                <strong>Mobile</strong>
                                                <br>
                                                <p class="text-muted">{{ $data->phone }}</p>
                                            </div>
                                            <div class="col-md-3 col-6 b-r">
                                                <strong>Email</strong>
                                                <br>
                                                <p class="text-muted">{{ $data->email }}</p>
                                            </div>
                                            <div class="col-md-3 col-6 b-r">
                                                <strong>Address</strong>
                                                <br>
                                                <p class="text-muted">{{ $data->address }}</p>
                                            </div>
                                            <div class="col-md-3 col-6 b-r">
                                                <strong>Country</strong>
                                                <br>
                                                <p class="text-muted">{{ $data->country }}</p>
                                            </div>
                                            {{-- <div class="col-md-3 col-6 b-r">
                                                <strong>State</strong>
                                                <br>
                                                <p class="text-muted">{{ $data->state }}</p>
                                            </div> --}}
                                            <div class="col-md-3 col-6 b-r">
                                                <strong>City</strong>
                                                <br>
                                                <p class="text-muted">{{ $data->city }}</p>
                                            </div>
                                        </div>
                                        <h5>Account Detail</h5>
                                        <div class="row">
                                            @foreach (['name' => 'Bank Name', 'accountHolder' => 'Account Holder Name', 'accountNumber' => 'Account Number'] as $property => $label)
                                                <div class="col-md-3 col-6 b-r">
                                                    <strong>{{ $label }}</strong>
                                                    <br>
                                                    <p class="text-muted">{{ $account->{$property} ?? '' }}</p>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                    <div class="tab-pane fade active show" id="settings" role="tabpanel"
                                        aria-labelledby="profile-tab2">
                                        <form method="post" action="{{ route('pharmacy.updateProfile', $data->id) }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="card-header">
                                                <h4>Edit Profile</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>Name</label>
                                                        <input type="text" name="name" value="{{ $data->name }}"
                                                            class="form-control">
                                                        @error('name')
                                                            <div class="text-danger">
                                                                Please fill in the Name
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>Email</label>
                                                        <input type="email" name="email" value="{{ $data->email }}"
                                                            class="form-control" readonly>
                                                        @error('email')
                                                            <div class="text-danger">
                                                                Please fill in the email
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>Profile Image</label>
                                                        <div class="custom-file">
                                                            <input type="file" name="image"
                                                                value="{{ $data->image }}" class="custom-file-input"
                                                                id="customFile">
                                                            <label class="custom-file-label" for="customFile">Choose
                                                                file</label>
                                                        </div>

                                                        <div class="invalid-feedback">

                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>Phone</label>
                                                        <input type="tel" name="phone" value="{{ $data->phone }}"
                                                            class="form-control" value="">
                                                        @error('phone')
                                                            <div class="text-danger">
                                                                Please fill in the email
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row">

                                                    <div class="form-group col-md-6 col-12">
                                                        <label>Address</label>
                                                        <input type="text" name="address" value="{{ $data->address }}"
                                                            class="form-control" value="">
                                                        @error('phone')
                                                            <div class="text-danger">
                                                                Please fill in the email
                                                            </div>
                                                        @enderror
                                                    </div>

                                                    {{-- <div class="form-group col-md-6 col-12">
                                                        <label>Country</label>
                                                        <select name="country" id="country-dropdown"
                                                            class="form-control">
                                                            <option value=""></option>
                                                            @foreach ($data1['country'] as $country)
                                                                <option value="{{ $country->name }}"
                                                                    {{ $data->country == $country->name ? 'selected' : '' }}>
                                                                    {{ $country->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('country')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div> --}}
                                                </div>
                                            </div>
                                            <div class="row mx-0 px-4">
                                                {{-- <div class="col-sm-6 pl-sm-0 pr-sm-2">
                                                    <div class="form-group mb-3">
                                                        <label for="state">State</label>
                                                        <select class="form-control" name="state" id="state-dropdown"
                                                            value="{{ $data->state }}">
                                                            <option value=""></option>
                                                            @foreach ($data1['state'] as $state)
                                                                <option value="{{ $state->name }}"
                                                                    {{ $data->state == $state->name ? 'selected' : '' }}>
                                                                    {{ $state->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('state')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div> --}}
                                                <div class="col-sm-6 pl-sm-0 pr-sm-2">
                                                    {{-- <div class="form-group mb-3">
                                                        <label for="city">City</label>
                                                        <input type="text" name="city" value="{{ $data->city }}"
                                                            class="form-control">

                                                        @error('city')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div> --}}

                                                </div>
                                            </div>
                                            <div class="card-footer text-center">
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                        <form method="post"
                                            action="{{ route('pharmacy.change-password', [$data->id, $data->slug]) }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>Old Password</label>
                                                        <input type="password" placeholder="Old Password"
                                                            name="current_password" id="current_password" value=""
                                                            class="form-control">
                                                        @error('current_password')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>New Password</label>
                                                        <input type="password" name="new_password"
                                                            placeholder="New Password" id="new_password" value=""
                                                            class="form-control">
                                                        @error('new_password')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-4 justify-content-center">
                                                <button type="submit" class="btn btn-primary">Update Password</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="account" role="tabpanel"
                                        aria-labelledby="profile-tab3">
                                        <form method="POST" enctype="multipart/form-data"
                                            action="{{ isset($account) ? route('pharmacy.bank_detail.update', $account->id) : route('pharmacy.bank_detail.store') }}">
                                            @csrf
                                            @if (isset($account))
                                                @method('PATCH')
                                            @endif
                                            <div class="card-header">
                                                <h4>{{ isset($account) ? 'Edit' : 'Add' }} Account Detail</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>Bank Name</label>
                                                        <input type="text" name="bankName"
                                                            value="{{ isset($account) ? $account->name : old('bankName') }}"
                                                            class="form-control">
                                                        @error('bankName')
                                                            <div class="text-danger">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>Account Holder Name</label>
                                                        <input type="text" name="accountHolder"
                                                            value="{{ isset($account) ? $account->accountHolder : old('accountHolder') }}"
                                                            class="form-control">
                                                        @error('accountHolder')
                                                            <div class="text-danger">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>Account Number</label>
                                                        <input type="number" name="accountNumber"
                                                            value="{{ isset($account) ? $account->accountNumber : old('accountNumber') }}"
                                                            class="form-control">
                                                        @error('accountNumber')
                                                            <div class="text-danger">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer text-center">
                                                @if ($account)
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                @else
                                                    <button type="submit" class="btn btn-primary">Create Account</button>
                                                @endif
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </section>
    </div>

@endsection
@section('js')
    <script>
        @if (\Illuminate\Support\Facades\Session::has('success'))
            toastr.success('{{ \Illuminate\Support\Facades\Session::get('success') }}');
        @endif

        @if (\Illuminate\Support\Facades\Session::has('error'))
            toastr.error('{{ \Illuminate\Support\Facades\Session::get('error') }}');
        @endif
    </script>
     {{-- <script>
        $(document).ready(function() {
            $('#country-dropdown').on('change', function() {
                var country_id = this.value;
                $("#state-dropdown").html('');
                $.ajax({
                    url: "{{ url('pharmacy/get-states-by-country') }}",
                    type: "POST",
                    data: {
                        country_id: country_id,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $('#state-dropdown').html('<option value="">Select State</option>');
                        $.each(result.states, function(key, value) {
                            $("#state-dropdown").append('<option value="' + value +
                                '">' + value + '</option>');
                        });
                        $('#city-dropdown').html(
                        '<option value="">Select State First</option>');
                    }
                });
            });
            $('#state-dropdown').on('change', function() {
                var state_id = this.value;
                $("#city-dropdown").html('');
                $.ajax({
                    url: "{{ url('pharmacy/get-cities-by-state') }}",
                    type: "POST",
                    data: {
                        state_id: state_id,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $('#city-dropdown').html('<option value="">Select City</option>');
                        $.each(result.cities, function(key, value) {
                            $("#city-dropdown").append('<option value="' + value +
                                '">' + value + '</option>');
                        });
                    }
                });
            });
        });
    </script> --}}
@endsection
