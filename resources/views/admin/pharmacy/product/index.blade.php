@extends('admin.layout.app')
@section('title', 'index')
@section('content')
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Products</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                <a class="btn btn-success mb-3" href="{{route('pharmacyProduct.create',$pharmacy->id)}}">Add Product</a>
                                <table class="table text-center" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Category</th>
                                            <th>Sub Category</th>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Description</th>
                                            <th>Stock</th>
                                            <th>Photos</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $product)
                                            <tr>

                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $product->subcategory->category->name }}</td>
                                                <td>{{ $product->subcategory->name }}</td>
                                                <td>{{ $product->product_name }}</td>
                                                <td>{{ $product->price }}</td>
                                                <td>{!!$product->description !!}</td>
                                                <td>{{ $product->pivot->stock }}</td>
                                                {{-- <td>
                                                    <img src="{{ asset($company->image) }}" alt="" height="50"
                                                        width="50" class="image">
                                                </td> --}}

                                                <td>
                                                    <a href="{{ route('pharmacy_productPhoto.index', ['id' => $product->pivot->id,'product_id' => $product->pivot->product_id]) }}">View</a>
                                                </td>
                                                <td
                                                    style="display: flex;align-items: center;justify-content: center;column-gap: 8px">
                                                    <a class="btn btn-info"
                                                        href="{{ route('pharmacyProduct.edit',$product->pivot->id) }}">Edit</a>
                                                    <form method="get"
                                                        action="{{ route('pharmacyProduct.destroy', $product->pivot->id) }}">
                                                        @csrf
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <button type="submit" class="btn btn-danger btn-flat show_confirm"
                                                            data-toggle="tooltip">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@section('js')
    @if (\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
        </script>
    @endif
    <script>
        $(document).ready(function() {
            $('#table_id_events').DataTable()

        })
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script type="text/javascript">
        $('.show_confirm').click(function(event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            swal({
                    title: `Are you sure you want to delete this record?`,
                    text: "If you delete this, it will be gone forever.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
        });
    </script>
@endsection
