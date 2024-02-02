@extends('layout')

@section('public-content')
    <!--============================
        BANNER PART START
    ==============================-->

    @if ($sliderVisibility->status == 1)
        <section id="wsus__banner">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 d-none d-lg-block">
                        <ul class="wsus_menu_cat_item">
                            @foreach ($productCategories as $productCategory)
                                @if ($productCategory->subCategories->count() == 0)
                                    <li><a href="{{ route('product',['category' => $productCategory->slug]) }}"><i
                                                class="{{ $productCategory->icon }}"></i> {{ $productCategory->name }}
                                        </a></li>
                                @else
                                    <li><a class="wsus__droap_arrow"
                                           href="{{ route('product',['category' => $productCategory->slug]) }}"><i
                                                class="{{ $productCategory->icon }}"></i> {{ $productCategory->name }}
                                        </a>
                                        <ul class="wsus_menu_cat_droapdown">
                                            @foreach ($productCategory->subCategories as $subCategory)
                                                <li>
                                                    <a href="{{ route('product',['sub_category' => $subCategory->slug]) }}">{{ $subCategory->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-xl-9 col-lg-9">
                        <div class="wsus__banner_content">
                            <div class="row banner_slider">
                                @foreach ($sliders->take($sliderVisibility->qty) as $slider)
                                    <div class="col-xl-12">
                                        <div class="wsus__single_slider"
                                             style="background: url({{ asset($slider->image) }});">
                                            <div class="wsus__single_slider_text">
                                                <h1>{!! nl2br($slider->title) !!}</h1>
                                                <h6>{!! nl2br($slider->description) !!}</h6>
                                                <a class="common_btn" href="{{ $slider->link }}">{{__('shop now')}}</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!--============================
        BANNER PART END
    ==============================-->

    <!--============================
        BRAND SLIDER START
    ==============================-->
    @php
        $brandVisibility = $visibilities->where('id',2)->first();
    @endphp
    @if ($brandVisibility->status == 1)
        <section id="wsus__brand_sleder">
            <div class="container">
                <div class="brand_border">
                    <div class="row brand_slider">
                        @foreach ($brands->take($brandVisibility->qty) as $brand)
                            <div class="col-xl-2">
                                <div class="wsus__brand_logo">
                                    <a href="{{ route('product',['brand' => $brand->slug]) }}"><img
                                            src="{{ asset($brand->logo) }}" alt="brand" class="img-fluid w-100"></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!--============================
        BRAND SLIDER END
    ==============================-->

    <!--============================
        SINGLE BANNER START
    ==============================-->
    @php
        $bannerVisibility = $visibilities->where('id',5)->first();
    @endphp
    @if ($bannerVisibility->status == 1)
        <section id="wsus__single_banner">
            <div class="container">
                <div class="row">
                    @php
                        $bannerOne = $banners->where('id',3)->first();
                        $bannerTwo = $banners->where('id',4)->first();
                    @endphp
                    <div class="col-xl-6 col-lg-6">
                        <div class="wsus__single_banner_content">
                            <div class="wsus__single_banner_img">
                                <img src="{{ asset($bannerOne->image) }}" alt="banner" class="img-fluid w-100">
                            </div>
                            <div class="wsus__single_banner_text">
                                <h6>{{ $bannerOne->description }}</h6>
                                <h3>{{ $bannerOne->title }}</h3>
                                <a class="shop_btn" href="{{ $bannerOne->link }}">{{__('shop now')}}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6">
                        <div class="wsus__single_banner_content">
                            <div class="wsus__single_banner_img">
                                <img src="{{ asset($bannerTwo->image) }}" alt="banner" class="img-fluid w-100">
                            </div>
                            <div class="wsus__single_banner_text">
                                <h6>{{ $bannerTwo->description }}</h6>
                                <h3>{{ $bannerTwo->title }}</h3>
                                <a class="shop_btn" href="{{ $bannerTwo->link }}">{{__('shop now')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!--============================
        SINGLE BANNER END
    ==============================-->


    <!--============================
           HOT DEALS START
    ==============================-->
    <section id="wsus__hot_deals">
        <div class="container">
            @php
                $flashDealVisibility = $visibilities->where('id',6)->first();
                $productIds = [];
                $productYears = [];
                $productMonths = [];
                $productDays = [];

            @endphp
            <script>
                var productIds = <?= json_encode($productIds) ?>;
                var productYears = <?= json_encode($productYears) ?>;
                var productMonths = <?= json_encode($productMonths) ?>;
                var productDays = <?= json_encode($productDays) ?>;
            </script>
            @if ($flashDealVisibility->status == 1)
                <div class="row">
                    <div class="col-xl-12">
                        <div class="wsus__section_header">
                            <h3>{{__('Flash Deal')}}</h3>
                        </div>
                    </div>
                </div>
                <div class="row hot_deals_slider">
                    @foreach ($flashDealProducts->take($flashDealVisibility->qty) as $flashDealProduct)
                        <div class="col-xl-6 col-lg-6">
                            <div class="wsus__hot_deals_offer">
                                <div class="wsus__hot_deals_img">
                                    <img src="{{ $flashDealProduct->thumb_image }}" alt="mobile"
                                         class="img-fluid w-100">
                                    <div class="simply-countdown flash-deal-product-{{ $flashDealProduct->id }}"></div>
                                </div>
                                <div class="wsus__hot_deals_text">
                                    <a class="wsus__hot_title"
                                       href="{{ route('product-detail', $flashDealProduct->slug) }}">{{ $flashDealProduct->short_name }}</a>

                                    @php
                                        $variantPrice = 0;
                                        $variants = $flashDealProduct->variants->where('status', 1);
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

                                        $totalPrice = $flashDealProduct->price;
                                        if($flashDealProduct->offer_price != null){
                                            $offerPrice = $flashDealProduct->offer_price;
                                            $offer = $totalPrice - $offerPrice;
                                            $percentage = ($offer * 100) / $totalPrice;
                                            $percentage = round($percentage);
                                        }
                                    @endphp


                                    @if ($flashDealProduct->offer_price == null)
                                        <p class="wsus__hot_deals_proce">{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $totalPrice + $variantPrice) }}</p>
                                    @else
                                        <p class="wsus__hot_deals_proce">{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $flashDealProduct->offer_price + $variantPrice) }}
                                            <del>{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $totalPrice) }}</del>
                                        </p>
                                    @endif

                                    <P class="wsus__details">
                                        {{ $flashDealProduct->short_description }}
                                    </P>
                                    <ul>
                                        <li><a class="add_cart"
                                               onclick="addToCartMainProduct('{{ $flashDealProduct->id }}')"
                                               href="javascript:;">{{__('add to cart')}}</a></li>
                                        <li><a href="javascript:;"
                                               onclick="addToWishlist('{{ $flashDealProduct->id }}')"><i
                                                    class="far fa-heart"></i></a></li>
                                        <li><a href="javascript:;"
                                               onclick="addToCompare('{{ $flashDealProduct->id }}')"><i
                                                    class="far fa-random"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @foreach ($flashDealProducts->take($flashDealVisibility->qty) as $flashDealProduct)
                    <section class="product_popup_modal">
                        <div class="modal fade" id="productModalView-{{ $flashDealProduct->id }}" tabindex="-1"
                             aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"><i
                                                class="far fa-times"></i></button>
                                        <div class="row">
                                            <div class="col-xl-6 col-12 col-sm-10 col-md-8 col-lg-6 m-auto display">
                                                <div class="wsus__quick_view_img">
                                                    @if ($flashDealProduct->video_link)
                                                        @php
                                                            $video_id=explode("=",$flashDealProduct->video_link);
                                                        @endphp
                                                        <a class="venobox wsus__pro_det_video" data-autoplay="true"
                                                           data-vbtype="video"
                                                           href="https://youtu.be/{{ $video_id[1] }}">
                                                            <i class="fas fa-play"></i>
                                                        </a>
                                                    @endif

                                                    <div class="row modal_slider">
                                                        @foreach ($flashDealProduct->gallery as $image)
                                                            <div class="col-xl-12">
                                                                <div class="modal_slider_img">
                                                                    <img src="{{ asset($image->image) }}" alt="product"
                                                                         class="img-fluid w-100">
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-12 col-sm-12 col-md-12 col-lg-6">
                                                <div class="wsus__pro_details_text">
                                                    <a class="title"
                                                       href="{{ route('product-detail', $flashDealProduct->slug) }}">{{ $flashDealProduct->name }}</a>

                                                    @if ($flashDealProduct->qty == 0)
                                                        <p class="wsus__stock_area"><span
                                                                class="in_stock">{{__('Out of Stock')}}</span></p>
                                                    @else
                                                        <p class="wsus__stock_area"><span class="in_stock">{{__('In stock')}}
                                                                @if ($setting->show_product_qty == 1)
                                                                    </span> ({{ $flashDealProduct->qty }} {{__('item')}}
                                                            )
                                                            @endif
                                                        </p>
                                                    @endif

                                                    @php
                                                        $variantPrice = 0;
                                                        $variants = $flashDealProduct->variants->where('status', 1);
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

                                                        $totalPrice = $flashDealProduct->price;
                                                        if($flashDealProduct->offer_price != null){
                                                            $offerPrice = $flashDealProduct->offer_price;
                                                            $offer = $totalPrice - $offerPrice;
                                                            $percentage = ($offer * 100) / $totalPrice;
                                                            $percentage = round($percentage);
                                                        }


                                                    @endphp


                                                    @if ($flashDealProduct->offer_price == null)
                                                        <h4>{{ $currencySetting->currency_icon }}<span
                                                                id="mainProductModalPrice-{{ $flashDealProduct->id }}">{{ sprintf("%.2f", $totalPrice + $variantPrice) }}</span>
                                                        </h4>
                                                    @else
                                                        <h4>{{ $currencySetting->currency_icon }}<span
                                                                id="mainProductModalPrice-{{ $flashDealProduct->id }}">{{ sprintf("%.2f", $flashDealProduct->offer_price + $variantPrice) }}</span>
                                                            <del>{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $totalPrice) }}</del>
                                                        </h4>
                                                    @endif



                                                    @php
                                                        $productPrice = 0;
                                                        if ($flashDealProduct->offer_price == null) {
                                                            $productPrice = $totalPrice + $variantPrice;
                                                        }else {
                                                            $productPrice = $flashDealProduct->offer_price + $variantPrice;
                                                        }

                                                    @endphp
                                                    <form id="productModalFormId-{{ $flashDealProduct->id }}">
                                                        <div class="wsus__quentity">
                                                            <h5>{{__('quantity') }} :</h5>
                                                            <div class="modal_btn">
                                                                <button
                                                                    onclick="productModalDecrement('{{ $flashDealProduct->id }}')"
                                                                    type="button" class="btn btn-danger btn-sm">-
                                                                </button>
                                                                <input id="productModalQty-{{ $flashDealProduct->id }}"
                                                                       name="quantity" readonly class="form-control"
                                                                       type="text" min="1" max="100" value="1"/>
                                                                <button
                                                                    onclick="productModalIncrement('{{ $flashDealProduct->id }}')"
                                                                    type="button" class="btn btn-success btn-sm">+
                                                                </button>
                                                            </div>
                                                            <h3 class="d-none">{{ $currencySetting->currency_icon }}
                                                                <span
                                                                    id="productModalPrice-{{ $flashDealProduct->id }}">{{ sprintf("%.2f",$productPrice) }}</span>
                                                            </h3>

                                                            <input type="hidden" name="product_id"
                                                                   value="{{ $flashDealProduct->id }}">
                                                            <input type="hidden" name="image"
                                                                   value="{{ $flashDealProduct->thumb_image }}">
                                                            <input type="hidden" name="slug"
                                                                   value="{{ $flashDealProduct->slug }}">

                                                        </div>
                                                        @php
                                                            $productVariants = App\Models\ProductVariant::where(['status' => 1, 'product_id'=> $flashDealProduct->id])->get();
                                                        @endphp
                                                        @if ($productVariants->count() != 0)
                                                            <div class="wsus__selectbox">
                                                                <div class="row">
                                                                    @foreach ($productVariants as $productVariant)
                                                                        @php
                                                                            $items = App\Models\ProductVariantItem::orderBy('is_default','desc')->where(['product_variant_id' => $productVariant->id, 'product_id' => $flashDealProduct->id])->get();
                                                                        @endphp
                                                                        @if ($items->count() != 0)
                                                                            <div class="col-xl-6 col-sm-6 mb-3">
                                                                                <h5 class="mb-2">{{ $productVariant->name }}
                                                                                    :</h5>

                                                                                <input type="hidden" name="variants[]"
                                                                                       value="{{ $productVariant->id }}">
                                                                                <input type="hidden"
                                                                                       name="variantNames[]"
                                                                                       value="{{ $productVariant->name }}">

                                                                                <select
                                                                                    class="select_2 productModalVariant"
                                                                                    name="items[]"
                                                                                    data-product="{{ $flashDealProduct->id }}">
                                                                                    @foreach ($items as $item)
                                                                                        <option
                                                                                            value="{{ $item->id }}">{{ $item->name }}</option>
                                                                                    @endforeach
                                                                                </select>

                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <ul class="wsus__button_area">
                                                            <li>
                                                                <button type="button"
                                                                        onclick="addToCartInProductModal('{{ $flashDealProduct->id }}')"
                                                                        class="add_cart">{{__('add to cart')}}</button>
                                                            </li>
                                                            <li><a class="buy_now" href="javascript:;"
                                                                   onclick="addToBuyNow('{{ $flashDealProduct->id }}')">{{__('buy now')}}</a>
                                                            </li>
                                                            <li><a href="javascript:;"
                                                                   onclick="addToWishlist('{{ $flashDealProduct->id }}')"><i
                                                                        class="fal fa-heart"></i></a></li>
                                                            <li><a href="javascript:;"
                                                                   onclick="addToCompare('{{ $flashDealProduct->id }}')"><i
                                                                        class="far fa-random"></i></a></li>
                                                        </ul>
                                                    </form>
                                                    @if ($flashDealProduct->sku)
                                                        <p class="brand_model">
                                                            <span>{{__('Model')}} :</span> {{ $flashDealProduct->sku }}
                                                        </p>
                                                    @endif

                                                    <p class="brand_model"><span>{{__('Brand')}} :</span> <a
                                                            href="{{ route('product',['brand' => $flashDealProduct->brand->slug]) }}">{{ $flashDealProduct->brand->name }}</a>
                                                    </p>
                                                    <p class="brand_model"><span>{{__('Category')}} :</span> <a
                                                            href="{{ route('product',['category' => $flashDealProduct->category->slug]) }}">{{ $flashDealProduct->category->name }}</a>
                                                    </p>
                                                    <div class="wsus__pro_det_share d-none">
                                                        <h5>{{__('share')}} :</h5>
                                                        <ul class="d-flex">
                                                            <li><a class="facebook"
                                                                   href="https://www.facebook.com/sharer/sharer.php?u={{ route('product-detail', $flashDealProduct->slug) }}&t={{ $flashDealProduct->name }}"><i
                                                                        class="fab fa-facebook-f"></i></a></li>
                                                            <li><a class="twitter"
                                                                   href="https://twitter.com/share?text={{ $flashDealProduct->name }}&url={{ route('product-detail', $flashDealProduct->slug) }}"><i
                                                                        class="fab fa-twitter"></i></a></li>
                                                            <li><a class="linkedin"
                                                                   href="https://www.linkedin.com/shareArticle?mini=true&url={{ route('product-detail', $flashDealProduct->slug) }}&title={{ $flashDealProduct->name }}"><i
                                                                        class="fab fa-linkedin"></i></a></li>
                                                            <li><a class="pinterest"
                                                                   href="https://www.pinterest.com/pin/create/button/?description={{ $flashDealProduct->name }}&media=&url={{ route('product-detail', $flashDealProduct->slug) }}"><i
                                                                        class="fab fa-pinterest-p"></i></a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                @endforeach
            @endif


            @php
                $productHighlightVisibility = $visibilities->where('id',7)->first();
            @endphp
            @if ($productHighlightVisibility->status == 1)
                <div class="row">
                    <div class="wsus__hot_large_item">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="wsus__section_header justify-content-md-center">
                                    <div class="monthly_top_filter2">
                                        <button class="active click_featured_product"
                                                data-filter="._featured">{{__('Featured')}}</button>
                                        <button data-filter="._best">{{__('Best Product')}}</button>
                                        <button data-filter="._top">{{__('Top Rated')}}</button>
                                        <button data-filter="._new">{{__('New')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row grid2">
                            @foreach ($featuredProducts as $featuredProduct)

                                @php
                                    $variantPrice = 0;
                                    $variants = $featuredProduct->variants->where('status', 1);
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



                                    $totalPrice = $featuredProduct->price;
                                    if($featuredProduct->offer_price != null){
                                        $offerPrice = $featuredProduct->offer_price;
                                        $offer = $totalPrice - $offerPrice;
                                        $percentage = ($offer * 100) / $totalPrice;
                                        $percentage = round($percentage);
                                    }
                                @endphp
                                <div class="col-xl-3 col-sm-6 col-md-6 col-lg-4 _featured">
                                    <div class="wsus__product_item">
                                        @if ($featuredProduct->new_product == 1)
                                            <span class="wsus__new">{{__('New')}}</span>
                                        @elseif ($featuredProduct->is_featured == 1)
                                            <span class="wsus__new">{{__('Featured')}}</span>
                                        @elseif ($featuredProduct->is_top == 1)
                                            <span class="wsus__new">{{__('Top')}}</span>
                                        @elseif ($featuredProduct->is_best == 1)
                                            <span class="wsus__new">{{__('Best')}}</span>
                                        @endif


                                        @if ($featuredProduct->offer_price != null)
                                            <span class="wsus__minus">-{{ $percentage }}%</span>
                                        @endif

                                        <a class="wsus__pro_link"
                                           href="{{ route('product-detail', $featuredProduct->slug) }}">
                                            <img src="{{ asset($featuredProduct->thumb_image) }}" alt="product"
                                                 class="img-fluid w-100 img_1"/>
                                            <img src="{{ asset($featuredProduct->thumb_image) }}" alt="product"
                                                 class="img-fluid w-100 img_2"/>
                                        </a>


                                        <ul class="wsus__single_pro_icon">
                                            <li><a data-bs-toggle="modal"
                                                   data-bs-target="#productModalView-{{ $featuredProduct->id }}"><i
                                                        class="fal fa-eye"></i></a></li>
                                            <li><a href="javascript:;"
                                                   onclick="addToWishlist('{{ $featuredProduct->id }}')"><i
                                                        class="far fa-heart"></i></a></li>
                                            <li><a href="javascript:;"
                                                   onclick="addToCompare('{{ $featuredProduct->id }}')"><i
                                                        class="far fa-random"></i></a></li>
                                        </ul>
                                        <div class="wsus__product_details">
                                            <a class="wsus__category"
                                               href="{{ route('product',['category' => $featuredProduct->category->slug]) }}">{{ $featuredProduct->category->name }} </a>


                                            <a class="wsus__pro_name"
                                               href="{{ route('product-detail', $featuredProduct->slug) }}">{{ $featuredProduct->short_name }}</a>

                                            @if ($featuredProduct->offer_price == null)
                                                <p class="wsus__price">{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $totalPrice + $variantPrice) }}</p>
                                            @else
                                                <p class="wsus__price">{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $featuredProduct->offer_price + $variantPrice) }}
                                                    <del>{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $totalPrice) }}</del>
                                                </p>
                                            @endif
                                            <a class="add_cart"
                                               onclick="addToCartMainProduct('{{ $featuredProduct->id }}')"
                                               href="javascript:;">{{__('add to cart')}}</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @foreach ($bestProducts as $bestProduct)

                                @php
                                    $variantPrice = 0;
                                    $variants = $bestProduct->variants->where('status', 1);
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

                                    $totalPrice = $bestProduct->price;
                                    if($bestProduct->offer_price != null){
                                        $offerPrice = $bestProduct->offer_price;
                                        $offer = $totalPrice - $offerPrice;
                                        $percentage = ($offer * 100) / $totalPrice;
                                        $percentage = round($percentage);
                                    }
                                @endphp
                                <div class="col-xl-3 col-sm-6 col-md-6 col-lg-4 _best">
                                    <div class="wsus__product_item">
                                        @if ($bestProduct->new_product == 1)
                                            <span class="wsus__new">{{__('New')}}</span>
                                        @elseif ($bestProduct->is_featured == 1)
                                            <span class="wsus__new">{{__('Featured')}}</span>
                                        @elseif ($bestProduct->is_top == 1)
                                            <span class="wsus__new">{{__('Top')}}</span>
                                        @elseif ($bestProduct->is_best == 1)
                                            <span class="wsus__new">{{__('Best')}}</span>
                                        @endif

                                        @if ($bestProduct->offer_price != null)
                                            <span class="wsus__minus">-{{ $percentage }}%</span>
                                        @endif
                                        <a class="wsus__pro_link"
                                           href="{{ route('product-detail', $bestProduct->slug) }}">
                                            <img src="{{ asset($bestProduct->thumb_image) }}" alt="product"
                                                 class="img-fluid w-100 img_1"/>
                                            <img src="{{ asset($bestProduct->thumb_image) }}" alt="product"
                                                 class="img-fluid w-100 img_2"/>
                                        </a>

                                        @php
                                            $variantPrice = 0;
                                            $variants = $bestProduct->variants->where('status', 1);
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

                                            $totalPrice = $bestProduct->price;
                                            if($bestProduct->offer_price != null){
                                                $offerPrice = $bestProduct->offer_price;
                                                $offer = $totalPrice - $offerPrice;
                                                $percentage = ($offer * 100) / $totalPrice;
                                                $percentage = round($percentage);
                                            }
                                        @endphp

                                        <ul class="wsus__single_pro_icon">
                                            <li><a data-bs-toggle="modal"
                                                   data-bs-target="#productModalView-{{ $bestProduct->id }}"><i
                                                        class="fal fa-eye"></i></a></li>
                                            <li><a href="javascript:;"
                                                   onclick="addToWishlist('{{ $bestProduct->id }}')"><i
                                                        class="far fa-heart"></i></a></li>
                                            <li><a href="javascript:;" onclick="addToCompare('{{ $bestProduct->id }}')"><i
                                                        class="far fa-random"></i></a></li>
                                        </ul>
                                        <div class="wsus__product_details">
                                            <a class="wsus__category"
                                               href="{{ route('product',['category' => $bestProduct->category->slug]) }}">{{ $bestProduct->category->name }} </a>

                                            <a class="wsus__pro_name"
                                               href="{{ route('product-detail', $bestProduct->slug) }}">{{ $bestProduct->short_name }}</a>

                                            @if ($bestProduct->offer_price == null)
                                                <p class="wsus__price">{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $totalPrice + $variantPrice) }}</p>
                                            @else
                                                <p class="wsus__price">{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $bestProduct->offer_price + $variantPrice) }}
                                                    <del>{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $totalPrice) }}</del>
                                                </p>
                                            @endif
                                            <a class="add_cart" onclick="addToCartMainProduct('{{ $bestProduct->id }}')"
                                               href="javascript:;">{{__('add to cart')}}</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @foreach ($topProducts as $topProduct)
                                @php
                                    $variantPrice = 0;
                                    $variants = $topProduct->variants->where('status', 1);
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



                                    $totalPrice = $topProduct->price;
                                    if($topProduct->offer_price != null){
                                        $offerPrice = $topProduct->offer_price;
                                        $offer = $totalPrice - $offerPrice;
                                        $percentage = ($offer * 100) / $totalPrice;
                                        $percentage = round($percentage);
                                    }
                                @endphp

                                <div class="col-xl-3 col-sm-6 col-md-6 col-lg-4 _top">
                                    <div class="wsus__product_item ">
                                        @if ($topProduct->new_product == 1)
                                            <span class="wsus__new">{{__('New')}}</span>
                                        @elseif ($topProduct->is_featured == 1)
                                            <span class="wsus__new">{{__('Featured')}}</span>
                                        @elseif ($topProduct->is_top == 1)
                                            <span class="wsus__new">{{__('Top')}}</span>
                                        @elseif ($topProduct->is_best == 1)
                                            <span class="wsus__new">{{__('Best')}}</span>
                                        @endif


                                        @if ($topProduct->offer_price != null)
                                            <span class="wsus__minus">-{{ $percentage }}%</span>
                                        @endif
                                        <a class="wsus__pro_link"
                                           href="{{ route('product-detail', $topProduct->slug) }}">
                                            <img src="{{ asset($topProduct->thumb_image) }}" alt="product"
                                                 class="img-fluid w-100 img_1"/>
                                            <img src="{{ asset($topProduct->thumb_image) }}" alt="product"
                                                 class="img-fluid w-100 img_2"/>
                                        </a>


                                        <ul class="wsus__single_pro_icon">
                                            <li><a data-bs-toggle="modal"
                                                   data-bs-target="#productModalView-{{ $topProduct->id }}"><i
                                                        class="fal fa-eye"></i></a></li>
                                            <li><a href="javascript:;" onclick="addToWishlist('{{ $topProduct->id }}')"><i
                                                        class="far fa-heart"></i></a></li>
                                            <li><a href="javascript:;"
                                                   onclick="addToCompare('{{ $topProduct->id }}')"><i
                                                        class="far fa-random"></i></a>
                                            </li>
                                        </ul>
                                        <div class="wsus__product_details">
                                            <a class="wsus__category"
                                               href="{{ route('product',['category' => $topProduct->category->slug]) }}">{{ $topProduct->category->name }} </a>

                                            <a class="wsus__pro_name"
                                               href="{{ route('product-detail', $topProduct->slug) }}">{{ $topProduct->short_name }}</a>

                                            @if ($topProduct->offer_price == null)
                                                <p class="wsus__price">{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $totalPrice + $variantPrice) }}</p>
                                            @else
                                                <p class="wsus__price">{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $topProduct->offer_price + $variantPrice) }}
                                                    <del>{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $totalPrice) }}</del>
                                                </p>
                                            @endif
                                            <a class="add_cart" onclick="addToCartMainProduct('{{ $topProduct->id }}')"
                                               href="javascript:;">{{__('add to cart')}}</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @foreach ($newProducts as $newProduct)
                                @php
                                    $variantPrice = 0;
                                    $variants = $newProduct->variants->where('status', 1);
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

                                    $totalPrice = $newProduct->price;
                                    if($newProduct->offer_price != null){
                                        $offerPrice = $newProduct->offer_price;
                                        $offer = $totalPrice - $offerPrice;
                                        $percentage = ($offer * 100) / $totalPrice;
                                        $percentage = round($percentage);
                                    }
                                @endphp
                                <div class="col-xl-3 col-sm-6 col-md-6 col-lg-4 _new">
                                    <div class="wsus__product_item wsus__after">
                                        @if ($newProduct->new_product == 1)
                                            <span class="wsus__new">{{__('New')}}</span>
                                        @elseif ($newProduct->is_featured == 1)
                                            <span class="wsus__new">{{__('Featured')}}</span>
                                        @elseif ($newProduct->is_top == 1)
                                            <span class="wsus__new">{{__('Top')}}</span>
                                        @elseif ($newProduct->is_best == 1)
                                            <span class="wsus__new">{{__('Best')}}</span>
                                        @endif


                                        @if ($newProduct->offer_price != null)
                                            <span class="wsus__minus">-{{ $percentage }}%</span>
                                        @endif
                                        <a class="wsus__pro_link"
                                           href="{{ route('product-detail', $newProduct->slug) }}">
                                            <img src="{{ asset($newProduct->thumb_image) }}" alt="product"
                                                 class="img-fluid w-100 img_1"/>
                                            <img src="{{ asset($newProduct->thumb_image) }}" alt="product"
                                                 class="img-fluid w-100 img_2"/>
                                        </a>

                                        <ul class="wsus__single_pro_icon">
                                            <li><a data-bs-toggle="modal"
                                                   data-bs-target="#productModalView-{{ $newProduct->id }}"><i
                                                        class="fal fa-eye"></i></a></li>
                                            <li><a href="javascript:;" onclick="addToWishlist('{{ $newProduct->id }}')"><i
                                                        class="far fa-heart"></i></a></li>
                                            <li><a href="javascript:;"
                                                   onclick="addToCompare('{{ $newProduct->id }}')"><i
                                                        class="far fa-random"></i></a>
                                            </li>
                                        </ul>
                                        <div class="wsus__product_details">
                                            <a class="wsus__category"
                                               href="{{ route('product',['category' => $newProduct->category->slug]) }}">{{ $newProduct->category->name }} </a>

                                            <a class="wsus__pro_name"
                                               href="{{ route('product-detail', $newProduct->slug) }}">{{ $newProduct->short_name }}</a>

                                            @if ($newProduct->offer_price == null)
                                                <p class="wsus__price">{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $totalPrice + $variantPrice) }}</p>
                                            @else
                                                <p class="wsus__price">{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $newProduct->offer_price + $variantPrice) }}
                                                    <del>{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $totalPrice) }}</del>
                                                </p>
                                            @endif
                                            <a class="add_cart" onclick="addToCartMainProduct('{{ $newProduct->id }}')"
                                               href="javascript:;">{{__('add to cart')}}</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @foreach ($featuredProducts as $featuredProduct)
                            <section class="product_popup_modal">
                                <div class="modal fade" id="productModalView-{{ $featuredProduct->id }}" tabindex="-1"
                                     aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"><i
                                                        class="far fa-times"></i></button>
                                                <div class="row">
                                                    <div
                                                        class="col-xl-6 col-12 col-sm-10 col-md-8 col-lg-6 m-auto display">
                                                        <div class="wsus__quick_view_img">
                                                            @if ($featuredProduct->video_link)
                                                                @php
                                                                    $video_id=explode("=",$featuredProduct->video_link);
                                                                @endphp
                                                                <a class="venobox wsus__pro_det_video"
                                                                   data-autoplay="true" data-vbtype="video"
                                                                   href="https://youtu.be/{{ $video_id[1] }}">
                                                                    <i class="fas fa-play"></i>
                                                                </a>
                                                            @endif

                                                            <div class="row modal_slider">
                                                                @foreach ($featuredProduct->gallery as $image)
                                                                    <div class="col-xl-12">
                                                                        <div class="modal_slider_img">
                                                                            <img src="{{ asset($image->image) }}"
                                                                                 alt="product" class="img-fluid w-100">
                                                                        </div>
                                                                    </div>
                                                                @endforeach

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6 col-12 col-sm-12 col-md-12 col-lg-6">
                                                        <div class="wsus__pro_details_text">
                                                            <a class="title"
                                                               href="{{ route('product-detail', $featuredProduct->slug) }}">{{ $featuredProduct->name }}</a>

                                                            @if ($featuredProduct->qty == 0)
                                                                <p class="wsus__stock_area"><span
                                                                        class="in_stock">{{__('Out of Stock')}}</span>
                                                                </p>
                                                            @else
                                                                <p class="wsus__stock_area"><span class="in_stock">{{__('In stock')}}
                                                                        @if ($setting->show_product_qty == 1)
                                                                    </span> ({{ $featuredProduct->qty }} {{__('item')}})
                                                                    @endif
                                                                </p>
                                                            @endif


                                                            @php
                                                                $variantPrice = 0;
                                                                $variants = $featuredProduct->variants->where('status', 1);
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
                                                                $totalPrice = $featuredProduct->price;
                                                                if($featuredProduct->offer_price != null){
                                                                    $offerPrice = $featuredProduct->offer_price;
                                                                    $offer = $totalPrice - $offerPrice;
                                                                    $percentage = ($offer * 100) / $totalPrice;
                                                                    $percentage = round($percentage);
                                                                }


                                                            @endphp


                                                            @if ($featuredProduct->offer_price == null)
                                                                <h4>{{ $currencySetting->currency_icon }}<span
                                                                        id="mainProductModalPrice-{{ $featuredProduct->id }}">{{ sprintf("%.2f", $totalPrice + $variantPrice) }}</span>
                                                                </h4>
                                                            @else
                                                                <h4>{{ $currencySetting->currency_icon }}<span
                                                                        id="mainProductModalPrice-{{ $featuredProduct->id }}">{{ sprintf("%.2f", $featuredProduct->offer_price + $variantPrice) }}</span>
                                                                    <del>{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $totalPrice) }}</del>
                                                                </h4>
                                                            @endif

                                                            @php
                                                                $productPrice = 0;

                                                                if ($featuredProduct->offer_price == null) {
                                                                    $productPrice = $totalPrice + $variantPrice;
                                                                }else {
                                                                    $productPrice = $featuredProduct->offer_price + $variantPrice;
                                                                }
                                                            @endphp
                                                            <form id="productModalFormId-{{ $featuredProduct->id }}">
                                                                <div class="wsus__quentity">
                                                                    <h5>{{__('quantity') }} :</h5>
                                                                    <div class="modal_btn">
                                                                        <button
                                                                            onclick="productModalDecrement('{{ $featuredProduct->id }}')"
                                                                            type="button" class="btn btn-danger btn-sm">
                                                                            -
                                                                        </button>
                                                                        <input
                                                                            id="productModalQty-{{ $featuredProduct->id }}"
                                                                            name="quantity" readonly
                                                                            class="form-control" type="text" min="1"
                                                                            max="100" value="1"/>
                                                                        <button
                                                                            onclick="productModalIncrement('{{ $featuredProduct->id }}')"
                                                                            type="button"
                                                                            class="btn btn-success btn-sm">+
                                                                        </button>
                                                                    </div>
                                                                    <h3 class="d-none">{{ $currencySetting->currency_icon }}
                                                                        <span
                                                                            id="productModalPrice-{{ $featuredProduct->id }}">{{ sprintf("%.2f",$productPrice) }}</span>
                                                                    </h3>

                                                                    <input type="hidden" name="product_id"
                                                                           value="{{ $featuredProduct->id }}">
                                                                    <input type="hidden" name="image"
                                                                           value="{{ $featuredProduct->thumb_image }}">
                                                                    <input type="hidden" name="slug"
                                                                           value="{{ $featuredProduct->slug }}">

                                                                </div>
                                                                @php
                                                                    $productVariants = App\Models\ProductVariant::where(['status' => 1, 'product_id'=> $featuredProduct->id])->get();
                                                                @endphp
                                                                @if ($productVariants->count() != 0)
                                                                    <div class="wsus__selectbox">
                                                                        <div class="row">
                                                                            @foreach ($productVariants as $productVariant)
                                                                                @php
                                                                                    $items = App\Models\ProductVariantItem::orderBy('is_default','desc')->where(['product_variant_id' => $productVariant->id, 'product_id' => $featuredProduct->id])->get();
                                                                                @endphp
                                                                                @if ($items->count() != 0)
                                                                                    <div class="col-xl-6 col-sm-6 mb-3">
                                                                                        <h5 class="mb-2">{{ $productVariant->name }}
                                                                                            :</h5>

                                                                                        <input type="hidden"
                                                                                               name="variants[]"
                                                                                               value="{{ $productVariant->id }}">
                                                                                        <input type="hidden"
                                                                                               name="variantNames[]"
                                                                                               value="{{ $productVariant->name }}">

                                                                                        <select
                                                                                            class="select_2 productModalVariant"
                                                                                            name="items[]"
                                                                                            data-product="{{ $featuredProduct->id }}">
                                                                                            @foreach ($items as $item)
                                                                                                <option
                                                                                                    value="{{ $item->id }}">{{ $item->name }}</option>
                                                                                            @endforeach
                                                                                        </select>

                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                <ul class="wsus__button_area">
                                                                    <li>
                                                                        <button type="button"
                                                                                onclick="addToCartInProductModal('{{ $featuredProduct->id }}')"
                                                                                class="add_cart">{{__('add to cart')}}</button>
                                                                    </li>
                                                                    <li><a class="buy_now" href="javascript:;"
                                                                           onclick="addToBuyNow('{{ $featuredProduct->id }}')">{{__('buy now')}}</a>
                                                                    </li>
                                                                    <li><a href="javascript:;"
                                                                           onclick="addToWishlist('{{ $featuredProduct->id }}')"><i
                                                                                class="fal fa-heart"></i></a></li>
                                                                    <li><a href="javascript:;"
                                                                           onclick="addToCompare('{{ $featuredProduct->id }}')"><i
                                                                                class="far fa-random"></i></a></li>
                                                                </ul>
                                                            </form>
                                                            @if ($featuredProduct->sku)
                                                                <p class="brand_model">
                                                                    <span>{{__('Model')}} :</span> {{ $featuredProduct->sku }}
                                                                </p>
                                                            @endif

                                                            <p class="brand_model"><span>{{__('Brand')}} :</span> <a
                                                                    href="{{ route('product',['brand' => $featuredProduct->brand->slug]) }}">{{ $featuredProduct->brand->name }}</a>
                                                            </p>
                                                            <p class="brand_model"><span>{{__('Category')}} :</span> <a
                                                                    href="{{ route('product',['category' => $featuredProduct->category->slug]) }}">{{ $featuredProduct->category->name }}</a>
                                                            </p>
                                                            <div class="wsus__pro_det_share d-none">
                                                                <h5>{{__('share')}} :</h5>
                                                                <ul class="d-flex">
                                                                    <li><a class="facebook"
                                                                           href="https://www.facebook.com/sharer/sharer.php?u={{ route('product-detail', $featuredProduct->slug) }}&t={{ $featuredProduct->name }}"><i
                                                                                class="fab fa-facebook-f"></i></a></li>
                                                                    <li><a class="twitter"
                                                                           href="https://twitter.com/share?text={{ $featuredProduct->name }}&url={{ route('product-detail', $featuredProduct->slug) }}"><i
                                                                                class="fab fa-twitter"></i></a></li>
                                                                    <li><a class="linkedin"
                                                                           href="https://www.linkedin.com/shareArticle?mini=true&url={{ route('product-detail', $featuredProduct->slug) }}&title={{ $featuredProduct->name }}"><i
                                                                                class="fab fa-linkedin"></i></a></li>
                                                                    <li><a class="pinterest"
                                                                           href="https://www.pinterest.com/pin/create/button/?description={{ $featuredProduct->name }}&media=&url={{ route('product-detail', $featuredProduct->slug) }}"><i
                                                                                class="fab fa-pinterest-p"></i></a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @endforeach

                        @foreach ($bestProducts as $bestProduct)
                            <section class="product_popup_modal">
                                <div class="modal fade" id="productModalView-{{ $bestProduct->id }}" tabindex="-1"
                                     aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"><i
                                                        class="far fa-times"></i></button>
                                                <div class="row">
                                                    <div
                                                        class="col-xl-6 col-12 col-sm-10 col-md-8 col-lg-6 m-auto display">
                                                        <div class="wsus__quick_view_img">
                                                            @if ($bestProduct->video_link)
                                                                @php
                                                                    $video_id=explode("=",$bestProduct->video_link);
                                                                @endphp
                                                                <a class="venobox wsus__pro_det_video"
                                                                   data-autoplay="true" data-vbtype="video"
                                                                   href="https://youtu.be/{{ $video_id[1] }}">
                                                                    <i class="fas fa-play"></i>
                                                                </a>
                                                            @endif

                                                            <div class="row modal_slider">
                                                                @foreach ($bestProduct->gallery as $image)
                                                                    <div class="col-xl-12">
                                                                        <div class="modal_slider_img">
                                                                            <img src="{{ asset($image->image) }}"
                                                                                 alt="product" class="img-fluid w-100">
                                                                        </div>
                                                                    </div>
                                                                @endforeach

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6 col-12 col-sm-12 col-md-12 col-lg-6">
                                                        <div class="wsus__pro_details_text">
                                                            <a class="title"
                                                               href="{{ route('product-detail', $bestProduct->slug) }}">{{ $bestProduct->name }}</a>

                                                            @if ($bestProduct->qty == 0)
                                                                <p class="wsus__stock_area"><span
                                                                        class="in_stock">{{__('Out of Stock')}}</span>
                                                                </p>
                                                            @else
                                                                <p class="wsus__stock_area"><span class="in_stock">{{__('In stock')}}
                                                                        @if ($setting->show_product_qty == 1)
                                                                    </span> ({{ $bestProduct->qty }} {{__('item')}})
                                                                    @endif
                                                                </p>
                                                            @endif

                                                            @php
                                                                $variantPrice = 0;
                                                                $variants = $bestProduct->variants->where('status', 1);
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

                                                                $totalPrice = $bestProduct->price;
                                                                if($bestProduct->offer_price != null){
                                                                    $offerPrice = $bestProduct->offer_price;
                                                                    $offer = $totalPrice - $offerPrice;
                                                                    $percentage = ($offer * 100) / $totalPrice;
                                                                    $percentage = round($percentage);
                                                                }


                                                            @endphp
                                                            @if ($bestProduct->offer_price == null)
                                                                <h4>{{ $currencySetting->currency_icon }}<span
                                                                        id="mainProductModalPrice-{{ $bestProduct->id }}">{{ sprintf("%.2f", $totalPrice + $variantPrice) }}</span>
                                                                </h4>
                                                            @else
                                                                <h4>{{ $currencySetting->currency_icon }}<span
                                                                        id="mainProductModalPrice-{{ $bestProduct->id }}">{{ sprintf("%.2f", $bestProduct->offer_price + $variantPrice) }}</span>
                                                                    <del>{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $totalPrice) }}</del>
                                                                </h4>
                                                            @endif

                                                            @php
                                                                $productPrice = 0;

                                                                if ($bestProduct->offer_price == null) {
                                                                    $productPrice = $totalPrice + $variantPrice;
                                                                }else {
                                                                    $productPrice = $bestProduct->offer_price + $variantPrice;
                                                                }

                                                            @endphp
                                                            <form id="productModalFormId-{{ $bestProduct->id }}">
                                                                <div class="wsus__quentity">
                                                                    <h5>{{__('quantity') }} :</h5>
                                                                    <div class="modal_btn">
                                                                        <button
                                                                            onclick="productModalDecrement('{{ $bestProduct->id }}')"
                                                                            type="button" class="btn btn-danger btn-sm">
                                                                            -
                                                                        </button>
                                                                        <input
                                                                            id="productModalQty-{{ $bestProduct->id }}"
                                                                            name="quantity" readonly
                                                                            class="form-control" type="text" min="1"
                                                                            max="100" value="1"/>
                                                                        <button
                                                                            onclick="productModalIncrement('{{ $bestProduct->id }}')"
                                                                            type="button"
                                                                            class="btn btn-success btn-sm">+
                                                                        </button>
                                                                    </div>
                                                                    <h3 class="d-none">{{ $currencySetting->currency_icon }}
                                                                        <span
                                                                            id="productModalPrice-{{ $bestProduct->id }}">{{ sprintf("%.2f",$productPrice) }}</span>
                                                                    </h3>

                                                                    <input type="hidden" name="product_id"
                                                                           value="{{ $bestProduct->id }}">
                                                                    <input type="hidden" name="image"
                                                                           value="{{ $bestProduct->thumb_image }}">
                                                                    <input type="hidden" name="slug"
                                                                           value="{{ $bestProduct->slug }}">

                                                                </div>
                                                                @php
                                                                    $productVariants = App\Models\ProductVariant::where(['status' => 1, 'product_id'=> $bestProduct->id])->get();
                                                                @endphp
                                                                @if ($productVariants->count() != 0)
                                                                    <div class="wsus__selectbox">
                                                                        <div class="row">
                                                                            @foreach ($productVariants as $productVariant)
                                                                                @php
                                                                                    $items = App\Models\ProductVariantItem::orderBy('is_default','desc')->where(['product_variant_id' => $productVariant->id, 'product_id' => $bestProduct->id])->get();
                                                                                @endphp
                                                                                @if ($items->count() != 0)
                                                                                    <div class="col-xl-6 col-sm-6 mb-3">
                                                                                        <h5 class="mb-2">{{ $productVariant->name }}
                                                                                            :</h5>

                                                                                        <input type="hidden"
                                                                                               name="variants[]"
                                                                                               value="{{ $productVariant->id }}">
                                                                                        <input type="hidden"
                                                                                               name="variantNames[]"
                                                                                               value="{{ $productVariant->name }}">

                                                                                        <select
                                                                                            class="select_2 productModalVariant"
                                                                                            name="items[]"
                                                                                            data-product="{{ $bestProduct->id }}">
                                                                                            @foreach ($items as $item)
                                                                                                <option
                                                                                                    value="{{ $item->id }}">{{ $item->name }}</option>
                                                                                            @endforeach
                                                                                        </select>

                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                <ul class="wsus__button_area">
                                                                    <li>
                                                                        <button type="button"
                                                                                onclick="addToCartInProductModal('{{ $bestProduct->id }}')"
                                                                                class="add_cart">{{__('add to cart')}}</button>
                                                                    </li>
                                                                    <li><a class="buy_now" href="javascript:;"
                                                                           onclick="addToBuyNow('{{ $bestProduct->id }}')">{{__('buy now')}}</a>
                                                                    </li>
                                                                    <li><a href="javascript:;"
                                                                           onclick="addToWishlist('{{ $bestProduct->id }}')"><i
                                                                                class="fal fa-heart"></i></a></li>
                                                                    <li><a href="javascript:;"
                                                                           onclick="addToCompare('{{ $bestProduct->id }}')"><i
                                                                                class="far fa-random"></i></a></li>
                                                                </ul>
                                                            </form>
                                                            @if ($bestProduct->sku)
                                                                <p class="brand_model">
                                                                    <span>{{__('Model')}} :</span> {{ $bestProduct->sku }}
                                                                </p>
                                                            @endif

                                                            <p class="brand_model"><span>{{__('Brand')}} :</span> <a
                                                                    href="{{ route('product',['brand' => $bestProduct->brand->slug]) }}">{{ $bestProduct->brand->name }}</a>
                                                            </p>
                                                            <p class="brand_model"><span>{{__('Category')}} :</span> <a
                                                                    href="{{ route('product',['category' => $bestProduct->category->slug]) }}">{{ $bestProduct->category->name }}</a>
                                                            </p>
                                                            <div class="wsus__pro_det_share d-none">
                                                                <h5>{{__('share')}} :</h5>
                                                                <ul class="d-flex">
                                                                    <li><a class="facebook"
                                                                           href="https://www.facebook.com/sharer/sharer.php?u={{ route('product-detail', $bestProduct->slug) }}&t={{ $bestProduct->name }}"><i
                                                                                class="fab fa-facebook-f"></i></a></li>
                                                                    <li><a class="twitter"
                                                                           href="https://twitter.com/share?text={{ $bestProduct->name }}&url={{ route('product-detail', $bestProduct->slug) }}"><i
                                                                                class="fab fa-twitter"></i></a></li>
                                                                    <li><a class="linkedin"
                                                                           href="https://www.linkedin.com/shareArticle?mini=true&url={{ route('product-detail', $bestProduct->slug) }}&title={{ $bestProduct->name }}"><i
                                                                                class="fab fa-linkedin"></i></a></li>
                                                                    <li><a class="pinterest"
                                                                           href="https://www.pinterest.com/pin/create/button/?description={{ $bestProduct->name }}&media=&url={{ route('product-detail', $bestProduct->slug) }}"><i
                                                                                class="fab fa-pinterest-p"></i></a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @endforeach

                        @foreach ($topProducts as $topProduct)
                            <section class="product_popup_modal">
                                <div class="modal fade" id="productModalView-{{ $topProduct->id }}" tabindex="-1"
                                     aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"><i
                                                        class="far fa-times"></i></button>
                                                <div class="row">
                                                    <div
                                                        class="col-xl-6 col-12 col-sm-10 col-md-8 col-lg-6 m-auto display">
                                                        <div class="wsus__quick_view_img">
                                                            @if ($topProduct->video_link)
                                                                @php
                                                                    $video_id=explode("=",$topProduct->video_link);
                                                                @endphp
                                                                <a class="venobox wsus__pro_det_video"
                                                                   data-autoplay="true" data-vbtype="video"
                                                                   href="https://youtu.be/{{ $video_id[1] }}">
                                                                    <i class="fas fa-play"></i>
                                                                </a>
                                                            @endif

                                                            <div class="row modal_slider">
                                                                @foreach ($topProduct->gallery as $image)
                                                                    <div class="col-xl-12">
                                                                        <div class="modal_slider_img">
                                                                            <img src="{{ asset($image->image) }}"
                                                                                 alt="product" class="img-fluid w-100">
                                                                        </div>
                                                                    </div>
                                                                @endforeach

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6 col-12 col-sm-12 col-md-12 col-lg-6">
                                                        <div class="wsus__pro_details_text">
                                                            <a class="title"
                                                               href="{{ route('product-detail', $topProduct->slug) }}">{{ $topProduct->name }}</a>

                                                            @if ($topProduct->qty == 0)
                                                                <p class="wsus__stock_area"><span
                                                                        class="in_stock">{{__('Out of Stock')}}</span>
                                                                </p>
                                                            @else
                                                                <p class="wsus__stock_area"><span class="in_stock">{{__('In stock')}}
                                                                        @if ($setting->show_product_qty == 1)
                                                                    </span> ({{ $topProduct->qty }} {{__('item')}})
                                                                    @endif
                                                                </p>
                                                            @endif

                                                            @php
                                                                $variantPrice = 0;
                                                                $variants = $topProduct->variants->where('status', 1);
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

                                                                $totalPrice = $topProduct->price;
                                                                if($topProduct->offer_price != null){
                                                                    $offerPrice = $topProduct->offer_price;
                                                                    $offer = $totalPrice - $offerPrice;
                                                                    $percentage = ($offer * 100) / $totalPrice;
                                                                    $percentage = round($percentage);
                                                                }


                                                            @endphp


                                                            @if ($topProduct->offer_price == null)
                                                                <h4>{{ $currencySetting->currency_icon }}<span
                                                                        id="mainProductModalPrice-{{ $topProduct->id }}">{{ sprintf("%.2f", $totalPrice + $variantPrice) }}</span>
                                                                </h4>
                                                            @else
                                                                <h4>{{ $currencySetting->currency_icon }}<span
                                                                        id="mainProductModalPrice-{{ $topProduct->id }}">{{ sprintf("%.2f", $topProduct->offer_price + $variantPrice) }}</span>
                                                                    <del>{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $totalPrice) }}</del>
                                                                </h4>
                                                            @endif

                                                            @php
                                                                $productPrice = 0;
                                                                    if ($topProduct->offer_price == null) {
                                                                        $productPrice = $totalPrice + $variantPrice;
                                                                    }else {
                                                                        $productPrice = $topProduct->offer_price + $variantPrice;
                                                                    }
                                                            @endphp
                                                            <form id="productModalFormId-{{ $topProduct->id }}">
                                                                <div class="wsus__quentity">
                                                                    <h5>{{__('quantity') }} :</h5>
                                                                    <div class="modal_btn">
                                                                        <button
                                                                            onclick="productModalDecrement('{{ $topProduct->id }}')"
                                                                            type="button" class="btn btn-danger btn-sm">
                                                                            -
                                                                        </button>
                                                                        <input
                                                                            id="productModalQty-{{ $topProduct->id }}"
                                                                            name="quantity" readonly
                                                                            class="form-control" type="text" min="1"
                                                                            max="100" value="1"/>
                                                                        <button
                                                                            onclick="productModalIncrement('{{ $topProduct->id }}')"
                                                                            type="button"
                                                                            class="btn btn-success btn-sm">+
                                                                        </button>
                                                                    </div>
                                                                    <h3 class="d-none">{{ $currencySetting->currency_icon }}
                                                                        <span
                                                                            id="productModalPrice-{{ $topProduct->id }}">{{ sprintf("%.2f",$productPrice) }}</span>
                                                                    </h3>

                                                                    <input type="hidden" name="product_id"
                                                                           value="{{ $topProduct->id }}">
                                                                    <input type="hidden" name="image"
                                                                           value="{{ $topProduct->thumb_image }}">
                                                                    <input type="hidden" name="slug"
                                                                           value="{{ $topProduct->slug }}">

                                                                </div>
                                                                @php
                                                                    $productVariants = App\Models\ProductVariant::where(['status' => 1, 'product_id'=> $topProduct->id])->get();
                                                                @endphp
                                                                @if ($productVariants->count() != 0)
                                                                    <div class="wsus__selectbox">
                                                                        <div class="row">
                                                                            @foreach ($productVariants as $productVariant)
                                                                                @php
                                                                                    $items = App\Models\ProductVariantItem::orderBy('is_default','desc')->where(['product_variant_id' => $productVariant->id, 'product_id' => $topProduct->id])->get();
                                                                                @endphp
                                                                                @if ($items->count() != 0)
                                                                                    <div class="col-xl-6 col-sm-6 mb-3">
                                                                                        <h5 class="mb-2">{{ $productVariant->name }}
                                                                                            :</h5>

                                                                                        <input type="hidden"
                                                                                               name="variants[]"
                                                                                               value="{{ $productVariant->id }}">
                                                                                        <input type="hidden"
                                                                                               name="variantNames[]"
                                                                                               value="{{ $productVariant->name }}">

                                                                                        <select
                                                                                            class="select_2 productModalVariant"
                                                                                            name="items[]"
                                                                                            data-product="{{ $topProduct->id }}">
                                                                                            @foreach ($items as $item)
                                                                                                <option
                                                                                                    value="{{ $item->id }}">{{ $item->name }}</option>
                                                                                            @endforeach
                                                                                        </select>

                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                <ul class="wsus__button_area">
                                                                    <li>
                                                                        <button type="button"
                                                                                onclick="addToCartInProductModal('{{ $topProduct->id }}')"
                                                                                class="add_cart">{{__('add to cart')}}</button>
                                                                    </li>
                                                                    <li><a class="buy_now" href="javascript:;"
                                                                           onclick="addToBuyNow('{{ $topProduct->id }}')">{{__('buy now')}}</a>
                                                                    </li>
                                                                    <li><a href="javascript:;"
                                                                           onclick="addToWishlist('{{ $topProduct->id }}')"><i
                                                                                class="fal fa-heart"></i></a></li>
                                                                    <li><a href="javascript:;"
                                                                           onclick="addToCompare('{{ $topProduct->id }}')"><i
                                                                                class="far fa-random"></i></a></li>
                                                                </ul>
                                                            </form>
                                                            @if ($topProduct->sku)
                                                                <p class="brand_model">
                                                                    <span>{{__('Model')}} :</span> {{ $topProduct->sku }}
                                                                </p>
                                                            @endif

                                                            <p class="brand_model"><span>{{__('Brand')}} :</span> <a
                                                                    href="{{ route('product',['brand' => $topProduct->brand->slug]) }}">{{ $topProduct->brand->name }}</a>
                                                            </p>
                                                            <p class="brand_model"><span>{{__('Category')}} :</span> <a
                                                                    href="{{ route('product',['category' => $topProduct->category->slug]) }}">{{ $topProduct->category->name }}</a>
                                                            </p>
                                                            <div class="wsus__pro_det_share d-none">
                                                                <h5>{{__('share')}} :</h5>
                                                                <ul class="d-flex">
                                                                    <li><a class="facebook"
                                                                           href="https://www.facebook.com/sharer/sharer.php?u={{ route('product-detail', $topProduct->slug) }}&t={{ $topProduct->name }}"><i
                                                                                class="fab fa-facebook-f"></i></a></li>
                                                                    <li><a class="twitter"
                                                                           href="https://twitter.com/share?text={{ $topProduct->name }}&url={{ route('product-detail', $topProduct->slug) }}"><i
                                                                                class="fab fa-twitter"></i></a></li>
                                                                    <li><a class="linkedin"
                                                                           href="https://www.linkedin.com/shareArticle?mini=true&url={{ route('product-detail', $topProduct->slug) }}&title={{ $topProduct->name }}"><i
                                                                                class="fab fa-linkedin"></i></a></li>
                                                                    <li><a class="pinterest"
                                                                           href="https://www.pinterest.com/pin/create/button/?description={{ $topProduct->name }}&media=&url={{ route('product-detail', $topProduct->slug) }}"><i
                                                                                class="fab fa-pinterest-p"></i></a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @endforeach

                        @foreach ($newProducts as $newProduct)
                            <section class="product_popup_modal">
                                <div class="modal fade" id="productModalView-{{ $newProduct->id }}" tabindex="-1"
                                     aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"><i
                                                        class="far fa-times"></i></button>
                                                <div class="row">
                                                    <div
                                                        class="col-xl-6 col-12 col-sm-10 col-md-8 col-lg-6 m-auto display">
                                                        <div class="wsus__quick_view_img">
                                                            @if ($newProduct->video_link)
                                                                @php
                                                                    $video_id=explode("=",$newProduct->video_link);
                                                                @endphp
                                                                <a class="venobox wsus__pro_det_video"
                                                                   data-autoplay="true" data-vbtype="video"
                                                                   href="https://youtu.be/{{ $video_id[1] }}">
                                                                    <i class="fas fa-play"></i>
                                                                </a>
                                                            @endif

                                                            <div class="row modal_slider">
                                                                @foreach ($newProduct->gallery as $image)
                                                                    <div class="col-xl-12">
                                                                        <div class="modal_slider_img">
                                                                            <img src="{{ asset($image->image) }}"
                                                                                 alt="product" class="img-fluid w-100">
                                                                        </div>
                                                                    </div>
                                                                @endforeach

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6 col-12 col-sm-12 col-md-12 col-lg-6">
                                                        <div class="wsus__pro_details_text">
                                                            <a class="title"
                                                               href="{{ route('product-detail', $newProduct->slug) }}">{{ $newProduct->name }}</a>

                                                            @if ($newProduct->qty == 0)
                                                                <p class="wsus__stock_area"><span
                                                                        class="in_stock">{{__('Out of Stock')}}</span>
                                                                </p>
                                                            @else
                                                                <p class="wsus__stock_area"><span class="in_stock">{{__('In stock')}}
                                                                        @if ($setting->show_product_qty == 1)
                                                                    </span> ({{ $newProduct->qty }} {{__('item')}})
                                                                    @endif
                                                                </p>
                                                            @endif

                                                            @php
                                                                $variantPrice = 0;
                                                                $variants = $newProduct->variants->where('status', 1);
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

                                                                $totalPrice = $newProduct->price;
                                                                if($newProduct->offer_price != null){
                                                                    $offerPrice = $newProduct->offer_price;
                                                                    $offer = $totalPrice - $offerPrice;
                                                                    $percentage = ($offer * 100) / $totalPrice;
                                                                    $percentage = round($percentage);
                                                                }


                                                            @endphp
                                                            @if ($newProduct->offer_price == null)
                                                                <h4>{{ $currencySetting->currency_icon }}<span
                                                                        id="mainProductModalPrice-{{ $newProduct->id }}">{{ sprintf("%.2f", $totalPrice + $variantPrice) }}</span>
                                                                </h4>
                                                            @else
                                                                <h4>{{ $currencySetting->currency_icon }}<span
                                                                        id="mainProductModalPrice-{{ $newProduct->id }}">{{ sprintf("%.2f", $newProduct->offer_price + $variantPrice) }}</span>
                                                                    <del>{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $totalPrice) }}</del>
                                                                </h4>
                                                            @endif

                                                            @php
                                                                $productPrice = 0;

                                                                    if ($newProduct->offer_price == null) {
                                                                        $productPrice = $totalPrice + $variantPrice;
                                                                    }else {
                                                                        $productPrice = $newProduct->offer_price + $variantPrice;
                                                                    }

                                                            @endphp
                                                            <form id="productModalFormId-{{ $newProduct->id }}">
                                                                <div class="wsus__quentity">
                                                                    <h5>{{__('quantity') }} :</h5>
                                                                    <div class="modal_btn">
                                                                        <button
                                                                            onclick="productModalDecrement('{{ $newProduct->id }}')"
                                                                            type="button" class="btn btn-danger btn-sm">
                                                                            -
                                                                        </button>
                                                                        <input
                                                                            id="productModalQty-{{ $newProduct->id }}"
                                                                            name="quantity" readonly
                                                                            class="form-control" type="text" min="1"
                                                                            max="100" value="1"/>
                                                                        <button
                                                                            onclick="productModalIncrement('{{ $newProduct->id }}')"
                                                                            type="button"
                                                                            class="btn btn-success btn-sm">+
                                                                        </button>
                                                                    </div>
                                                                    <h3 class="d-none">{{ $currencySetting->currency_icon }}
                                                                        <span
                                                                            id="productModalPrice-{{ $newProduct->id }}">{{ sprintf("%.2f",$productPrice) }}</span>
                                                                    </h3>

                                                                    <input type="hidden" name="product_id"
                                                                           value="{{ $newProduct->id }}">
                                                                    <input type="hidden" name="image"
                                                                           value="{{ $newProduct->thumb_image }}">
                                                                    <input type="hidden" name="slug"
                                                                           value="{{ $newProduct->slug }}">

                                                                </div>
                                                                @php
                                                                    $productVariants = App\Models\ProductVariant::where(['status' => 1, 'product_id'=> $newProduct->id])->get();
                                                                @endphp
                                                                @if ($productVariants->count() != 0)
                                                                    <div class="wsus__selectbox">
                                                                        <div class="row">
                                                                            @foreach ($productVariants as $productVariant)
                                                                                @php
                                                                                    $items = App\Models\ProductVariantItem::orderBy('is_default','desc')->where(['product_variant_id' => $productVariant->id, 'product_id' => $newProduct->id])->get();
                                                                                @endphp
                                                                                @if ($items->count() != 0)
                                                                                    <div class="col-xl-6 col-sm-6 mb-3">
                                                                                        <h5 class="mb-2">{{ $productVariant->name }}
                                                                                            :</h5>

                                                                                        <input type="hidden"
                                                                                               name="variants[]"
                                                                                               value="{{ $productVariant->id }}">
                                                                                        <input type="hidden"
                                                                                               name="variantNames[]"
                                                                                               value="{{ $productVariant->name }}">

                                                                                        <select
                                                                                            class="select_2 productModalVariant"
                                                                                            name="items[]"
                                                                                            data-product="{{ $newProduct->id }}">
                                                                                            @foreach ($items as $item)
                                                                                                <option
                                                                                                    value="{{ $item->id }}">{{ $item->name }}</option>
                                                                                            @endforeach
                                                                                        </select>

                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                <ul class="wsus__button_area">
                                                                    <li>
                                                                        <button type="button"
                                                                                onclick="addToCartInProductModal('{{ $newProduct->id }}')"
                                                                                class="add_cart">{{__('add to cart')}}</button>
                                                                    </li>
                                                                    <li><a class="buy_now" href="javascript:;"
                                                                           onclick="addToBuyNow('{{ $newProduct->id }}')">{{__('buy now')}}</a>
                                                                    </li>
                                                                    <li><a href="javascript:;"
                                                                           onclick="addToWishlist('{{ $newProduct->id }}')"><i
                                                                                class="fal fa-heart"></i></a></li>
                                                                    <li><a href="javascript:;"
                                                                           onclick="addToCompare('{{ $newProduct->id }}')"><i
                                                                                class="far fa-random"></i></a></li>
                                                                </ul>
                                                            </form>
                                                            @if ($newProduct->sku)
                                                                <p class="brand_model">
                                                                    <span>{{__('Model')}} :</span> {{ $newProduct->sku }}
                                                                </p>
                                                            @endif

                                                            <p class="brand_model"><span>{{__('Brand')}} :</span> <a
                                                                    href="{{ route('product',['brand' => $newProduct->brand->slug]) }}">{{ $newProduct->brand->name }}</a>
                                                            </p>
                                                            <p class="brand_model"><span>{{__('Category')}} :</span> <a
                                                                    href="{{ route('product',['category' => $newProduct->category->slug]) }}">{{ $newProduct->category->name }}</a>
                                                            </p>
                                                            <div class="wsus__pro_det_share d-none">
                                                                <h5>{{__('share')}} :</h5>
                                                                <ul class="d-flex">
                                                                    <li><a class="facebook"
                                                                           href="https://www.facebook.com/sharer/sharer.php?u={{ route('product-detail', $newProduct->slug) }}&t={{ $newProduct->name }}"><i
                                                                                class="fab fa-facebook-f"></i></a></li>
                                                                    <li><a class="twitter"
                                                                           href="https://twitter.com/share?text={{ $newProduct->name }}&url={{ route('product-detail', $newProduct->slug) }}"><i
                                                                                class="fab fa-twitter"></i></a></li>
                                                                    <li><a class="linkedin"
                                                                           href="https://www.linkedin.com/shareArticle?mini=true&url={{ route('product-detail', $newProduct->slug) }}&title={{ $newProduct->name }}"><i
                                                                                class="fab fa-linkedin"></i></a></li>
                                                                    <li><a class="pinterest"
                                                                           href="https://www.pinterest.com/pin/create/button/?description={{ $newProduct->name }}&media=&url={{ route('product-detail', $newProduct->slug) }}"><i
                                                                                class="fab fa-pinterest-p"></i></a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @endforeach

                    </div>
                </div>
            @endif


            @php
                $bannerVisiblity = $visibilities->where('id',8)->first();
            @endphp
            @if ($bannerVisiblity->status == 1)
                <section id="wsus__single_banner">
                    <div class="">
                        <div class="row">
                            @php
                                $bannerOne = $banners->where('id',5)->first();
                                $bannerTwo = $banners->where('id',6)->first();
                            @endphp
                            <div class="col-xl-6 col-lg-6">
                                <div class="wsus__single_banner_content">
                                    <div class="wsus__single_banner_img">
                                        <img src="{{ $bannerOne->image }}" alt="banner" class="img-fluid w-100">
                                    </div>
                                    <div class="wsus__single_banner_text">
                                        <h6>{{ $bannerOne->description }}</h6>
                                        <h3>{{ $bannerOne->title }}</h3>
                                        <a class="shop_btn" href="{{ $bannerOne->link }}">{{__('shop now')}}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <div class="wsus__single_banner_content">
                                    <div class="wsus__single_banner_img">
                                        <img src="{{ $bannerTwo->image }}" alt="banner" class="img-fluid w-100">
                                    </div>
                                    <div class="wsus__single_banner_text">
                                        <h6>{{ $bannerTwo->description }}</h6>
                                        <h3>{{ $bannerTwo->title }}</h3>
                                        <a class="shop_btn" href="{{ $bannerTwo->link }}">{{__('shop now')}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif
        </div>
    </section>

    <!--============================
           HOT DEALS END
    ==============================-->



    <!--============================
        WEEKLY BEST ITEM START
    ==============================-->
    @php
        $threeColVisible = $visibilities->where('id',9)->first();
    @endphp
    @if ($threeColVisible->status == 1)
        <section id="wsus__weekly_best">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-sm-6 col-md-6 col-lg-4">
                        <div class="wsus__section_header">
                            @php
                                $firstCategory = $columnCategories->where('id',$threeColumnCategory->category_id_one)->first();
                            @endphp
                            <h3>{{ $firstCategory ? $firstCategory->name : '' }}</h3>
                        </div>
                        <div class="row weekly_best">
                            @foreach ($threeColumnFirstCategoryProducts as $threeColfirstCatProduct)
                                <div class="col-xl-12">
                                    <a class="wsus__hot_deals__single"
                                       href="{{ route('product-detail', $threeColfirstCatProduct->slug) }}">
                                        <div class="wsus__hot_deals__single_img">
                                            <img src="{{ asset($threeColfirstCatProduct->thumb_image) }}" alt="bag"
                                                 class="img-fluid w-100">
                                        </div>
                                        <div class="wsus__hot_deals__single_text">
                                            <h5>{{ $threeColfirstCatProduct->short_name }}</h5>

                                            @php
                                                $variantPrice = 0;
                                                $variants = $threeColfirstCatProduct->variants->where('status', 1);
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

                                                $totalPrice = $threeColfirstCatProduct->price;
                                                if($threeColfirstCatProduct->offer_price != null){
                                                    $offerPrice = $threeColfirstCatProduct->offer_price;
                                                    $offer = $totalPrice - $offerPrice;
                                                    $percentage = ($offer * 100) / $totalPrice;
                                                    $percentage = round($percentage);
                                                }
                                            @endphp


                                            @if ($threeColfirstCatProduct->offer_price == null)
                                                <p class="wsus__tk">{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $totalPrice + $variantPrice) }}</p>
                                            @else
                                                <p class="wsus__tk">{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $threeColfirstCatProduct->offer_price + $variantPrice) }}
                                                    <del>{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $totalPrice) }}</del>
                                                </p>
                                            @endif

                                        </div>
                                    </a>
                                </div>
                            @endforeach

                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-6 col-md-6 col-lg-4">
                        <div class="wsus__section_header">
                            @php
                                $secondCategory = $columnCategories->where('id',$threeColumnCategory->category_id_two)->first();
                            @endphp
                            <h3>{{ $secondCategory ? $secondCategory->name : '' }}</h3>
                        </div>
                        <div class="row weekly_best">
                            @foreach ($threeColumnSecondCategoryProducts as $threeColsecondCatProduct)
                                <div class="col-xl-12">
                                    <a class="wsus__hot_deals__single"
                                       href="{{ route('product-detail', $threeColsecondCatProduct->slug) }}">
                                        <div class="wsus__hot_deals__single_img">
                                            <img src="{{ asset($threeColsecondCatProduct->thumb_image) }}" alt="bag"
                                                 class="img-fluid w-100">
                                        </div>
                                        <div class="wsus__hot_deals__single_text">
                                            <h5>{{ $threeColsecondCatProduct->short_name }}</h5>
                                            @php
                                                $variantPrice = 0;
                                                $variants = $threeColsecondCatProduct->variants->where('status', 1);
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

                                                $totalPrice = $threeColsecondCatProduct->price;
                                                if($threeColsecondCatProduct->offer_price != null){
                                                    $offerPrice = $threeColsecondCatProduct->offer_price;
                                                    $offer = $totalPrice - $offerPrice;
                                                    $percentage = ($offer * 100) / $totalPrice;
                                                    $percentage = round($percentage);
                                                }
                                            @endphp


                                            @if ($threeColsecondCatProduct->offer_price == null)
                                                <p class="wsus__tk">{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $totalPrice + $variantPrice) }}</p>
                                            @else
                                                <p class="wsus__tk">{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $threeColsecondCatProduct->offer_price + $variantPrice) }}
                                                    <del>{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $totalPrice) }}</del>
                                                </p>
                                            @endif

                                        </div>
                                    </a>
                                </div>
                            @endforeach


                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-6 col-md-6 col-lg-4">
                        <div class="wsus__section_header">
                            @php
                                $threeCategory = $columnCategories->where('id',$threeColumnCategory->category_id_three)->first();
                            @endphp
                            <h3>{{ $threeCategory ? $threeCategory->name : ''}}</h3>
                        </div>
                        <div class="row weekly_best">
                            @foreach ($threeColumnThirdCategoryProducts as $threeColCatProduct)
                                <div class="col-xl-12">
                                    <a class="wsus__hot_deals__single"
                                       href="{{ route('product-detail', $threeColCatProduct->slug) }}">
                                        <div class="wsus__hot_deals__single_img">
                                            <img src="{{ asset($threeColCatProduct->thumb_image) }}" alt="bag"
                                                 class="img-fluid w-100">
                                        </div>
                                        <div class="wsus__hot_deals__single_text">
                                            <h5>{{ $threeColCatProduct->short_name }}</h5>

                                            @php
                                                $variantPrice = 0;
                                                $variants = $threeColCatProduct->variants->where('status', 1);
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


                                                $totalPrice = $threeColCatProduct->price;
                                                if($threeColCatProduct->offer_price != null){
                                                    $offerPrice = $threeColCatProduct->offer_price;
                                                    $offer = $totalPrice - $offerPrice;
                                                    $percentage = ($offer * 100) / $totalPrice;
                                                    $percentage = round($percentage);
                                                }
                                            @endphp


                                            @if ($threeColCatProduct->offer_price == null)
                                                <p class="wsus__tk">{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $totalPrice + $variantPrice) }}</p>
                                            @else
                                                <p class="wsus__tk">{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $threeColCatProduct->offer_price + $variantPrice) }}
                                                    <del>{{ $currencySetting->currency_icon }}{{ sprintf("%.2f", $totalPrice) }}</del>
                                                </p>
                                            @endif

                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!--============================
        WEEKLY BEST ITEM END
    ==============================-->

    <!--============================
    LARGE BANNER  START
