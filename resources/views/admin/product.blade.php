@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Products')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Products')}}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.Products')}}</div>
            </div>
          </div>
          <div class="section-body products">
            <a href="{{ route('admin.product.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> {{__('admin.Add New')}}</a>
            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                      <div class="table-responsive table-invoice">
                        <table class="table table-striped" id="dataTableMy">
                            <thead>
                                <tr>
                                    <th width="5%">{{__('admin.SN')}}</th>
                                    <th width="5%">{{__('admin.Name')}}</th>
                                    <th width="25%">{{__('admin.Category')}}</th>
                                    <th width="20%">{{__('admin.Brand')}}</th>
                                    <th width="10%">{{__('admin.Photo')}}</th>
                                    <th width="5%">{{__('admin.Type')}}</th>
                                    <th width="5%">{{__('admin.Status')}}</th>
                                    <th width="20%">{{__('admin.Action')}}</th>
                                  </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $index => $product)
                                    <tr>
                                        <td>{{ ++$index }}</td>
                                        <td><a href="{{ route('admin.product.show',$product->id) }}">{{ $product->short_name }}</a></td>
                                        <td>{{ $product->getCategory() }}</td>
                                        <td>{{ $product->brand?$product->brand->name:'' }}</td>

                                        <td>@if(!empty($product->thumb_image)) 
                                          <img class="rounded-circle" src="{{ $product->thumb_image }}" alt="" width="100px" height="100px">@endif</td>
                                        <td>
                                            @if ($product->new_product == 1)
                                            <span class="badge badge-primary p-1">{{__('admin.New')}}</span>
                                            @endif

                                            @if ($product->is_featured == 1)
                                            <span class="badge badge-success p-1">{{__('admin.Featured')}}</span>
                                            @endif

                                            @if ($product->is_top == 1)
                                            <span class="badge badge-warning p-1">{{__('admin.Top')}}</span>
                                            @endif

                                            @if ($product->is_best == 1)
                                            <span class="badge badge-danger p-1">{{__('admin.Best')}}</span>
                                            @endif

                                        </td>
                                        <td>
                                            @if($product->status == 1)
                                              @if(Auth::user()->admin_type==1)
                                                <a href="javascript:;"  onclick="changeProductStatus({{$product->id}})">
                                                    <input   {{(Auth::user()->admin_type==0)?'disabled="true"':''}}  type="checkbox" checked data-toggle="toggle" data-on="{{__('admin.Active')}}" data-off="{{__('admin.InActive')}}" data-onstyle="success" data-offstyle="danger">
                                                </a>
                                              @else
                                                <a href="javascript:;"  onclick="">
                                                    <input   {{(Auth::user()->admin_type==0)?'disabled="true"':''}}  type="checkbox" checked data-toggle="toggle" data-on="{{__('admin.Active')}}" data-off="{{__('admin.InActive')}}" data-onstyle="success" data-offstyle="danger">
                                                </a>
                                              @endif
                                            @else
                                            @if(Auth::user()->admin_type==1)

                                              <a href="javascript:;"  onclick="changeProductStatus({{$product->id}})" >
                                                  <input {{(Auth::user()->admin_type==0)?'disabled="true"':''}}  type="checkbox" data-toggle="toggle" data-on="{{__('admin.Active')}}" data-off="{{__('admin.InActive')}}" data-onstyle="success" data-offstyle="danger">
                                              </a>
                                              @else
                                              <a href="javascript:;"  onclick="" >
                                                  <input  {{(Auth::user()->admin_type==0)?'disabled="true"':''}}  type="checkbox" data-toggle="toggle" data-on="{{__('admin.Active')}}" data-off="{{__('admin.InActive')}}" data-onstyle="success" data-offstyle="danger">
                                              </a>
                                              @endif
                                            @endif
                                        </td>
                                        <td>
                                        <a href="{{ route('admin.product.edit',$product->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>

                                        @php
                                            $existOrder = $orderProducts->where('product_id',$product->id)->count();
                                        @endphp
                                        @if(Auth::guard()->user()->is_moder!="1")
                                          @if ($existOrder == 0)
                                              <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData({{ $product->id }})"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                          @else
                                              <a href="javascript:;" data-toggle="modal" data-target="#canNotDeleteModal" class="btn btn-danger btn-sm" disabled><i class="fa fa-trash" aria-hidden="true"></i></a>
                                          @endif
                                        @endif


                                        <div class="dropdown d-inline">
                                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                              <i class="fas fa-cog"></i>
                                            </button>

                                            <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -131px, 0px);">
                                              <a class="dropdown-item has-icon" href="{{ route('admin.product-gallery',$product->id) }}"><i class="far fa-image"></i> {{__('admin.Image Gallery')}}</a>

                                              <a class="dropdown-item has-icon" href="{{ route('admin.product-variant',$product->id) }}"><i class="fas fa-cog"></i>{{__('admin.Product Variant')}}</a>

                                            </div>
                                          </div>

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

      <!-- Modal -->
      <div class="modal fade" id="canNotDeleteModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                        <div class="modal-body">
                            {{__('admin.You can not delete this product. Because there are one or more order has been created in this product.')}}
                        </div>

                  <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('admin.Close')}}</button>
                  </div>
              </div>
          </div>
      </div>
      <script>
      $(document).ready(function () {
        var exampleDataTable = $('#dataTableMy').DataTable({
           stateSave: true,
           processing: true,
      initComplete: function () {
        this.api().columns([2,3]).every(function (d) {
          var column = this;
           var theadname = $("#dataTableMy th").eq([d]).html();
          var select = $('<select class="form-control" style="width:100%"><option value="">' +theadname +": All</option></select>")
            .appendTo($(column.header()).empty())
            .on('change', function () {
              var val = $.fn.dataTable.util.escapeRegex(
                $(this).val()
              );

              column
                .search(val ? '^' + val + '$' : '', true, false)
                .draw();
            });

          column.data().unique().sort().each(function (d, j) {
            select.append('<option value="' + d + '">' + d + '</option>')
          });
        });
      }

    });

    // Event handler when position select is changed
    $(exampleDataTable.columns(1).header()).find('select').on('change', function () {
      var nextSelect = $(exampleDataTable.columns(2).header()).find('select');
      var nextColumn = exampleDataTable.column(2);
      var nextColumnResults = exampleDataTable.column(2, { search: 'applied' });
      nextColumn.search('').draw();
      nextSelect.empty();
      nextSelect.append('<option value=""></option>');
      nextColumnResults.data().unique().sort().each(function (d, j) {
        nextSelect.append('<option value="' + d + '">' + d + '</option>')
      });
    });
  });
</script>
<script>
    function deleteData(id){
        $("#deleteForm").attr("action",'{{ url("admin/product/") }}'+"/"+id)
    }
    function changeProductStatus(id){
        var isDemo = 1
        if(isDemo == 0){
            toastr.error('This Is Demo Version. You Can Not Change Anything');
            return;
        }
        $.ajax({
            type:"put",
            data: { _token : '{{ csrf_token() }}' },
            url:"{{url('/admin/product-status/')}}"+"/"+id,
            success:function(response){
                toastr.success(response)
            },
            error:function(err){
                console.log(err);

            }
        })
    }
</script>
@endsection
