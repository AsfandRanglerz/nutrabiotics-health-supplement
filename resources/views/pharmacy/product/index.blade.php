@extends('pharmacy.layout.app')
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
                                <a class="btn btn-success mb-3" href="{{ route('pharmacy.product.create') }}">Add
                                    Product</a>
                                <table class="table text-center" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Category</th>
                                            <th>Sub Category</th>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th>Description</th>
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
                                                <td>{{ $product->pivot->stock }}</td>
                                                <td>{!! $product->description !!}</td>
                                                {{-- <td>
                                                    <img src="{{ asset($company->image) }}" alt="" height="50"
                                                        width="50" class="image">
                                                </td> --}}
                                                <td>
                                                    <a
                                                        href="{{ route('pharmacy.productPhoto.index', ['id' => $product->pivot->id, 'product_id' => $product->pivot->product_id]) }}">View</a>
                                                </td>

                                                <td
                                                    style="display: flex;align-items: center;justify-content: center;column-gap: 8px">
                                                    @if ($product->expiry_date == null)
                                                        <button onClick="viewDetail('{{ $product->id }}')" type="button"
                                                            class="btn btn-primary" data-toggle="modal"
                                                            data-target="#exampleModal">Discount</button>
                                                    @else
                                                        <div>
                                                            <i class=" fas fa-star"
                                                                style="position: relative; margin:-5px 52px 0 -4px; position: absolute; color:#FFD700; font-size:15px"></i>
                                                            <button onClick="viewDetail('{{ $product->id }}')"
                                                                type="button" class="btn btn-primary" data-toggle="modal"
                                                                data-target="#exampleModal">Discount</button>
                                                        </div>
                                                    @endif
                                                    <a class="btn btn-info"
                                                        href="{{ route('pharmacy.product.edit', $product->pivot->id) }}">Edit</a>
                                                    <form method="post"
                                                        action="{{ route('pharmacy.product.destroy', $product->pivot->id) }}">
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
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Discount</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        @csrf
                        <div class="form-group">
                            <label for="phone">Product Price</label>
                            <input type="number" name="price" id="response_price" class="form-control" readonly>
                            <label for="discount-percentage" class="col-form-label">Percentage</label>
                            <input type="number" name="d_per" id="discount_per" class="form-control" readonly>
                            <label for="price">Discount Price</label>
                            <input type="number" name="d_price" id="discount" class="form-control" readonly>
                            <label for="price">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" readonly>
                            <label for="price">Expiry Date</label>
                            <input type="date" name="expiry_date" id="expiry_date" class="form-control" readonly>
                            <input type="hidden" name="id" id="id">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                    </form>
                </div>

            </div>
        </div>
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
    <script>
        function viewDetail(id) {
            $.ajax({
                url: '{{ URL::to('/pharmacy/view-discount') }}',
                type: 'GET',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id': id
                },
                success: function(response) {
                    $('#response_price').val(response.data.price);
                    $('#id').val(response.data.id);
                    $('#discount_per').val(response.data.d_per);
                    $('#discount').val(response.data.d_price);
                    $('#start_date').val(response.data.start_date);
                    $('#expiry_date').val(response.data.expiry_date);
                }
            });
        }
    </script>
@endsection
