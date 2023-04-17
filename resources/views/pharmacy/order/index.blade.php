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
                                    <h4>Orders</h4>
                                </div>
                            </div>
                            {{-- @dd($filteredOrders); --}}
                            <div class="card-body table-striped table-bordered table-responsive">
                                {{-- <a class="btn btn-success mb-3" href="{{ route('user.create') }}">Add User</a> --}}
                                <table class="table text-center" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Order Id</th>
                                            <th>Product</th>
                                            <th>Customer Name</th>
                                            {{-- <th>Category</th> --}}
                                            {{-- <th>Sub Category</th> --}}
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>total</th>
                                            <th>Order Date</th>
                                            <th>Status</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($orderItems as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $data->order->code }}</td>
                                                <td>{{ $data->product->product_name ?? '' }}</td>
                                                <td>{{ $data->order->user->name ?? '' }}</td>
                                                {{-- <td>{{ $data->product->subcategory->category->name ?? '' }}</td> --}}
                                                {{-- <td>{{ $data->product->subcategory->name ?? '' }}</td> --}}
                                                <td>{{ $data->quantity ?? '' }}<br></td>
                                                <td>{{ $data->product->price ?? '' }}<br> </td>
                                                <td>{{ $data->total ?? '' }}<br></td>
                                                <td>{{ $data->created_at ? $data->created_at->format('d-m-Y') : '' }}</td>
                                                {{-- <td>{{ $data->commission ?? '' }}<br></td> --}}




                                                <td>
                                                    @if ($data->order->status == 1)
                                                        <div class="badge badge-success badge-shadow">Approved</div>
                                                    @else
                                                        <div class="badge badge-danger badge-shadow">Pending</div>
                                                    @endif
                                                </td>
                                                <td
                                                    style="display: flex;align-items: center;justify-content: center;column-gap: 8px">
                                                    <a onClick="viewDetail('{{ $data->order->id }}')" class="btn modal-btn"
                                                        style="color: var(--theme-color)!important;font-weight: bold">
                                                        <i class="fa fa-eye" style="font-size: 18px;"></i>
                                                    </a>
                                                    @if ($data->order->status == 1)
                                                        <form
                                                            action="{{ route('pharmacy.order.status', ['id' => $data->order->id]) }}"
                                                            method="post">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success" disabled>
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-toggle-left">
                                                                    <rect x="1" y="5" width="22"
                                                                        height="14" rx="7" ry="7"></rect>
                                                                    <circle cx="16" cy="12" r="3">
                                                                    </circle>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @else
                                                            <form
                                                            action="{{ route('pharmacy.order.status', ['id' => $data->order->id]) }}"
                                                            method="post">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger show_confirm">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-toggle-right">
                                                                    <rect x="1" y="5" width="22"
                                                                        height="14" rx="7" ry="7"></rect>
                                                                    <circle cx="8" cy="12" r="3">
                                                                    </circle>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    {{-- <a class="btn btn-info"
                                                        href="{{ route('user.edit', $officer->id) }}">Edit</a>
                                                    <form method="post"
                                                        action="{{ route('user.destroy', $officer->id) }}">
                                                        @csrf
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <button type="submit" class="btn btn-danger btn-flat show_confirm"
                                                            data-toggle="tooltip">Delete</button>
                                                    </form> --}}
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
    <div id="response_append">

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
            $('#table_id_events').DataTable();
        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>
    <script type="text/javascript">
        $('.show_confirm').click(function(event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            Swal.fire({
                title: "Are you sure you want to approve the Order?",
                text: "If you approve this, it will never be changed.",
                icon: "success",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>

    <script>
        function viewDetail(id) {
            $.ajax({
                url: '{{ URL::to('/pharmacy/view-order-detail') }}',
                type: 'GET',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id': id
                },
                success: function(response) {
                    $('#response_append').empty();
                    $('#response_append').append(response.data);
                    $('#viewDetail').modal('show');
                }
            });
        }
    </script>
    <script>
        $('.modal-btn').on('click', function() {
            $(this).addClass('disabled');
            setTimeout(() => {
                $(this).removeClass('disabled');
            }, 500);
        });
    </script>
@endsection