==============================-->

    @php
        $bannerVisibility = $visibilities->where('id',10)->first();
    @endphp
    @if ($bannerVisibility->status == 1)
        <section id="wsus__single_banner">
            <div class="container">
                <div class="row">
                    @php
                        $bannerOne = $banners->where('id',7)->first();
                        $bannerTwo = $banners->where('id',8)->first();
                    @endphp
                    <div class="col-xl-6 col-lg-6">
                        <div class="wsus__single_banner_content">
                            <div class="wsus__single_banner_img">
                                <img src="{{ asset($bannerOne->image) }}" alt="banner" class="img-fluid w-100">
                            </div>
                            <div class="wsus__single_banner_text">
                                <h6>{{ $bannerOne->description }}</h6>
                                <h3>{{ $bannerOne->title }}</h3>
                                <a class="shop_btn" href="{{ $bannerOne->link }}">{{__('shop now')}}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6">
                        <div class="wsus__single_banner_content">
                            <div class="wsus__single_banner_img">
                                <img src="{{ asset($bannerTwo->image) }}" alt="banner" class="img-fluid w-100">
                            </div>
                            <div class="wsus__single_banner_text">
                                <h6>{{ $bannerTwo->description }}</h6>
                                <h3>{{ $bannerTwo->title }}</h3>
                                <a class="shop_btn" href="{{ $bannerTwo->link }}">{{__('shop now')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!--============================
        LARGE BANNER  END
    ==============================-->

    <!--============================
      HOME SERVOCES START
    ==============================-->
    @php
        $serviceVisibility = $visibilities->where('id',11)->first();
    @endphp
    @if ($serviceVisibility->status == 1)
        <section id="wsus__home_services">
            <div class="container">
                <div class="row">
                    @foreach ($services as $service)
                        <div class="col-xl-3 col-sm-6 col-lg-3">
                            <div class="wsus__home_services_single">
                                <i class="{{ $service->icon }}"></i>
                                <h5>{{ $service->title }}</h5>
                                <p>{{ $service->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    <!--============================
        HOME SERVOCES END
    ==============================-->


    <!--============================
        HOME BLOGS START
    ==============================-->
    @php
        $blogVisibilty = $visibilities->where('id',12)->first();
    @endphp
    @if ($blogVisibilty->status == 1)
        <section id="wsus__blogs" class="home_blogs">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="wsus__section_header">
                            <h3>{{__('recent blogs')}}</h3>
                            <a class="see_btn" href="{{ route('blog') }}">{{__('see more')}} <i
                                    class="fas fa-caret-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="row home_blog_slider">
                    @php
                        $colorId=1;
                    @endphp
                    @foreach ($blogs as $index => $blog)
                        @php
                            if($index %4 ==0){
                                $colorId=1;
                            }

                            $color="";
                            if($colorId==1){
                                $color="blue";
                            }else if($colorId==2){
                                $color="red";
                            }else if($colorId==3){
                                $color="orange";
                            }else if($colorId==4){
                                $color="green";
                            }
                        @endphp
                        <div class="col-xl-4">
                            <div class="wsus__single_blog">
                                <a class="wsus__blog_img" href="{{ route('blog-detail', $blog->slug) }}">
                                    <img src="{{ asset($blog->image) }}" alt="blog" class="img-fluid w-100">
                                </a>
                                <a class="blog_top {{ $color }}"
                                   href="{{ route('blog-by-category',$blog->category->slug) }}">{{ $blog->category->name }}</a>
                                <div class="wsus__blog_text">
                                    <div class="wsus__blog_text_center">
                                        <a href="{{ route('blog-detail', $blog->slug) }}">{{ $blog->title }}</a>
                                        <p class="date">
                                            <span>{{ $blog->created_at->format('d F, Y') }}</span> {{__('Hosted by')}} {{ $blog->admin->name }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php
                            $colorId ++;
                        @endphp
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    <!--============================
        HOME BLOGS END
    ==============================-->

@endsection
