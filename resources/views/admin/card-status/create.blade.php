@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Customer Card Status')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Create Customer Card Status')}}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
              <div class="breadcrumb-item active"><a href="{{ route('admin.customer-card-status.index') }}">{{__('admin.Customer Card Status')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.create Customer Card Status')}}</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.customer-card-status.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('admin.Customer Card Status')}}</a>
            <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.customer-card-status.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>{{__('admin.Thumbnail Image Preview')}}</label>
                                    <div>
                                        <img id="preview-img" class="admin-img" src="{{ asset('uploads/website-images/preview.png') }}" alt="">
                                    </div>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Thumbnail Image')}} <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control-file"  name="image" onchange="previewThumnailImage(event)">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Title')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="title" class="form-control"  name="title" value="{{ old('title') }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Image alt')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="image_alt" class="form-control"  name="image_alt" value="{{ old('image_alt') }}">
                                </div>
                                
                                <div class="form-group col-12">
                                    <label>{{__('admin.Percentage')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="percentage" class="form-control"  name="percentage" value="{{ old('percentage') }}">
                                </div>                                

                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-primary">{{__('admin.Save')}}</button>
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
        reader.onload = function(){
            var output = document.getElementById('preview-img');
            output.src = reader.result;
        }

        reader.readAsDataURL(event.target.files[0]);
    };

</script>
@endsection
