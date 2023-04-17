@extends('pharmacy.layout.app')
@section('title', 'index')
@section('content')
    <div class="main-content" style="min-height: 562px;">
        <section class="">
            <div class="section-body">
                <div class="row">

                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Finance</h4>
                                </div>
                            </div>

                            {{-- @dd($filteredOrders); --}}
                            <div class="card-body table-striped table-bordered table-responsive">
                                <h4>Total Balance : {{ Auth::guard('pharmacy')->user()->balance }}</h4>
                                <h4>Withdraw Request : {{ $payment }}</h4>
                                <h4>Withdrawn : {{ $withdrawn }} </h4>

                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#exampleModal" data-whatever="@mdo">WithDrawal Request</button>
                                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">WithDrawal Request</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('pharmacy.withdrawal.store') }}" method="post">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="payment" class="col-form-label">Payment</label>
                                                        <input type="number" name="payment" id="payment"
                                                            value="{{ old('payment') }}" class="form-control"
                                                            placeholder="payment">
                                                        @error('payment')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary" id="submit"
                                                        disabled>WithDrawal</button>
                                                </div>
                                            </form>
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
    <script>
        $(document).ready(function() {
            $(document).on('keyup', '#payment', function() {
                var balance = '<?php echo Auth::guard('pharmacy')->user()->balance; ?>';
                var payment = $(this).val();
                var balanceNum = parseInt(balance);
                if (payment === '') {
                    $('.danger-msg').remove();
                    $('#submit').attr('disabled', true);
                } else if (payment > balanceNum) {
                    $('.danger-msg').remove();
                    $(this).after(
                        '<p class="mt-2 mb-0 text-danger danger-msg">You dont have enough money in your account</p>'
                    );
                    $('#submit').attr('disabled', true);
                } else {
                    $('.danger-msg').remove();
                    $('#submit').attr('disabled', false);
                }
            });
        });
    </script>
@endsection
