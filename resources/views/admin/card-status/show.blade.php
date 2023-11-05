@section('title')
Theta - Slider
@endsection
@extends('layouts.admin.main')

@section('rightbar-content')
    <div class="breadcrumbbar">
        <div class="row align-items-center">
            <div class="col-md-8 col-lg-8">
                <h4 class="page-title">Starter</h4>
                <div class="breadcrumb-list">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Basic Pages</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Starter</li>
                    </ol>
                </div>
            </div>
            <div class="col-md-4 col-lg-4">
                <div class="widgetbar">
                    <button class="btn btn-primary">Add Widget</button>
                </div>
            </div>
        </div>
    </div>
    <div class="contentbar">
        <!-- Start row -->
        <div class="row">

            <div class="col-md-12">
                <div class="box">
                    <div id="app" class="container tm-new-page-container">

                        <section class="content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box">
                                        <div class="card">
                                            <div class="card-header">Testimonial {{ $testimonial->id }}</div>
                                            <div class="card-body">

                                                <a href="{{ route('admin.testimonials.index') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                                                <a href="{{route('admin.testimonials.edit',['id'=>$item->id])}}" title="Edit icon"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                                                <form method="POST" action="{{route('admin.testimonials.destroy',['id'=>$item->id])}}" accept-charset="UTF-8" style="display:inline">
                                                    {{ method_field('DELETE') }}
                                                    {{ csrf_field() }}
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete icon" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                                                </form>
                                                <br/>
                                                <br/>

                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <tbody>
                                                            <tr>
                                                                <th>ID</th><td>{{ $testimonial->id }}</td>
                                                            </tr>
                                                            <tr><th> Full name </th><td> {{ $testimonial->full_name }} </td></tr>
                                                            <tr><th> Type </th><td> {{ $testimonial->type }} </td></tr>
                                                            <tr><th> Image </th><td> <img src="{{asset('assets/img/testimonials'.$item->image)}}"> </td></tr>
                                                            <tr><th> Description </th><td> {{ $testimonial->description }} </td></tr>
                                                            <tr><th> Image alt </th><td> {{ $testimonial->alt }} </td></tr>
                                                            <tr><th> Ordering </th><td> {{ $testimonial->ordering }} </td></tr>
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
                </div>
            </div>
        </div>
    </div>
@endsection
