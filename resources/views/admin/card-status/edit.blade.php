@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Customer Card Status')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Edit Customer Card Status')}}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item active"><a href="{{ route('admin.customer-card-status.index') }}">{{__('admin.Customer Card Status')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.Edit Customer Card Status')}}</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.customer-card-status.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('admin.Customer Card Status')}}</a>
            <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.customer-card-status.update',$cardStatus->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>{{__('admin.Thumbnail Image Preview')}}</label>
                                    <div>
                                        <img id="preview-img" class="admin-img" src="{{ asset($cardStatus->image) }}" alt="">
                                    </div>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.New Thumbnail Image')}} <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control-file"  name="image" onchange="previewThumnailImage(event)">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Title')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="title" class="form-control"  name="title" value="{{ $cardStatus->title }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Image alt')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="image_alt" class="form-control"  name="image_alt" value="{{ $cardStatus->image_alt }}">
                                </div>                                

                                <div class="form-group col-12">
                                    <label>{{__('admin.Percentage')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="percentage" class="form-control"  name="percentage" value="{{ $cardStatus->percentage }}">
                                </div>                                

                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-primary">{{__('admin.Update')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                  </div>
                </div>
          </div>
        </section>
      </div>

<script>

    function previewThumnailImage(event) {
        var reader = new FileReader();

        reader.readAsDataURL(event.target.files[0]);
    };

</script>
@endsection
