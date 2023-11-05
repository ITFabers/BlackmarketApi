@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Admin')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Edit Admin')}}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item active"><a href="{{ route('admin.admin.index') }}">{{__('admin.Admin')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.Edit Admin')}}</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.admin.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('admin.Admin')}}</a>
            <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                      <form action="{{ route('admin.admin.update',$admin->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="form-group col-12">
                              <label>{{__('admin.New Image')}}</label>
                              <input type="file" class="form-control-file" name="image">
                            </div>
                              <div class="form-group col-12">
                                  <label>{{__('admin.Name')}} <span class="text-danger">*</span></label>
                                  <input type="text" id="name" class="form-control"  name="name" value="{{$admin->name}}">
                              </div>
                              <div class="form-group col-12">
                                  <label>{{__('admin.Email')}} <span class="text-danger">*</span></label>
                                  <input type="text" id="slug" class="form-control"  name="email" value="{{$admin->email}}">
                              </div>
                              <div class="form-group col-12">
                                  <label>{{__('admin.Password')}} <span class="text-danger">*</span></label>
                                  <input type="password" id="password" class="form-control"  name="password">
                              </div>

                              <div class="form-group col-12">
                                  <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                  <select name="status" class="form-control">
                                      <option value="1" {{ $admin->status == 1 ? 'selected' : '' }} >{{__('admin.Active')}}</option>
                                      <option value="0" {{ $admin->status == 0 ? 'selected' : '' }} >{{__('admin.Inactive')}}</option>
                                  </select>
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

@endsection
