@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Notifications')}}</title>
@endsection
@section('admin-content')
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>{{__('admin.Notifications')}}</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
        <div class="breadcrumb-item">{{__('admin.Notifications')}}</div>
      </div>
    </div>
    <div class="section-body">
      <div class="row mt-4">
          <div class="col">
            <div class="card">
              <div class="card-body">
                <div class="table-responsive table-invoice">
                  <table class="table table-striped" id="dataTable">
                      <thead>
                          <tr>
                              <th width="5%">{{__('admin.SN')}}</th>
                              <th width="30%">{{__('admin.Message')}}</th>
                              <th width="20%">{{__('admin.Read At')}}</th>
                              <th width="20%">{{__('admin.Mark As Read')}}</th>
                            </tr>
                      </thead>
                      <tbody>
                          @foreach ($notifications as $index => $notification)
                              <tr>
                                  <td>{{ ++$index }}</td>
                                  <td><a href="{{ $notification->link }}">{{ $notification->message }}</a></td>
                                  <td>{{ $notification->read_at}}</td>
                                  <td>@if (!$notification->read_at)
                                      <form action="{{ route('admin.mark.notification.read', $notification->id) }}" method="POST">
                                          @csrf
                                          <button type="submit" class="btn btn-sm btn-primary">Mark as Read</button>
                                      </form>
                                  @endif</td>
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
