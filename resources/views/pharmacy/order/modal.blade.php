<!-- Button trigger modal -->


<!-- View Detail Modal -->
<div class="modal fade" id="viewDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class=" border-top border-bottom border-1" style="border-color: #f37a27 !important;">
                    <div class="card-body">
                        <button type="button" class="close " data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="card-body ">

                        </div>
                        <p class="lead fw-bold mb-4 text-center " style="color: #f37a27; word-spacing: 5px;"><strong>
                                Order Details </strong></p>

                        <div class="row ">

                            <div class="col-md-6">
                                <ul class="list-unstyled"">
                                    <li class="mb-1">Order ID: <strong class="text-muted"></strong></li>
                                        <li class="small m-1 text-muted">
                                            {{ $order->code }}
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-1">Order Date:</li>
                                    @foreach ($orderItems as $data)
                                        <li class="small text-muted">
                                            {{ $data->created_at ? $data->created_at->format('d-m-Y') : '' }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><strong>Customer Name</strong></li>
                                    <li class="mt-1">{{ $order->user->name ?? '' }}</li>
                                </ul>
                            </div>
                            <div class="col-md-6 m-auto">
                                <ul class="list-unstyled">
                                    <li class="text-md-end"><strong>Customer Email</strong></li>
                                    <li class=" text-md-end mt-1">{{ $order->user->email ?? '' }}</li>

                                </ul>
                            </div>
                        </div>


                        <hr class="m-1">


                        @foreach ($orderItems as $data)
                            <div class="row d-flex">
                                <div class="col-6">
                                    {{-- <ul class="text-decoration-none list-unstyled float-end p-0 m-0">
                                            <li class="mb-1 small"> <b>{{ $data->product->product_name ?? '' }}</b>
                                            </li>
                                        </ul> --}}
                                    <ul class="list-unstyled">
                                        <li><strong>Product</strong></li>
                                    </ul>
                                </div>
                                <div class="col-6 m-auto">
                                    {{-- <ul class="text-decoration-none list-unstyled float-end p-0 m-0">
                                            <li>Price</li>
                                            <li class="small text-muted m-1">{{ $data->price ?? '' }}</li>
                                        </ul> --}}
                                    <ul class="list-unstyled">
                                        <li class="text-end">{{ $data->product->product_name ?? '' }}</li>

                                    </ul>
                                </div>

                                <div class="col-6">
                                    <ul class="list-unstyled">
                                        <li><strong>Category</strong></li>
                                    </ul>
                                </div>
                                <div class="col-6 m-auto">
                                    <ul class="list-unstyled">
                                        <li class="text-end">{{ $data->product->subcategory->category->name ?? '' }}
                                        </li>

                                    </ul>
                                </div>
                                <div class="col-6">
                                    <ul class="list-unstyled">
                                        <li><strong>Sub Category</strong></li>
                                    </ul>
                                </div>
                                <div class="col-6 m-auto">
                                    <ul class="list-unstyled">
                                        <li class="text-end">{{ $data->product->subcategory->name ?? '' }}</li>

                                    </ul>
                                </div>
                                <div class="col-6">
                                    <ul class="list-unstyled">
                                        <li><strong>Price</strong></li>
                                    </ul>
                                </div>
                                <div class="col-6 m-auto">
                                    <ul class="list-unstyled">
                                        <li class="text-end">{{ $data->price ?? '' }}</li>

                                    </ul>
                                </div>
                                <div class="col-6">
                                    <ul class="list-unstyled">
                                        <li><strong>Qty</strong></li>
                                    </ul>
                                </div>
                                <div class="col-6 m-auto">
                                    <ul class="list-unstyled">
                                        <li class="text-end">{{ $data->quantity ?? '' }}</li>

                                    </ul>
                                </div>
                            </div>
                            <hr class="m-0 p-0">
                            <div class="row">
                                <div class="offset-6 col-6 mt-2">
                                    <ul class="list-unstyled">
                                        <li class=" text-muted"><strong>Sub Total:</strong> <span
                                                style="margin-left: 5px;">{{ $data->sub_total ?? '' }}</span></li>

                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="offset-6 col-6">
                                    <ul class="list-unstyled">
                                        <li class=" text-muted"><strong>Discount:</strong> <span
                                                style="margin-left: 5px;">{{ $data->d_per ?? '' }}%</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="offset-6 col-6">
                                    <ul class="list-unstyled">
                                        <li class=" text-muted"><strong>Commission:</strong> <span
                                                style="margin-left: 5px;">{{ $data->commission ?? '' }}</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="offset-6 col-6">
                                    <ul class="list-unstyled">
                                        <li class=" text-success"><strong>Total:</strong> <span
                                                style="margin-left: 5px; color: black;">{{ $data->total ?? '' }}</span>
                                        </li>
                                    </ul>

                                </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>



{{-- <div class="row">

                <div class="col-md-3 col-6 b-r">
                    <strong>Order Id</strong>
                    <br>
                    <p class="text-muted">{{ $order->code }}</p>
                </div>
                <div class="col-md-3 col-6 b-r">
                    <strong>Customer Name</strong>
                    <br>
                    <p class="text-muted">{{ $order->user->name ?? '' }}</p>
                </div>
                @foreach ($orderItems as $data)
                    <div class="col-md-3 col-6 b-r">
                        <strong>Category</strong><br>
                        <p class="text-muted">{{ $data->product->subcategory->category->name ?? '' }}</p>
                    </div>
                    <div class="col-md-3 col-6 b-r">
                        <strong>SubCategory</strong><br>
                        <p class="text-muted">{{ $data->product->subcategory->name ?? '' }}</p>
                    </div>
                    <div class="col-md-3 col-6 b-r">
                        <strong>Product</strong><br>
                        <p class="text-muted">{{ $data->product->product_name ?? '' }}</p>
                    </div>
                    <div class="col-md-3 col-6 b-r">
                        <strong>Quantity</strong><br>
                        <p class="text-muted">{{ $data->quantity ?? '' }}</p>
                    </div>
                    <div class="col-md-3 col-6 b-r">
                        <strong>Price</strong><br>
                        <p class="text-muted">{{ $data->price ?? '' }}</p>
                    </div>
                    <div class="col-md-3 col-6 b-r">
                        <strong>Total</strong><br>
                        <p class="text-muted">{{ $data->total ?? '' }}</p>
                    </div>
                    <div class="col-md-3 col-6 b-r">
                        <strong>Order Date</strong><br>
                        <p class="text-muted">{{ $data->created_at ? $data->created_at->format('d-m-Y') : '' }}</p>
                    </div>
                    <div class="col-md-3 col-6 b-r">
                        <strong>Commission</strong><br>
                        <p class="text-muted">{{ $data->commission ?? '' }}</p>
                    </div>
                @endforeach
            </div> --}}
