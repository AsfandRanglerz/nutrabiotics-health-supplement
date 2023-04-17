@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')

    <body>
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    {{-- <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a> --}}
                    <form id="add_student" action="{{route('notification.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <h4 class="text-center my-4">Notification</h4>
                                    <div class="row mx-0 px-4">
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Title</label>
                                                <input type="text" placeholder="Title" name="title" id="title"
                                                    value="{{ old('title') }}" class="form-control">
                                                @error('title')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-2">
                                            <div class="form-group mb-3">
                                                <label>Type</label>
                                                <select name="type" id="type" class="form-control"
                                                    value="{{ old('type') }}">
                                                    <option value="" disabled selected>Select Option</option>
                                                    <option value="user">User</option>
                                                    <option value="pharmacy">Pharmacy</option>

                                                </select>
                                                @error('type')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mx-0 px-4">

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label for="body">Body</label>
                                                <textarea placeholder="Enter body text here" name="body" id="body" class="form-control">{{ old('body') }}</textarea>
                                                @error('body')
                                                  <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                              </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-center row">
                                        <div class="col">
                                            <button type="submit" class="btn btn-success mr-1 btn-bg"
                                                id="submit">Send Notification</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </body>
@endsection

@section('js')
    @if (\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
        </script>
    @endif
@endsection
