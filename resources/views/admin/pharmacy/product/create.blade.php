@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')

    <body>
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    {{-- <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a> --}}
                    <form id="add_student" action="{{ route('pharmacyProduct.store',$data['pharmacy_id']) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <h4 class="text-center my-4">Add Product</h4>
                                    <div class="row mx-0 px-4">
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Category</label>
                                                <select name="category_id" id="category-dropdown" class="form-control"
                                                    value="{{ old('category_id') }}">
                                                    <option value="">Select Category</option>
                                                    @foreach ($data['category'] as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('category_id')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6 pl-sm-0 pr-sm-2">
                                            <div class="form-group mb-3">
                                                <label for="subcategory_id">Sub Category</label>
                                                <select class="form-control" name="subcategory_id" id="subcategory-dropdown"
                                                    value="{{ old('subcategory_id') }}">
                                                </select>
                                                @error('subcategory_id')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mx-0 px-4">
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label for="product_name">Product Name</label>
                                                <select name="product_id" id="product-dropdown" class="form-control"
                                                    value="{{ old('product_id') }}">
                                                </select>
                                                @error('product_id')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Stock</label>
                                                <input type="number" name="stock" id="stock"
                                                    value="{{ old('stock') }}" class="form-control" placeholder="Stock">
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
                                                <textarea name="description" class="form-control"></textarea>
                                                @error('description')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div> --}}


                                    <div class="card-footer text-center row">
                                        <div class="col">
                                            <button type="submit" class="btn btn-success mr-1 btn-bg"
                                                id="submit">Add</button>
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
                    url: "{{ url('admin/get-subcategory-by-categories') }}",
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
                url: "{{ url('admin/get-product-by-subcategory') }}",
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
@endsection
