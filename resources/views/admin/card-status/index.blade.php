@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Products')}}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Customer Card Status')}}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.Customer Card Status')}}</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.customer-card-status.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> {{__('admin.Add New')}}</a>
            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                      <div class="table-responsive table-invoice">
                        <table class="table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th>{{__('admin.SN')}}</th>
                                    <th>{{__('admin.Title')}}</th>
                                    <th>{{__('admin.Image')}}</th>
                                    <th>{{__('admin.Percentage')}}</th>
                                    <th>{{__('admin.Action')}}</th>
                                  </tr>
                            </thead>
                            <tbody>
                                @foreach ($cardStatus as $index => $item)
                                    <tr>
                                        <td>{{ ++$index }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>
                                            <img src="{{ asset($item->image) }}" alt="" width="150px">
                                        </td>
                                        <td>
                                          {{$item->percentage}}
                                        </td>
                                        <td>
                                        <a href="{{ route('admin.customer-card-status.edit',$item->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                        <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData({{ $item->id }})"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
    <script>
        function deleteData(id){
            $("#deleteForm").attr("action",'{{ url("admin/customer-card-status/") }}'+"/"+id)
        }
    </script>
@endsection
