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
                                    <h4>Report</h4>
                                </div>
                            </div>

                            <div class="card-body table-striped table-bordered table-responsive">
                                <form action={{ route('report.check') }} method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row mx-0 px-4">
                                        <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label for="datepicker">Select a date:</label>
                                                <input type="text" class="form-control" placeholder="Select Date" name="selected_date"
                                                    id="datepicker" />
                                                @error('selected_date')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-center row">
                                        <div class="col">
                                            <button type="submit" class="btn btn-success mr-1 btn-bg"
                                                id="submit">Select</button>
                                        </div>
                                    </div>
                                </form>
                                <div class="d-flex">
                                    <h6 class="mr-4">Total Sale: {{ $total }}</h3>
                                        <h6>Total Commission: {{ $total_Commission }}</h3>
                                </div>
                                @if (count($orderItems) > 0)
                                    <table class="table text-center" id="table_id_events">
                                        <thead>
                                            <tr>
                                                <th>Sr.</th>
                                                <th>Order Id</th>
                                                <th>Product</th>
                                                <th>Customer Name</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>total</th>
                                                <th>Order Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($orderItems as $data)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $data->order->code }}</td>
                                                    <td>{{ $data->product->product_name ?? '' }}</td>
                                                    <td>{{ $data->order->user->name ?? '' }}</td>
                                                    <td>{{ $data->quantity ?? '' }}<br></td>
                                                    <td>{{ $data->product->price ?? '' }}<br> </td>
                                                    <td>{{ $data->total ?? '' }}<br></td>
                                                    <td>{{ $data->created_at ? $data->created_at->format('d-m-Y') : '' }}
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                    @else
                                    <div style="text-align: center;">
                                        <p style="display: inline-block;">No orders found</p>
                                      </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('js')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
    @if (\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
        </script>
    @endif
    <script>
        $(document).ready(function() {
            $('#table_id_events').DataTable({
                searching: false
            });
        })
    </script>
    <script>
        var dp = $("#datepicker").datepicker({
            format: "mm-yyyy",
            startView: "months",
            minViewMode: "months"
        });

    </script>

@endsection
