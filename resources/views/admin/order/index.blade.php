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
                                            <th>Pharmacy</th>
                                            <th>User</th>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>total</th>
                                            <th>Order Date</th>
                                            <th>Status</th>
                                            <th>Action</th>


                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($orderItems as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $data->order->code }}</td>
                                                <td>{{ $data->order->pharmacy->name ?? '' }}</td>
                                                <td>{{ $data->order->user->name ?? '' }}</td>
                                                <td>{{ $data->product->product_name ?? '' }}</td>
                                                <td>{{ $data->quantity ?? '' }}<br></td>
                                                <td>{{ $data->product->price ?? '' }}<br> </td>
                                                <td>{{ $data->total ?? '' }}<br></td>
                                                <td>{{ $data->created_at ? $data->created_at->format('d-m-Y') : '' }}</td>
                                                <td>
                                                    @if ($data->order->status == 1)
                                                        <div class="badge badge-success badge-shadow">Approved</div>
                                                    @elseif ($data->order->status == 0)
                                                        @if (isset($data->order->description))
                                                        <form method="post" action="{{ route('order.status', $data->order->id) }}">
                                                            @csrf
                                                            @method('POST')
                                                            <div class="badge badge-warning show-confirm badge-shadow position-relative" data-name="order">
                                                                <span class="position-relative">Pending<i class="fas fa-circle position-absolute" style="top: -8px; color: red;"></i></span>
                                                            </div>
                                                            <button type="submit" class="d-none"></button>
                                                        </form>
                                                    </form>

                                                        @else
                                                            <div class="badge badge-warning badge-shadow">Pending</div>
                                                        @endif
                                                    @elseif ($data->order->status == 2)
                                                        <div class="badge badge-danger badge-shadow">Inactive</div>
                                                    @endif
                                                </td>

                                                <td
                                                style="display: flex;align-items: center;justify-content: center;column-gap: 8px">
                                                    <a onClick="viewDetail('{{ $data->order->id }}')"
                                                        class="btn modal-btn" style="color: var(--theme-color)!important;font-weight: bold">
                                                        <i class="fa fa-eye" style="font-size: 18px;"></i>
                                                    </a>
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
            $('#table_id_events').DataTable()

        })
    </script>
    <script>
        function viewDetail(id) {
            $.ajax({
                url: '{{ URL::to('/admin/view-order-detail') }}',
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script type="text/javascript">
        $('.show-confirm').click(function(event) {
            event.preventDefault();
            var form = $(this).closest("form");
            var name = $(this).data("name");
            swal({
                title: `Are you sure you want to inactive this order?`,
                text: "This action cannot be undone.",
                icon: "warning",
                buttons: ["Cancel", "Change"],
                dangerMode: true,
            }).then((willChange) => {
                if (willChange) {
                    // Add a hidden input field to the form to change the status
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'status',
                        value: 'inactive'
                    }).appendTo(form);

                    // Submit the form
                    form.submit();
                }
            });
        });
    </script>
@endsection
