@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Products')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Product View</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">Product View</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.product.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('admin.Products')}}</a>
            <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>{{__('admin.Thumbnail Image Preview')}}</label>
                                    <div>
                                        @if(!empty($product->thumb_image))
                                          <img id="preview-img" class="admin-img" src="{{asset($product->thumb_image)}}" alt="">
                                        @else
                                        <img id="preview-img" class="admin-img" src="{{ asset('uploads/website-images/preview.png') }}" alt="">
                                        @endif
                                    </div>

                                </div>


                                <div class="form-group col-12">
                                    <label>{{__('admin.Short Name')}}: </label>
                                    <span>{{$product->short_name}}</span>
                                </div>

                                <div class="form-group col-12">
                                  <label>{{__('admin.Name')}}: </label>
                                  <span>{{$product->name}}</span>
                                </div>

                                <div class="form-group col-12">
                                  <label>{{__('admin.Slug')}}: </label>
                                  <span>{{$product->slug}}</span>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Brand')}}: </label>
                                    <span>{{$product->brand->name??''}}</span>

                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.SKU')}}: </label>
                                   <span>{{$product->sku}}</span>
                                </div>

                                <div class="form-group col-12">
                                  <label>{{__('admin.Price')}}: </label>
                                  <span>{{$product->price}}</span>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Offer Price')}}: </label>
                                    <span>{{$product->offer_price}}</span>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Short Description')}}: </label>
                                    <span>{{$product->short_description}}</span>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Long Description')}}: </label>
                                    <span>{!!$product->long_description!!}</span>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Highlight')}}: </label>
                                    <div>
                                        <input type="checkbox"name="top_product" id="top_product"> <label for="top_product" class="mr-3" >{{__('admin.Top Product')}}</label>

                                        <input type="checkbox" name="new_arrival" id="new_arrival"> <label for="new_arrival" class="mr-3" >{{__('admin.New Arrival')}}</label>

                                        <input type="checkbox" name="best_product" id="best_product"> <label for="best_product" class="mr-3" >{{__('admin.Best Product')}}</label>

                                        <input type="checkbox" name="is_featured" id="is_featured"> <label for="is_featured" class="mr-3" >{{__('admin.Featured Product')}}</label>
                                    </div>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Status')}}: </label>
                                    <select name="status" class="form-control">
                                        <option value="1" {{($product->status==1?'checked':'')}}>{{__('admin.Active')}}</option>
                                        <option value="0" {{($product->status==0?'checked':'')}}>{{__('admin.Inactive')}}</option>
                                    </select>
                                </div>




                                <div class="form-group col-12">
                                    <label>{{__('admin.SEO Title')}}: </label>
                                    <span>{{$product->seo_title}}</span>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.SEO Description')}}: </label>
                                    <span>{{$product->seo_description}}</span>
                                </div>
                                @foreach($product->attributes as $attr)
                                  <div class="form-group price_input col-12 mb-2">
                                      <label>Variants</label><br>
                                      <span>
                                          {{$attr->getPriceLabel() . ': ' . $attr->text}}
                                      </span>
                                  </div>
                                @endforeach
                                {{--<div class="form-group col-12">
                                    <label>{{__('admin.Specifications')}}: </label>
                                    <div>
                                      @if(!empty($product->specifications))
                                        @foreach ($product->specifications as $specificationKey)
                                          <span>{{$specificationKey->specification}}</span>
                                        @endforeach
                                      @endif
                                    </div>
                                </div>--}}
                            </div>
                    </div>
                  </div>
                </div>
          </div>
        </section>
      </div>

@endsection
