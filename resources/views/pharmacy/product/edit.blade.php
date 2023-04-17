@extends('pharmacy.layout.app')
@section('title', 'Dashboard')
@section('content')

    <body>
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    {{-- <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a> --}}
                    <form id="add_student" action="{{ route('pharmacy.product.update', $data->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <h4 class="text-center my-4">Edit Product</h4>
                                    <div class="row mx-0 px-4">
                                        {{-- <input type="hidden" value="{{$data->pharmacy_id}}" name="pharmacy_id"> --}}
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Category</label>
                                                <select name="category_id" id="category-dropdown" class="form-control"
                                                    value="{{ old('category_id') }}">
                                                    <option value="" disabled>Select Category</option>
                                                    @foreach ($category as $country)
                                                        <option value="{{ $country->id }}"
                                                            {{ $data->product->category_id == $country->id ? 'selected' : '' }}
                                                            disabled="disabled">
                                                            {{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('category_id')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label for="product_name">Sub Category</label>
                                                <select name="subcategory_id" id="subcategory-dropdown"
                                                    class="form-control">
                                                    <option value="" disabled>Select Sub Category</option>
                                                    @foreach ($subcategory as $country)
                                                        <option value="{{ $country->id }}"
                                                            {{ $data->product->subcategory_id == $country->id ? 'selected' : '' }}
                                                            disabled="disabled">
                                                            {{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mx-0 px-4">
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label for="product_name">Product Name</label>
                                                <select name="product_id" id="product-dropdown" class="form-control">
                                                    <option value="" disabled>Select Product</option>
                                                    @foreach ($product as $country)
                                                        <option value="{{ $country->id }}"
                                                            {{ $data->product_id == $country->id ? 'selected' : '' }}
                                                            disabled>
                                                            {{ $country->product_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if ($data->product_id)
                                                    <input type="hidden" name="product_id"
                                                        value="{{ $data->product_id }}">
                                                @endif

                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Stock</label>
                                                <input type="number" name="stock" id="stock"
                                                    value="{{ $data->stock }}" class="form-control" placeholder="Stock">
                                                @error('stock')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="row mx-0 px-4">
                                        <div class="col-sm-6 pl-sm-0 pr-sm-2">
                                            <div class="form-group mb-3">
                                                <label>Description</label>
                                                <textarea name="description" class="form-control">{{ $data->product->description ?? '' }}</textarea>
                                                @error('description')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div> --}}


                                    <div class="card-footer text-center row">
                                        <div class="col">
                                            <button type="submit" class="btn btn-success mr-1 btn-bg"
                                                id="submit">Update</button>
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
    <script>
        $(document).ready(function() {
            $('#category-dropdown').on('change', function() {
                var category_id = this.value;
                $("#subcategory-dropdown").html('');
                $.ajax({
                    url: "{{ url('pharmacy/get-subcategory-by-categories') }}",
                    type: "POST",
                    data: {
                        category_id: category_id,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $('#subcategory-dropdown').html(
                            '<option value="">Select Sub Category</option>');
                        $.each(result.SubCategory, function(key, value) {
                            $("#subcategory-dropdown").append('<option value="' + value
                                .id +
                                '">' + value.name + '</option>');
                        });
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#subcategory-dropdown').on('change', function() {
                var subcategory_id = this.value;
                $("#product-dropdown").html('');
                $.ajax({
                    url: "{{ url('pharmacy/get-product-by-subcategory') }}",
                    type: "POST",
                    data: {
                        subcategory_id: subcategory_id,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $('#product-dropdown').html('<option value="">Select Product</option>');
                        $.each(result.product, function(key, value) {
                            $("#product-dropdown").append('<option value="' + value.id +
                                '">' + value.product_name + '</option>');
                        });
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#readonly option:contains("HTML")').attr("disabled", "disabled");
        });
    </script>
@endsection
