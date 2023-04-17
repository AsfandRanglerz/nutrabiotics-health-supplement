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
                                    <h4>Photos</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                <table class="table text-center" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Photo</th>


                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $product)
                                            <tr>

                                                <td>{{ $loop->iteration }}</td>

                                                <td>
                                                    <img src="{{ asset($product->photo) }}" alt="" height="50"
                                                        width="50" class="image">
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
    
    <script>
        $(document).ready(function() {
            $('#table_id_events').DataTable()

        })
    </script>
@endsection
