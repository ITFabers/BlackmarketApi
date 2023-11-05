@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Products')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Create Product')}}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.Create Product')}}</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.product.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('admin.Products')}}</a>
            <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data" class="create-product">
                            @csrf
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>{{__('admin.Thumbnail Image Preview')}}</label>
                                    <div>
                                        <img id="preview-img" class="admin-img" src="{{ asset('uploads/website-images/preview.png') }}" alt="">
                                    </div>

                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Thumnail Image')}}</label>
                                    <input type="file" class="form-control-file"  name="thumb_image" onchange="previewThumnailImage(event)">
                                </div>



                                <div class="form-group col-12">
                                    <label>{{__('admin.Product Gallery')}}</label>
                                    <input type="file" class="form-control-file" multiple name="gallery[]">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Short Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="short_name" class="form-control"  name="short_name" value="{{ old('short_name') }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="name" class="form-control"  name="name" value="{{ old('name') }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Slug')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="slug" class="form-control"  name="slug" value="{{ old('slug') }}">
                                </div>
                                @if(!empty(old('subcategories')) )
                                  <div class="form-group col-12"  id="dynamicDropdowns">
                                      <label>{{__('admin.Category')}} <span class="text-danger">*</span></label>
                                      <select name="subcategories[]" class="form-control select2 categoryDropdown" data-level="0">
                                          <option value="">Select Category</option>
                                          @foreach ($categories as $category)
                                              <option value="{{ $category->id }}" {{old('subcategories')[0] == $category->id ?'selected':''}}>{{ $category->name }}</option>
                                          @endforeach
                                      </select>
                                      <?php if (!empty(old('subcategories')[0])) {
                                        $subcategories = App\Models\Category::where('parent_id', old('subcategories')[0])->get();
                                      }else {
                                        $subcategories=[];
                                      } ?>
                                      <select name="subcategories[]" class="form-control mt-3 categoryDropdown categoryDropdown1" data-level="1">
                                          <option value="0">Select Subcategory</option>
                                          @foreach ($subcategories as $category)
                                              <option value="{{ $category->id }}"  {{old('subcategories')[1] == $category->id ?'selected':''}}>{{ $category->name }}</option>
                                          @endforeach
                                      </select>
                                  </div>

                                @else
                                <div class="form-group col-12"  id="dynamicDropdowns">
                                    <label>{{__('admin.Category')}} <span class="text-danger">*</span></label>
                                    <select name="subcategories[]" class="form-control select2 categoryDropdown" data-level="0">
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                @endif

                                <div class="form-group col-12">
                                    <label>{{__('admin.Brand')}} </label>
                                    <select name="brand" class="form-control select2" id="brand">
                                        <option value="">{{__('admin.Select Brand')}}</option>
                                        @foreach ($brands as $brand)
                                            <option {{ old('brand') == $brand->id ? 'selected' : '' }} value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.SKU')}} </label>
                                   <input type="text" class="form-control" name="sku">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Price')}} <span class="text-danger">*</span></label>
                                   <input type="text" class="form-control" name="price" value="{{ old('price') }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Discount')}}</label>
                                   <input type="text" class="form-control" name="offer_price" value="{{ old('offer_price') }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Short Description')}} <span class="text-danger">*</span></label>
                                    <textarea name="short_description" id="" cols="30" rows="10" class="form-control text-area-5">{{ old('short_description') }}</textarea>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Long Description')}} <span class="text-danger">*</span></label>
                                    <textarea name="long_description" id="" cols="30" rows="10" class="summernote">{{ old('long_description') }}</textarea>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Highlight')}}</label>
                                    <div>
                                        <input type="checkbox"name="top_product" id="top_product"> <label for="top_product" class="mr-3" >{{__('admin.Top Product')}}</label>

                                        <input type="checkbox" name="new_arrival" id="new_arrival"> <label for="new_arrival" class="mr-3" >{{__('admin.New Arrival')}}</label>

                                        <input type="checkbox" name="best_product" id="best_product"> <label for="best_product" class="mr-3" >{{__('admin.Best Product')}}</label>

                                        <input type="checkbox" name="is_featured" id="is_featured"> <label for="is_featured" class="mr-3" >{{__('admin.Featured Product')}}</label>
                                    </div>
                                </div>
                                @if(Auth::user()->admin_type==1)
                                  <div class="form-group col-12">
                                      <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                      <select name="status" class="form-control">
                                          <option value="1">{{__('admin.Active')}}</option>
                                          <option value="0">{{__('admin.Inactive')}}</option>
                                      </select>
                                  </div>
                                @endif
                                <div class="form-group col-12">
                                  <a href="javascript:;" data-toggle="modal" data-target="#createVariant"  class="btn btn-primary"><i class="fas fa-plus"></i> Add new specification</a>
                                  <a href="javascript:;" data-toggle="modal" data-target="#createVariantItem"  class="btn btn-primary"><i class="fas fa-plus"></i> Add new specification item</a>

                                </div>


                              @if(!empty(old('variant')) )
                              <div class="form-group col-12" id="variant-selects">
                                @foreach(old('variant') as $key=> $var)
                                  <div class="variant-select-row">
                                      <label for="variant">Select Specification</label>
                                      <select name="variant[]" class="variant form-control  ">
                                          <option value="">Select a specification...</option>
                                          @foreach($variants as $variant)
                                              <option value="{{ $variant->id }}" {{$var == $variant->id ?'selected':''}}>{{ $variant->name }}</option>
                                          @endforeach
                                      </select>
                                      <?php $variantItems = App\Models\ProductVariantItem::where('product_variant_id',$var)->get() ?>
                                      <!-- Variant Item dropdown will be populated dynamically -->
                                      <select name="variant_item[]" class="mt-3 variant-item form-control">
                                          <option value="">Select a specification item...</option>
                                          @foreach($variantItems as $variant)
                                              <option value="{{ $variant->id }}" {{old('variant_item')[$key] == $variant->id ?'selected':''}}>{{ $variant->name }}</option>
                                          @endforeach
                                      </select>
                                      <input type="text" class="form-control mt-3 variant-text" placeholder="Text" name="text[]" value="{{old('text')[$key]}}">

                                      <!-- Button to remove this variant select -->
                                      <button type="button" class=" mt-3 mb-3 remove-variant btn btn-danger">Remove</button>
                                  </div>
                                @endforeach
                            </div>
                              @else
                              <div class="form-group col-12" id="variant-selects">
                                <div class="variant-select-row">
                                    <label for="variant">Select Specification</label>
                                    <select name="variant[]" class="variant form-control  ">
                                        <option value="">Select a specification...</option>
                                        @foreach($variants as $variant)
                                            <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                                        @endforeach
                                    </select>

                                    <!-- Variant Item dropdown will be populated dynamically -->
                                    <select name="variant_item[]" class="mt-3 variant-item form-control">
                                        <option value="">Select a specification item...</option>
                                    </select>
                                    <input type="text" class="form-control mt-3 variant-text" placeholder="Text" name="text[]" value="">

                                    <!-- Button to remove this variant select -->
                                    <button type="button" class=" mt-3 mb-3 remove-variant btn btn-danger">Remove</button>
                                </div>

                            </div>
                              @endif
                              <div class="form-group col-12">

                              <button type="button" class="mb-3 btn btn-primary" id="add-variant">+</button>
                            </div>
                                <div class="form-group col-12">
                                    <label>{{__('admin.SEO Title')}}</label>
                                   <input type="text" class="form-control" name="seo_title" value="{{ old('seo_title') }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.SEO Description')}}</label>
                                    <textarea name="seo_description" id="" cols="30" rows="10" class="form-control text-area-5">{{ old('seo_description') }}</textarea>
                                </div>
                                <div class="form-group col-12">
                                    <label>{{__('admin.Show Homepage ?')}}  <span class="text-danger">*</span></label>
                                    <select name="show_homepage" class="form-control">
                                        <option value="0">{{__('admin.No')}}</option>
                                        <option value="1">{{__('admin.Yes')}}</option>
                                    </select>
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
      <div class="modal fade" id="createVariant" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('admin.Create Variant')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                  <div class="modal-body">
                      <div class="container-fluid">
                        <form action="{{ route('admin.store-product-variant') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>{{__('admin.Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="name" class="form-control"  name="name">
                                </div>
                                <div class="form-group col-12">
                                    <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control">
                                        <option value="1">{{__('admin.Active')}}</option>
                                        <option value="0">{{__('admin.Inactive')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-primary">{{__('admin.Save')}}</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('admin.Close')}}</button>
                                </div>
                            </div>
                        </form>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <div class="modal fade" id="createVariantItem" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title">{{__('admin.Create Product Variant Item')}}</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <div class="container-fluid">
                        <form action="{{ route('admin.store-product-variant-item') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group col-12">
                                  <select name="variant_id" class="form-control">
                                      <option value="">Select a specification...</option>
                                      @foreach($variants as $variant)
                                          <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                                      @endforeach
                                  </select>
                                </div>
                                <div class="form-group col-12">
                                    <label>{{__('admin.Item Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="name" class="form-control"  name="name" >
                                </div>
                                <div class="form-group col-12">
                                    <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control">
                                        <option value="1">{{__('admin.Active')}}</option>
                                        <option value="0">{{__('admin.Inactive')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">{{__('admin.Save')}}</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('admin.Close')}}</button>
                                </div>
                            </div>
                        </form>
                      </div>
                  </div>
              </div>
          </div>
      </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    (function($) {
        "use strict";
        $(document).ready(function () {
            $("#name").on("focusout",function(e){
                $("#slug").val(convertToSlug($(this).val()));
            })

                var initialVariantSelect = $('.variant:first').clone();
            var initialVariantItemsSelect = $('.variant-item:first').clone();
            var initialVariantItemsText = $('.variant-text:first').clone();

            // Add new variant select on button click
            $('#add-variant').on('click', function() {
                var newVariantSelectRow = $('.variant-select-row:last').clone();
                newVariantSelectRow.find('.variant').replaceWith(initialVariantSelect.clone());
                newVariantSelectRow.find('.variant-item').replaceWith(initialVariantItemsSelect.clone());
                newVariantSelectRow.find('.variant-text').replaceWith(initialVariantItemsText.clone());
                newVariantSelectRow.appendTo('#variant-selects');
            });

            // Remove variant select on button click
            $(document).on('click', '.remove-variant', function() {
                $(this).closest('.variant-select-row').remove();
            });

            // Fetch variant items on variant select change
            $(document).on('change', '.variant', function() {
                var variantId = $(this).val();
                var currentSelect = $(this);
                var variantItemsSelect = currentSelect.closest('.variant-select-row').find('.variant-item');

                $.ajax({
                    url: "/admin/get-variant-items",
                    type: "GET",
                    data: { product_variant_id: variantId },
                    success: function(response) {
                        variantItemsSelect.empty();
                        $(this).closest('.variant-select-row').find('.variant-text').val('')
                        $.each(response, function(index, variantItem) {
                            variantItemsSelect.append($('<option>', {
                                value: variantItem.id,
                                text: variantItem.name
                            }));
                        });
                    }
                });
            });
            $(document).on('change', '.categoryDropdown', function() {
            var selectedCategoryId = $(this).val();
            var level = $(this).data('level');
            var nextLevel = level + 1;
            $('.categoryDropdown1').remove()
            if (selectedCategoryId !== '0') {
                $.ajax({
                    url: '/admin/get-subcategories/' + selectedCategoryId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                      if (data.length>0) {
                        var subcategoryDropdown = '<select name="subcategories[]" class="categoryDropdown  mt-3 form-control select2" data-level="' + nextLevel + '">';
                        subcategoryDropdown += '<option value="0">Select Subcategory</option>';

                        $.each(data, function(index, subcategory) {
                            subcategoryDropdown += '<option value="' + subcategory.id + '">' + subcategory.name + '</option>';
                        });

                        subcategoryDropdown += '</select>';
                        $('#dynamicDropdowns').append(subcategoryDropdown);
                      }

                    }
                });
            }
            $(this).nextAll('.categoryDropdown').remove();
        });


        });
    })(jQuery);

    function convertToSlug(Text){
            return Text
                .toLowerCase()
                .replace(/[^\w ]+/g,'')
                .replace(/ +/g,'-');
    }

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
