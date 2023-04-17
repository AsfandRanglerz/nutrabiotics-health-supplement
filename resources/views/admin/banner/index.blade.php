@extends('admin.layout.app')
@section('title', 'index')

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-4 col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>Add banner</h4>
                            </div>
                            <form action="{{route('banner.store')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Choose Image</label>
                                        <input type="file" name="image" value="{{ old('image') }}"
                                            class="form-control">
                                        @error('image')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button class="btn btn-primary mr-1" type="submit">Add</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-12 col-md-8 col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h4>All Banners<small class="font-weight-bold"></small></h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered text-center" id="table">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Sr.</th>
                                                <th class="text-center">image</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data as $banner)
                                                   <tr>
                                                       <td >{{ $loop->iteration }}</td>
                                                       <td>
                                                        <img src="{{ asset($banner->image) }}" alt="" height="50"
                                                            width="50" class="image">
                                                    </td>
                                                       <td
                                                       style="display: flex;align-items: center;justify-content: center;column-gap: 8px">
                                                       <a class="btn btn-info"
                                                               href="{{route('banner.edit', $banner->id)}}">Edit</a>
                                                               <form method="POST" action="{{ route('banner.destroy', $banner->id) }}">
                                                                   @csrf
                                                                   <input name="_method" type="hidden" value="DELETE">
                                                                   <button type="submit" class="btn btn-danger btn-flat show_confirm" data-toggle="tooltip" >Delete</button>
                                                               </form>
                                                                  </td>
                                                               </tr>
                                             @endforeach

                                           </tbody>

                                            {{-- @empty
                                                <tr>
                                                    <td colspan="4">Data Not Found!</td>
                                                </tr>
                                            @endforelse --}}
                                        </tbody>
                                    </table>
                                </div>
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
        $('#table').DataTable();

    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script type="text/javascript">

$('.show_confirm').click(function(event) {
          var form =  $(this).closest("form");
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
