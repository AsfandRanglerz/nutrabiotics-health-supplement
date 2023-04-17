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
                                    <h4>WithDrawal Requests</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                {{-- <a class="btn btn-success mb-3" href="{{ route('user.create') }}">Add User</a> --}}
                                <table class="table text-center" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Pharmacy Name</th>
                                            <th>Email</th>
                                            <th>Payment</th>
                                            <th>Status</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $officer)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $officer->pharmacy->name ?? '' }}</td>
                                                <td>{{ $officer->pharmacy->email ?? '' }}</td>
                                                <td>{{ $officer->payment }}</td>

                                                <td>
                                                    @if ($officer->status == 1)
                                                        <div class="badge badge-success badge-shadow">Approved</div>
                                                    @else
                                                        <div class="badge badge-danger badge-shadow">Pending</div>
                                                    @endif
                                                </td>
                                                <td
                                                    style="display: flex;align-items: center;justify-content: center;column-gap: 8px">
                                                    <a onClick="viewDetail('{{ $officer->pharmacy->id }}')"
                                                        class="btn modal-btn"
                                                        style="color: var(--theme-color)!important;font-weight: bold"
                                                        class="bi bi-cash-coin" data-target="#exampleModal"><svg
                                                            xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                                            fill="currentColor" class="bi bi-cash-coin" viewBox="0 0 16 16">
                                                            <path fill-rule="evenodd"
                                                                d="M11 15a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm5-4a5 5 0 1 1-10 0 5 5 0 0 1 10 0z" />
                                                            <path
                                                                d="M9.438 11.944c.047.596.518 1.06 1.363 1.116v.44h.375v-.443c.875-.061 1.386-.529 1.386-1.207 0-.618-.39-.936-1.09-1.1l-.296-.07v-1.2c.376.043.614.248.671.532h.658c-.047-.575-.54-1.024-1.329-1.073V8.5h-.375v.45c-.747.073-1.255.522-1.255 1.158 0 .562.378.92 1.007 1.066l.248.061v1.272c-.384-.058-.639-.27-.696-.563h-.668zm1.36-1.354c-.369-.085-.569-.26-.569-.522 0-.294.216-.514.572-.578v1.1h-.003zm.432.746c.449.104.655.272.655.569 0 .339-.257.571-.709.614v-1.195l.054.012z" />
                                                            <path
                                                                d="M1 0a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h4.083c.058-.344.145-.678.258-1H3a2 2 0 0 0-2-2V3a2 2 0 0 0 2-2h10a2 2 0 0 0 2 2v3.528c.38.34.717.728 1 1.154V1a1 1 0 0 0-1-1H1z" />
                                                            <path
                                                                d="M9.998 5.083 10 5a2 2 0 1 0-3.132 1.65 5.982 5.982 0 0 1 3.13-1.567z" />
                                                        </svg></a>
                                                    @if ($officer->status == 1)
                                                        <form
                                                            action="{{ route('withDrawalRequest.status', ['id' => $officer->id]) }}"
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
                                                            action="{{ route('withDrawalRequest.status', ['id' => $officer->id]) }}"
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
            $('#table_id_events').DataTable()

        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>
    <script type="text/javascript">
        $('.show_confirm').click(function(event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            Swal.fire({
                title: "Are you sure you want to approve the withdrawal request?",
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
            // alert(id);
            $.ajax({
                url: '{{ URL::to('/admin/withdrawal-account-detail') }}',
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
