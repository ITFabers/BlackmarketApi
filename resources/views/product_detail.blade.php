@extends('layout')
@section('title')
    <title>{{ $product->seo_title }}</title>
@endsection
@section('meta')
    <meta name="description" content="{{ $product->seo_description }} {{ $tags }}">
@endsection

@section('public-content')


    <!--============================
         BREADCRUMB START
    ==============================-->
    <section id="wsus__breadcrumb" style="background: url({{  asset($product->banner_image) }});">
        <div class="wsus_breadcrumb_overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h4>{{__('Product')}}</h4>
                        <ul>
                            <li><a href="{{ route('home') }}">{{__('Home')}}</a></li>
                            <li><a href="{{ route('product') }}">{{__('Product')}}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        BREADCRUMB END
    ==============================-->
<!--============================
        PRODUCT DETAILS START
    ==============================-->
    <section id="wsus__product_details">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-md-5 col-lg-5">
                    <div id="sticky_pro_zoom">
                        <div class="exzoom hidden" id="exzoom">
                            <div class="exzoom_img_box">
                                @if ($product->video_link)
                                    @php
                                        $video_id=explode("=",$product->video_link);
                                    @endphp
                                    <a class="venobox wsus__pro_det_video" data-autoplay="true" data-vbtype="video"
                                        href="https://youtu.be/{{ $video_id[1] }}">
                                        <i class="fas fa-play"></i>
                                    </a>
                                @endif
                                <ul class='exzoom_img_ul'>
                                    @foreach ($product->gallery as $image)
                                    <li><img class="zoom ing-fluid w-100" src="{{ asset($image->image) }}" alt="product"></li>
                                    @endforeach


                                </ul>
                            </div>
                            <div class="exzoom_nav"></div>
                            <p class="exzoom_btn">
                                <a href="javascript:void(0);" class="exzoom_prev_btn"> <i class="far fa-chevron-left"></i> </a>
                                <a href="javascript:void(0);" class="exzoom_next_btn"> <i class="far fa-chevron-right"></i> </a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-5 col-md-7 col-lg-7">
                    <div class="wsus__pro_details_text">
                        <a class="title" href="javascript:;">{{ $product->name }}</a>
                            <input type="hidden" id="stock_qty" value="{{ $product->qty }}">
                            @if ($product->qty == 0)
                            <p class="wsus__stock_area"><span class="in_stock">{{__('Out of Stock')}}</span></p>
                            @else
                                <p class="wsus__stock_area"><span class="in_stock">{{__('In stock')}}</span>
                                    @if ($setting->show_product_qty == 1)
                                    ({{ $product->qty }} {{__('item')}})
                                    @endif
                                </p>
                            @endif

                        @php
                            $variantPrice = 0;
                            $variants = $product->variants->where('status', 1);
                            if($variants->count() != 0){
                                foreach ($variants as $variants_key => $variant) {
                                    if($variant->variantItems->where('status',1)->count() != 0){
                                        $item = $variant->variantItems->where('is_default',1)->first();
                                        if($item){
                                            $variantPrice += $item->price;
                                        }
                                    }
                                }
                            }

                            $today = date('Y-m-d H:i:s');

                            $totalPrice = $product->price;
                            if($product->offer_price != null){
                                $offerPrice = $product->offer_price;
                                $offer = $totalPrice - $offerPrice;
                                $percentage = ($offer * 100) / $totalPrice;
                                $percentage = round($percentage);
                            }


                        @endphp


                            @if ($product->offer_price == null)
                                <h4>{{ $currencySetting->currency_icon }} <span id="mainProductPrice">{{ sprintf("%.2f", $totalPrice + $variantPrice) }}</span> </h4>
                            @else
                                <h4>{{ $currencySetting->currency_icon }} <span id="mainProductPrice">{{ sprintf("%.2f", $product->offer_price + $variantPrice) }}</span>  <del>{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $totalPrice) }}</del></h4>
                            @endif


                        <p class="description">{{ $product->short_description }}</p>

                        @if ($product->is_flash_deal == 1)
                            @php
                                $end_time = $product->flash_deal_date;
                            @endphp
                            <script>
                                var end_year = {{ date('Y', strtotime($end_time)) }};
                                var end_month = {{ date('m', strtotime($end_time)) }};
                                var end_date = {{ date('d', strtotime($end_time)) }};
                            </script>
                            <div class="wsus_pro_hot_deals">
                                <h5>{{__('offer ending time')}} : </h5>
                                <div class="simply-countdown product-details"></div>
                            </div>
                        @endif

                        @php
                            $productPrice = 0;

                                if ($product->offer_price == null) {
                                    $productPrice = $totalPrice + $variantPrice;
                                }else {
                                    $productPrice = $product->offer_price + $variantPrice;
                                }

                        @endphp

                        <form id="shoppingCartForm">
                        <div class="wsus__quentity">
                            <h5>{{__('Quantity')}} :</h5>
                            <div class="modal_btn">
                                <button type="button" class="btn btn-danger btn-sm decrementProduct">-</button>
                                <input class="form-control product_qty" name="quantity" readonly type="text" min="1" max="100" value="1" />
                                <button type="button" class="btn btn-success btn-sm incrementProduct">+</button>
                            </div>
                            <h3 class="d-none">{{ $currencySetting->currency_icon }}<span id="product_price">{{ sprintf("%.2f",$productPrice) }}</span></h3>
                        </div>

                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="image" value="{{ $product->thumb_image }}">
                        <input type="hidden" name="slug" value="{{ $product->slug }}">

                        @if ($productVariants->count() != 0)
                            <div class="wsus__selectbox">
                                <div class="row">
                                    @foreach ($productVariants as $productVariant)
                                        @php
                                            $items = App\Models\ProductVariantItem::orderBy('is_default','desc')->where(['product_variant_id' => $productVariant->id, 'product_id' => $product->id])->get();
                                        @endphp
                                        @if ($items->count() != 0)
                                            <div class="col-xl-6 col-sm-6 mb-3">
                                                <h5 class="mb-2">{{ $productVariant->name }}:</h5>

                                                <input type="hidden" name="variants[]" value="{{ $productVariant->id }}">
                                                <input type="hidden" name="variantNames[]" value="{{ $productVariant->name }}">

                                                <select class="select_2 productVariant" name="items[]">
                                                    @foreach ($items as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif




                        <ul class="wsus__button_area">
                            <li><button type="submit" class="add_cart">{{__('add to cart')}}</button></li>
                            <li><a class="buy_now" href="javascript:;" id="buyNowBtn">{{__('buy now')}}</a></li>
                            <li><a href="javascript:;" onclick="addToWishlist('{{ $product->id }}')"><i class="fal fa-heart"></i></a></li>
                            <li><a href="javascript:;" onclick="addToCompare('{{ $product->id }}')"><i class="far fa-random"></i></a></li>
                        </ul>

                    </form>
                        @if ($product->sku)
                        <p class="brand_model"><span>{{__('Model')}} :</span> {{ $product->sku }}</p>
                        @endif

                        <p class="brand_model"><span>{{__('Brand')}} :</span> <a href="{{ route('product',['brand' => $product->brand->slug]) }}">{{ $product->brand->name }}</a></p>
                        <p class="brand_model"><span>{{__('Category')}} :</span> <a href="{{ route('product',['category' => $product->category->slug]) }}">{{ $product->category->name }}</a></p>
                        <div class="wsus__pro_det_share d-none">
                            <h5>{{__('share')}} :</h5>
                            <ul class="d-flex">
                                <li><a class="facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ route('product-detail', $product->slug) }}&t={{ $product->name }}"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a class="twitter" href="https://twitter.com/share?text={{ $product->name }}&url={{ route('product-detail', $product->slug) }}"><i class="fab fa-twitter"></i></a></li>
                                <li><a class="linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url={{ route('product-detail', $product->slug) }}&title={{ $product->name }}"><i class="fab fa-linkedin"></i></a></li>
                                <li><a class="pinterest" href="https://www.pinterest.com/pin/create/button/?description={{ $product->name }}&media=&url={{ route('product-detail', $product->slug) }}"><i class="fab fa-pinterest-p"></i></a></li>
                            </ul>
                        </div>
                        @auth
                            @php
                                $user = Auth::guard('web')->user();
                                $isExist = false;
                                $orders = App\Models\Order::where(['user_id' => $user->id])->get();
                                foreach ($orders as $key => $order) {
                                    foreach ($order->orderProducts as $key => $orderProduct) {
                                        if($orderProduct->product_id == $product->id){
                                            $isExist = true;
                                        }
                                    }
                                }
                            @endphp
                        @endauth

                    </div>
                </div>

                <div class="col-xl-3 col-md-12 mt-md-5 mt-lg-0">
                    <div class="wsus_pro_det_sidebar" id="sticky_sidebar">
                        <div class="lg_area">
                            <div class="wsus_pro_det_sidebar_single">
                                <i class="fal fa-truck"></i>
                                <div class="wsus_pro_det_sidebar_text">

                                    @if ($product->is_return == 1)
                                    <h5>{{__('Return Available')}}</h5>
                                    <p>{{ $product->returnPolicy->details }}</p>
                                    @else
                                        <h5>{{__('Return Not Available')}}</h5>
                                    @endif

                                </div>
                            </div>


                            <div class="wsus_pro_det_sidebar_single">
                                <i class="far fa-shield-check"></i>
                                <div class="wsus_pro_det_sidebar_text">
                                    <h5>{{__('Secure Payment')}}</h5>
                                    <p>{{__('We ensure secure payment')}}</p>
                                </div>
                            </div>
                            <div class="wsus_pro_det_sidebar_single">
                                <i class="fal fa-envelope-open-dollar"></i>
                                <div class="wsus_pro_det_sidebar_text">
                                    @if ($product->is_warranty == 1)
                                    <h5>{{__('Warranty Available')}}</h5>
                                    @else
                                    <h5>{{__('Warranty Not Available')}}</h5>
                                    @endif

                                </div>
                            </div>
                        </div>

                        @if ($banner->status == 1)
                            <div class="wsus__det_sidebar_banner">
                                <img src="{{ asset($banner->image) }}" alt="banner" class="img-fluid w-100">
                                    <div class="wsus__det_sidebar_banner_text_overlay">
                                    <div class="wsus__det_sidebar_banner_text">
                                        <p>{{ $banner->title }}</p>
                                        <h4>{{ $banner->description }}</h4>
                                        <a href="{{ $banner->link }}" class="common_btn">{{__('shop now')}}</a>
                                    </div>
                                </div>
                            </div>
                        @endif


                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="wsus__pro_det_description">
                        <ul class="nav nav-pills mb-3" id="pills-tab3" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-home-tab7" data-bs-toggle="pill"
                                    data-bs-target="#pills-home22" type="button" role="tab" aria-controls="pills-home"
                                    aria-selected="true">{{__('Description')}}</button>
                            </li>
                            @if ($product->is_specification == 1)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-profile-tab7" data-bs-toggle="pill"
                                    data-bs-target="#pills-profile22" type="button" role="tab"
                                    aria-controls="pills-profile" aria-selected="false">{{__('Specification')}}</button>
                            </li>
                            @endif

                        </ul>
                        <div class="tab-content" id="pills-tabContent4">
                            <div class="tab-pane fade  show active " id="pills-home22" role="tabpanel"
                                aria-labelledby="pills-home-tab7">
                                <div class="row">
                                    <div class="col-12">
                                        {!! $product->long_description !!}
                                    </div>


                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-profile22" role="tabpanel"
                                aria-labelledby="pills-profile-tab7">
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 mb-4 mb-lg-0">
                                        <div class="wsus__pro_det_info">
                                            <h4>{{__('Additional Information')}}</h4>
                                            @foreach ($product->specifications as $specification)
                                            <p><span>{{ $specification->key->key }}</span> <span>{{ $specification->specification }}</span></p>
                                            @endforeach
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        PRODUCT DETAILS END
    ==============================-->


<script>
    (function($) {
        "use strict";
        $(document).ready(function () {
            $(".productVariant").on("change",function(){
                calculateProductPrice();
            })

            $(".decrementProduct").on("click", function(){
                let qty = $(".product_qty").val();
                if(qty > 1){
                    qty = qty - 1;
                    $(".product_qty").val(qty);
                    calculateProductPrice();
                }
            })

            $(".incrementProduct").on("click", function(){
                let qty = $(".product_qty").val();
                qty = qty*1 + 1*1;
                $(".product_qty").val(qty);
                calculateProductPrice();
            })

            //start insert new cart item
            $("#shoppingCartForm").on("submit", function(e){
                e.preventDefault();
                $.ajax({
                    type: 'get',
                    data: $('#shoppingCartForm').serialize(),
                    url: "{{ route('add-to-cart') }}",
                    success: function (response) {
                        if(response.status == 0){
                            toastr.error(response.message)
                        }
                        if(response.status == 1){
                            toastr.success(response.message)
                            $.ajax({
                                type: 'get',
                                url: "{{ route('load-sidebar-cart') }}",
                                success: function (response) {
                                   $("#load_sidebar_cart").html(response)
                                   $.ajax({
                                        type: 'get',
                                        url: "{{ route('get-cart-qty') }}",
                                        success: function (response) {
                                            $("#cartQty").text(response.qty);
                                        },
                                    });
                                },
                            });
                        }
                    },
                    error: function(response) {

                    }
                });
            })
            //start insert new cart item

            // buy now item
            $("#buyNowBtn").on("click", function(){
                $.ajax({
                    type: 'get',
                    data: $('#shoppingCartForm').serialize(),
                    url: "{{ route('add-to-cart') }}",
                    success: function (response) {
                        if(response.status == 0){
                            toastr.error(response.message)
                        }
                        if(response.status == 1){
                            window.location.href = "{{ route('cart') }}";
                            toastr.success(response.message)
                            $.ajax({
                                type: 'get',
                                url: "{{ route('load-sidebar-cart') }}",
                                success: function (response) {
                                   $("#load_sidebar_cart").html(response)
                                   $.ajax({
                                        type: 'get',
                                        url: "{{ route('get-cart-qty') }}",
                                        success: function (response) {
                                            $("#cartQty").text(response.qty);
                                        },
                                    });
                                },
                            });
                        }
                    },
                    error: function(response) {

                    }
                });
            })

            $("#reviewFormId").on('submit', function(e){
                e.preventDefault();


                $.ajax({
                    type: 'post',
                    data: $('#reviewFormId').serialize(),
                    url: "{{ route('user.store-product-review') }}",
                    success: function (response) {
                        if(response.status == 1){
                            toastr.success(response.message)
                            $("#reviewFormId").trigger("reset");
                        }
                        if(response.status == 0){
                            toastr.error(response.message)
                            $("#reviewFormId").trigger("reset");
                        }
                    },
                    error: function(response) {
                        if(response.responseJSON.errors.rating)toastr.error(response.responseJSON.errors.rating[0])
                        if(response.responseJSON.errors.review)toastr.error(response.responseJSON.errors.review[0])

                    }
                });
            })

        });
    })(jQuery);

    function calculateProductPrice(){
        $.ajax({
            type: 'get',
            data: $('#shoppingCartForm').serialize(),
            url: "{{ route('calculate-product-price') }}",
            success: function (response) {
                let qty = $(".product_qty").val();
                let price = response.productPrice * qty;
                price = price.toFixed(2);
                $("#product_price").text(price);
                $("#mainProductPrice").text(price);
            },
            error: function(err) {
                alert('error')
            }
        });
    }

    function productReview(rating){
        $(".product_rat").each(function(){
            var product_rat = $(this).data('rating')
            if(product_rat > rating){
                $(this).removeClass('fas fa-star').addClass('fal fa-star');
            }else{
                $(this).removeClass('fal fa-star').addClass('fas fa-star');
            }
        })
        $("#product_rating").val(rating);
    }

</script>
@endsection
