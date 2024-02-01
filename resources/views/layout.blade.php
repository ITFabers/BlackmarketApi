@php
    $setting = App\Models\Setting::first();
    $productCategories = App\Models\Category::where(['status' => 1])->get();
    $megaMenuBanner = App\Models\BannerImage::find(1);
    $modalProducts = App\Models\Product::all();
    $currencySetting = App\Models\Setting::first();
    $googleAnalytic = App\Models\GoogleAnalytic::first();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densityDpi=device-dpi" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    @yield('title')
    @yield('meta')

    <link rel="icon" type="image/png" href="{{ asset($setting->favicon) }}">
    <link rel="stylesheet" href="{{ asset('user/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/jquery.nice-number.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/jquery.calendar.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/add_row_custon.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/mobile_menu.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/jquery.exzoom.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/multiple-image-video.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/ranger_style.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/jquery.classycountdown.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/venobox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('toastr/toastr.min.css') }}">

    @if ($setting->text_direction == 'rtl')
    <link rel="stylesheet" href="{{ asset('user/css/rtl.css') }}">
    @endif

    <link rel="stylesheet" href="{{ asset('user/css/style.css') }}">


    <link rel="stylesheet" href="{{ asset('user/css/responsive.css') }}">

    <link rel="stylesheet" href="{{ asset('user/css/dev.css') }}">

    <!--jquery library js-->
    <script src="{{ asset('user/js/jquery-3.6.0.min.js') }}"></script>


    @if ($googleAnalytic->status == 1)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $googleAnalytic->analytic_id }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $googleAnalytic->analytic_id }}');
    </script>
    @endif



    <script>
        var end_year = '';
        var end_month = '';
        var end_date = '';
        var capmaign_time = '';
        var campaign_end_year = ''
        var campaign_end_month = ''
        var campaign_end_date = ''
        var campaign_hour = ''
        var campaign_min = ''
        var campaign_sec = ''
        var productIds = [];
        var productYears = [];
        var productMonths = [];
        var productDays = [];
    </script>

    @include('theme_style_css')
</head>

<body>

    <!--============================
        TOP BAR START
    ==============================-->
    <section id="wsus__topbar">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-sm-5 col-md-6 col-lg-6 d-none d-lg-block">
                    <ul class="wsus__topbar_left">
                        @if ($menus->where('id',22)->first()->status == 1)
                        <li><a href="callto:{{ $setting->topbar_phone }}"><i class="fal fa-phone-square-alt"></i> {{ $setting->topbar_phone }}</a></li>
                        @endif
                        <li><a href="mailto:{{ $setting->topbar_email }}"><i class="fal fa-envelope"></i> {{ $setting->topbar_email }}</a></li>
                    </ul>
                </div>
                <div class="col-xl-6 col-sm-12 col-md-8 col-lg-6">
                    <ul class="wsus__topbar_right">
                        @if ($menus->where('id',9)->first()->status == 1)
                            <li><a href="{{ route('contact-us') }}"><i class="fal fa-address-card"></i> {{__('Contact Us')}}</a></li>
                        @endif
                        @if ($menus->where('id',17)->first()->status == 1)
                            <li><a href="{{ route('user.dashboard') }}"><i class="fal fa-user-circle"></i> {{__('My Account')}}</a></li>
                        @endif
                        @if ($menus->where('id',18)->first()->status == 1)
                            @guest
                            <li><a href="{{ route('login') }}"><i class="fal fa-user-alt"></i> {{__('Login / Register')}}</a></li>
                            @else
                            <li><a href="{{ route('user.logout') }}"><i class="far fa-sign-out-alt"></i> {{__('Logout')}}</a></li>
                            @endguest
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        TOP BAR END
    ==============================-->


    <!--============================
        HEADER START
    ==============================-->
    <header>
        <div class="container">
            <div class="row">
                <div class="col-2 d-lg-none">
                    <div class="wsus__mobile_menu_area">
                        <span class="wsus__mobile_menu_icon"><i class="fal fa-bars"></i></span>
                    </div>
                </div>
                <div class="col-xl-2 col-7 col-lg-2">
                    <div class="wsus_logo_area">
                        <a class="wsus__header_logo" href="{{ route('home') }}">
                            <img src="{{ asset($setting->logo) }}" alt="logo" class="img-fluid w-100">
                        </a>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-5 d-none d-lg-block">
                    @if ($menus->where('id',25)->first()->status == 1)
                    <div class="wsus__search">
                        <form action="{{ route('product') }}">
                            <div class="wsus__category_search">
                                <select class="select_2" name="category">
                                    <option value="">{{__('All Category')}}</option>
                                    @if (request()->has('category'))
                                        @foreach ($productCategories as $productCategory)
                                            <option {{ request()->get('category') == $productCategory->slug ? 'selected' : ''  }} value="{{ $productCategory->slug }}">{{ $productCategory->name }}</option>
                                        @endforeach
                                    @else
                                        @foreach ($productCategories as $productCategory)
                                            <option value="{{ $productCategory->slug }}">{{ $productCategory->name }}</option>
                                        @endforeach
                                    @endif

                                </select>
                            </div>
                            <input type="text" placeholder="{{__('Search...')}}" name="search" value="{{ request()->has('search') ? request()->get('search') : '' }}">
                            <button type="submit"><i class="far fa-search"></i></button>
                        </form>
                    </div>
                    @endif
                </div>
                <div class="col-xl-4 col-3 col-lg-5">
                    <div class="wsus__call_icon_area">
                        @if ($menus->where('id',23)->first()->status == 1)
                        <div class="wsus__call_area">
                            <div class="wsus__call">
                                <i class="far fa-phone-alt"></i>
                            </div>
                            <div class="wsus__call_text">
                                <p>{{__('Call Us Now')}} :</p>
                                <a href="callto:+{{ $setting->menu_phone }}">{{ $setting->menu_phone }}</a>
                            </div>
                        </div>
                        @endif
                        <ul class="wsus__icon_area">
                            @if ($menus->where('id',21)->first()->status == 1)
                            <li><a href="{{ route('user.wishlist') }}"><i class="far fa-heart"></i>
                                @auth
                                    @php
                                        $user = Auth::guard('web')->user();
                                        $wishlist = App\Models\Wishlist::where('user_id',$user->id)->count();
                                    @endphp
                                    <span id="wishlistQty">{{ $wishlist }}</span>
                                @endauth

                            </a></li>
                            @endif
                            @if ($menus->where('id',20)->first()->status == 1)
                            <li><a href="{{ route('compare') }}"><i class="far fa-random"></i>
                                <span id="compareQty">{{ Cart::instance('compare')->count() }}</span>

                            </a></li>
                            @endif

                            @if ($menus->where('id',19)->first()->status == 1)
                            <li><a class="wsus__cart_icon" href="javascript:;"><i
                                        class="fal fa-shopping-bag"></i><span id="cartQty">{{ Cart::instance('default')->count() }}</span></a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="wsus__mini_cart" >
            <h4>{{__('SHOPPING CART')}} <span class="wsus_close_mini_cart"><i class="far fa-times"></i></span></h4>
            <div id="load_sidebar_cart">
                @if (Cart::instance('default')->count() == 0)
                    <h5 class="text-danger text-center">{{('Your Cart is empty')}}</h5>
                @else
                <ul >
                    @php
                        $sidebarCartSubTotal = 0;
                        $sidebar_cart_contents = Cart::instance('default')->content();
                    @endphp
                    @foreach ($sidebar_cart_contents as $sidebar_cart_content)
                    <li>
                        <div class="wsus__cart_img">
                            <a href="#"><img src="{{ asset($sidebar_cart_content->options->image) }}" alt="product" class="img-fluid w-100"></a>
                            <a class="wsis__del_icon" onclick="sidebarCartItemRemove('{{ $sidebar_cart_content->rowId }}')" href="javascript:;"><i class="fas fa-minus-circle"></i></a>
                        </div>
                        <div class="wsus__cart_text">
                            <a class="wsus__cart_title" href="{{ route('product-detail', $sidebar_cart_content->options->slug) }}">{{ $sidebar_cart_content->name }}</a>
                            <p><span>{{ $sidebar_cart_content->qty }} x</span> {{ $currencySetting->currency_icon }}{{ $sidebar_cart_content->price }}</p>
                        </div>
                    </li>

                    @php
                        $productPrice = $sidebar_cart_content->price;
                        $total = $productPrice * $sidebar_cart_content->qty ;
                        $sidebarCartSubTotal += $total;
                    @endphp
                    @endforeach




                </ul>
                <h5>{{__('Sub Total')}} <span>{{ $currencySetting->currency_icon }}{{ $sidebarCartSubTotal }}</span></h5>
                <div class="wsus__minicart_btn_area">
                    <a class="common_btn" href="{{ route('cart') }}">{{__('View Cart')}}</a>
                    <a class="common_btn" href="{{ route('user.checkout.billing-address') }}">{{__('Checkout')}}</a>
                </div>
            @endif
            </div>
        </div>

    </header>
    <!--============================
        HEADER END
    ==============================-->


    <!--============================
        MAIN MENU START
    ==============================-->
    <nav class="wsus__main_menu d-none d-lg-block">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-3">
                    @if ($menus->where('id',24)->first()->status == 1)
                    <div class="wsus_menu_category_bar">
                        <p> <i class="far fa-bars"></i> {{__('Browse Categories')}} </p>
                        <span><i class="fas fa-caret-down"></i></span>
                    </div>

                    @endif

                    <ul class="wsus_menu_cat_item show_home toggle_menu">
                        @foreach ($productCategories as $productCategory)
                            @if ($productCategory->subCategories->count() == 0)
                                <li><a href="{{ route('product',['category' => $productCategory->slug]) }}"><i class="{{ $productCategory->icon }}"></i> {{ $productCategory->name }}</a></li>
                            @else
                                <li><a class="wsus__droap_arrow" href="{{ route('product',['category' => $productCategory->slug]) }}"><i class="{{ $productCategory->icon }}"></i> {{ $productCategory->name }} </a>
                                    <ul class="wsus_menu_cat_droapdown">
                                        @foreach ($productCategory->subCategories as $subCategory)
                                            @if ($subCategory->childCategories->count() == 0)
                                                <li><a href="{{ route('product',['sub_category' => $subCategory->slug]) }}">{{ $subCategory->name }}</a></li>
                                            @else
                                                <li><a href="{{ route('product',['sub_category' => $subCategory->slug]) }}">{{ $subCategory->name }} <i class="fas fa-angle-right"></i></a>
                                                    <ul class="wsus__sub_category">
                                                        @foreach ($subCategory->childCategories as $childCategory)
                                                            <li><a href="{{ route('product',['child_category' => $childCategory->slug]) }}">{{ $childCategory->name }}</a> </li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @endif

                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endforeach
                    </ul>

                </div>
                <div class="col-xl-9 col-lg-9">
                    <ul class="wsus__menu_item">
                        @if ($menus->where('id',1)->first()->status == 1)
                        <li><a  href="{{ route('home') }}">{{__('Home')}}</a></li>
                        @endif

                        @if ($menus->where('id',2)->first()->status == 1)
                        <li><a href="{{ route('product') }}">{{__('Shop')}}
                            @if ($menus->where('id',3)->first()->status == 1)
                             <i class="fas fa-caret-down"></i>
                            @endif
                            </a>
                            @if ($menus->where('id',3)->first()->status == 1)
                            <div class="wsus__mega_menu">
                                <div class="row">
                                    @foreach ($megaMenuCategories as $megaMenuCategory)
                                        <div class="col-xl-3 col-lg-4">
                                            <div class="wsus__mega_menu_colum">
                                                <h4><a class="text-dark" href="{{ route('product',['category' => $megaMenuCategory->category->slug]) }}">{{ $megaMenuCategory->category->name }}</a></h4>
                                                <ul class="wsis__mega_menu_item">
                                                    @foreach ($megaMenuCategory->subCategories as $subCategory)
                                                    <li><a href="{{ route('product',['sub_category' => $subCategory->subCategory->slug]) }}">{{ $subCategory->subCategory->name }} </a></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if ($megaMenuBanner->status == 1)
                                    <div class="col-xl-3 d-lg-none d-xl-block">
                                        <div class="wsus__mega_menu_colum">
                                            <img src="{{ asset($megaMenuBanner->image) }}" alt="images" class="img-fluid w-100">
                                            <div class="wsus__mega_menu_colum_text">
                                                <h5>{{ $megaMenuBanner->title }}</h5>
                                                <h3>{{ $megaMenuBanner->description }}</h3>
                                                <a class="common_btn" href="{{ $megaMenuBanner->link }}">{{__('Shop Now')}}</a>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </li>
                        @endif

                        @if ($menus->where('id',5)->first()->status == 1)
                        <li><a href="{{ route('blog') }}">{{__('Blog')}}</a></li>
                        @endif
                        @if ($menus->where('id',6)->first()->status == 1)
                        <li><a href="{{ route('campaign') }}">{{__('Campaign')}}</a></li>
                        @endif
                        @if ($menus->where('id',7)->first()->status == 1)
                        <li class="wsus__relative_li"><a href="javascript:;">{{__('Pages')}} <i class="fas fa-caret-down"></i></a>
                            <ul class="wsus__menu_droapdown">
                                @if ($menus->where('id',8)->first()->status == 1)
                                <li><a href="{{ route('about-us') }}">{{__('About Us')}}</a></li>
                                @endif
                                @if ($menus->where('id',9)->first()->status == 1)
                                <li><a href="{{ route('contact-us') }}">{{__('Contact Us')}}</a></li>
                                @endif
                                @if ($menus->where('id',10)->first()->status == 1)
                                <li><a href="{{ route('user.checkout.billing-address') }}">{{__('Check Out')}}</a></li>
                                @endif
                                @if ($menus->where('id',11)->first()->status == 1)
                                <li><a href="{{ route('brand') }}">{{__('Brand')}}</a></li>
                                @endif
                                @if ($menus->where('id',12)->first()->status == 1)
                                <li><a href="{{ route('faq') }}">{{__('FAQ')}}</a></li>
                                @endif
                                @if ($menus->where('id',13)->first()->status == 1)
                                <li><a href="{{ route('privacy-policy') }}">{{__('Privacy Policy')}}</a></li>
                                @endif
                                @if ($menus->where('id',14)->first()->status == 1)
                                <li><a href="{{ route('terms-and-conditions') }}">{{__('Terms and Conditions')}}</a></li>
                                @endif

                                @foreach ($customPages as $customPage)
                                    <li><a href="{{ route('page', $customPage->slug) }}">{{ $customPage->page_name }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        @endif
                    </ul>
                    <ul class="wsus__menu_item wsus__menu_item_right ms-auto">
                        @if ($menus->where('id',15)->first()->status == 1)
                        <li><a href="{{ route('track-order') }}">{{__('Track Order')}}</a></li>
                        @endif
                        @if ($menus->where('id',16)->first()->status == 1)
                        <li><a href="{{ route('flash-deal') }}">{{__('Flash Deal')}}</a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!--============================
        MAIN MENU END
    ==============================-->


    <!--============================
        MOBILE MENU START
    ==============================-->
    <section id="wsus__mobile_menu">
        <span class="wsus__mobile_menu_close"><i class="fal fa-times"></i></span>
        <ul class="wsus__mobile_menu_header_icon d-inline-flex">
            @if ($menus->where('id',21)->first()->status == 1)
            <li><a href="{{ route('user.wishlist') }}"><i class="far fa-heart"></i>
                @auth
                    @php
                        $user = Auth::guard('web')->user();
                        $wishlist = App\Models\Wishlist::where('user_id',$user->id)->count();
                    @endphp
                    <span id="mobileMenuwishlistQty">{{ $wishlist }}</span>
                @endauth
            </a></li>
            @endif

            @if ($menus->where('id',20)->first()->status == 1)
            <li><a href="{{ route('compare') }}"><i class="far fa-random"></i><span id="mobileMenuCompareQty">{{ Cart::instance('compare')->count() }}</span></a></li>
            @endif
        </ul>
        @if ($menus->where('id',25)->first()->status == 1)
        <form action="{{ route('product') }}">
            <input type="text" placeholder="{{__('Search')}}" name="search">
            <button type="submit"><i class="far fa-search"></i></button>
        </form>
        @endif

        @php
            $categoryFalse = $menus->where('id',24)->first()->status == 1 ? false : true;
        @endphp
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            @if ($menus->where('id',24)->first()->status == 1)
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home"
                    role="tab" aria-controls="pills-home" aria-selected="true">{{__('Categories')}}</button>
            </li>
            @endif
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $categoryFalse ? 'active' : '' }}" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile"
                    role="tab" aria-controls="pills-profile" aria-selected="false">{{__('Main Menu')}}</button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            @if ($menus->where('id',24)->first()->status == 1)
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <div class="wsus__mobile_menu_main_menu">
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <ul class="wsus_mobile_menu_category">
                            @foreach ($productCategories as $productCategory)
                                @if ($productCategory->subCategories->count() == 0)
                                <li><a href="{{ route('product',['category' => $productCategory->slug]) }}"><i class="{{ $productCategory->icon }}"></i> {{ $productCategory->name }}</a></li>
                                @else
                                    <li><a href="{{ route('product',['category' => $productCategory->slug]) }}" class="accordion-button collapsed" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseThreew-{{ $productCategory->id }}" aria-expanded="false"
                                        aria-controls="flush-collapseThreew-{{ $productCategory->id }}"><i class="{{ $productCategory->icon }}"></i> {{ $productCategory->name }}</a>
                                        <div id="flush-collapseThreew-{{ $productCategory->id }}" class="accordion-collapse collapse"
                                            data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                <ul>
                                                    @foreach ($productCategory->subCategories as $subCategory)
                                                        <li><a href="{{ route('product',['sub_category' => $subCategory->slug]) }}">{{ $subCategory->name }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif
            <div class="tab-pane fade  {{ $categoryFalse ? 'show active' : '' }}" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                <div class="wsus__mobile_menu_main_menu">
                    <div class="accordion accordion-flush" id="accordionFlushExample2">
                        <ul>
                            @if ($menus->where('id',1)->first()->status == 1)
                            <li><a href="{{ route('home') }}">{{__('Home')}}</a></li>
                            @endif
                            @if ($menus->where('id',2)->first()->status == 1)
                                @if ($menus->where('id',3)->first()->status == 1)
                                <li><a href="{{ route('product') }}" class="accordion-button collapsed" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseThree" aria-expanded="false"
                                        aria-controls="flush-collapseThree">{{__('Shop')}}</a>
                                    <div id="flush-collapseThree" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionFlushExample2">
                                        <div class="accordion-body">
                                            <ul>
                                                @foreach ($megaMenuCategories as $megaMenuCategory)
                                                <li><a href="{{ route('product',['category' => $megaMenuCategory->category->slug]) }}">{{ $megaMenuCategory->category->name }}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                @else
                                <li><a href="{{ route('product') }}">{{__('Shop')}}</a></li>
                                @endif
                            @endif

                            @if ($menus->where('id',5)->first()->status == 1)
                            <li><a href="{{ route('blog') }}">{{__('Blog') }}</a></li>
                            @endif
                            @if ($menus->where('id',6)->first()->status == 1)
                            <li><a href="{{ route('campaign') }}">{{__('Campain')}}</a></li>
                            @endif
                            @if ($menus->where('id',7)->first()->status == 1)
                            <li><a href="#" class="accordion-button collapsed" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseThree101" aria-expanded="false"
                                    aria-controls="flush-collapseThree101">{{__('Pages')}}</a>
                                <div id="flush-collapseThree101" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionFlushExample2">
                                    <div class="accordion-body">
                                        <ul>
                                            @if ($menus->where('id',8)->first()->status == 1)
                                            <li><a href="{{ route('about-us') }}">{{__('About Us')}}</a></li>
                                            @endif
                                            @if ($menus->where('id',9)->first()->status == 1)
                                            <li><a href="{{ route('contact-us') }}">{{__('Contact Us')}}</a></li>
                                            @endif
                                            @if ($menus->where('id',10)->first()->status == 1)
                                            <li><a href="{{ route('user.checkout.billing-address') }}">{{__('Check Out')}}</a></li>
                                            @endif
                                            @if ($menus->where('id',11)->first()->status == 1)
                                            <li><a href="{{ route('brand') }}">{{__('Brand')}}</a></li>
                                            @endif
                                            @if ($menus->where('id',12)->first()->status == 1)
                                            <li><a href="{{ route('faq') }}">{{__('FAQ')}}</a></li>
                                            @endif
                                            @if ($menus->where('id',13)->first()->status == 1)
                                            <li><a href="{{ route('privacy-policy') }}">{{__('Privacy Policy')}}</a></li>
                                            @endif
                                            @if ($menus->where('id',14)->first()->status == 1)
                                            <li><a href="{{ route('terms-and-conditions') }}">{{ __('user.Terms and Conditions') }}</a></li>
                                            @endif

                                            @foreach ($customPages as $customPage)
                                                <li><a href="{{ route('page', $customPage->slug) }}">{{ $customPage->page_name }}</a></li>
                                            @endforeach

                                        </ul>
                                    </div>
                                </div>
                            </li>
                            @endif
                            @if ($menus->where('id',15)->first()->status == 1)
                            <li><a href="{{ route('track-order') }}">{{__('Track Order')}}</a></li>
                            @endif
                            @if ($menus->where('id',16)->first()->status == 1)
                            <li><a href="{{ route('flash-deal') }}">{{__('Flash Deal')}}</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        MOBILE MENU END
    ==============================-->


    <!--==========================
           POP UP START
    ===========================-->

    @if ($announcementModal->status)
    <section id="wsus__pop_up">
        <div class="wsus__pop_up_center" style="background-image:url({{ asset($announcementModal->image) }})">
            <div class="wsus__pop_up_text">
                <span id="cross"><i class="fas fa-times"></i></span>
                <h5>{{ $announcementModal->title }}</h5>
                <p>{{ $announcementModal->description }}</p>
                <form id="modalSubscribeForm">
                    @csrf
                    <input type="email" name="email" placeholder="{{__('Your Email')}}" class="news_input">
                    <button type="submit" class="common_btn" id="modalSubscribeBtn"><i id="modal-subscribe-spinner" class="loading-icon fa fa-spin fa-spinner mr-3 d-none"></i> {{__('Subscribe')}}</button>
                    <div class="wsus__pop_up_check_box"></div>
                    </div>
                </form>
                <div class="form-check">
                    <input type="hidden" id="announcement_expired_date" value="{{ $announcementModal->expired_date }}">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault11">
                    <label class="form-check-label" for="flexCheckDefault11">
                        {{ $announcementModal->footer_text }}
                    </label>
                </div>
            </div>
        </div>
    </section>

    <script>
        let isannouncementModal = sessionStorage.getItem("announcementModal");
        let expirationDate = sessionStorage.getItem("announcementModalExpiration");
        if(isannouncementModal && expirationDate){
            let today = new Date();
            today = today.toISOString().slice(0,10)
            if(today < expirationDate){
                $("#wsus__pop_up").addClass("d-none");
            }
        }
    </script>

    @endif


    @yield('public-content')

    @php
        $setting = App\Models\Setting::first();
        $footer = App\Models\Footer::first();
        $links = App\Models\FooterSocialLink::all();
        $footerLinks = App\Models\FooterLink::all();
    @endphp
    <!--============================
        SUBSCRIBE PART START
    ==============================-->
    <section id="wsus__subscribe">
        <div class="container">
            <div class="wsus__subs_form">
                <div class="row">
                    <div class="col-xl-5 col-lg-5">
                        <div class="wsus__subd_text">
                            <h4>{{__('Subscribe To Our Newsletter')}}</h4>
                            <p>{{__('Get all the latest information on Events, Sales and Offers.')}}</p>
                        </div>
                    </div>
                    <div class="col-xl-7 col-lg-7">
                        <div class="wsus__subs_form">
                            <form id="subscriberForm">
                                @csrf
                                <input type="email" placeholder="{{__('Your Email')}}" name="email">
                                <button type="submit" class="common_btn" id="SubscribeBtn"><i id="subscribe-spinner" class="loading-icon fa fa-spin fa-spinner mr-3 d-none"></i>{{__('Subscribe')}}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        SUBSCRIBE PART END
    ==============================-->


    <!--============================
        FOOTER PART START
    ==============================-->
    <footer>
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-xl-3 col-sm-7 col-md-6 col-lg-3">
                    <div class="wsus__footer_content">
                        <img src="{{ asset($setting->logo) }}" alt="logo">
                        <a class="action" href="callto:{{ $footer->phone }}"><i class="fas fa-phone-alt"></i>
                            {{ $footer->phone }}</a>
                        <a class="action" href="mailto:{{ $footer->email }}"><i class="far fa-envelope"></i>
                            {{ $footer->email }}</a>
                        <p><i class="fal fa-map-marker-alt"></i> {{ $footer->address }}</p>

                    </div>
                </div>
                <div class="col-xl-2 col-sm-5 col-md-6 col-lg-2">
                    <div class="wsus__footer_content">

                        <h5>{{ $footer->first_column }}</h5>
                        <ul class="wsus__footer_menu">
                            @foreach ($footerLinks->where('column',1) as $footerLink)
                            <li><a href="{{ $footerLink->link }}"><i class="fas fa-caret-right"></i> {{ $footerLink->title }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-xl-2 col-sm-7 col-md-6 col-lg-2">
                    <div class="wsus__footer_content">
                        <h5>{{ $footer->second_column }}</h5>
                        <ul class="wsus__footer_menu">
                            @foreach ($footerLinks->where('column',2) as $footerLink)
                            <li><a href="{{ $footerLink->link }}"><i class="fas fa-caret-right"></i> {{ $footerLink->title }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-xl-2 col-sm-5 col-md-6 col-lg-3">
                    <div class="wsus__footer_content">
                        <h5>{{ $footer->third_column }}</h5>
                        <ul class="wsus__footer_menu">
                            @foreach ($footerLinks->where('column',3) as $footerLink)
                            <li><a href="{{ $footerLink->link }}"><i class="fas fa-caret-right"></i> {{ $footerLink->title }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="wsus__footer_bottom">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="wsus__copyright">
                            <p>{{ $footer->copyright }}</p>
                            <p>{{ $footer->image_title }} :
                                <img src="{{ asset($footer->payment_image) }}" alt="card" class="img-fluid">
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--============================
        FOOTER PART END
    ==============================-->


    <!--============================
        SCROLL BUTTON START
    ==============================-->
    <div class="wsus__scroll_btn">
        <i class="fas fa-chevron-up"></i>
    </div>
    <!--============================
        SCROLL BUTTON  END
    ==============================-->

    @php
        $isAuth = false;
        if(Auth::check()) $isAuth = true;
        $shop_page = App\Models\ShopPage::first();
        $max_val = $shop_page->filter_price_range;
        $currencySetting = App\Models\Setting::first();
        $currency_icon = $currencySetting->currency_icon;
        $cookie_consent = App\Models\CookieConsent::first();
        $setting = App\Models\Setting::first();

    @endphp
    <script>
        let filter_max_val = "{{ $max_val }}";
        let currency_icon = "{{ $currency_icon }}";
    </script>

    @if ($cookie_consent->status == 1)
    <script src="{{ asset('user/js/cookieconsent.min.js') }}"></script>

    <script>
    window.addEventListener("load",function(){window.wpcc.init({"border":"{{ $cookie_consent->border }}","corners":"{{ $cookie_consent->corners }}","colors":{"popup":{"background":"{{ $cookie_consent->background_color }}","text":"{{ $cookie_consent->text_color }} !important","border":"{{ $cookie_consent->border_color }}"},"button":{"background":"{{ $cookie_consent->btn_bg_color }}","text":"{{ $cookie_consent->btn_text_color }}"}},"content":{"href":"{{ route('privacy-policy') }}","message":"{{ $cookie_consent->message }}","link":"{{ $cookie_consent->link_text }}","button":"{{ $cookie_consent->btn_text }}"}})});
    </script>
    @endif

    <!--bootstrap js-->
    <script src="{{ asset('user/js/bootstrap.bundle.min.js') }}"></script>
    <!--font-awesome js-->
    <script src="{{ asset('user/js/Font-Awesome.js') }}"></script>
    <!--select2 js-->
    <script src="{{ asset('user/js/select2.min.js') }}"></script>
    <!--slick slider js-->
    <script src="{{ asset('user/js/slick.min.js') }}"></script>
    <!--simplyCountdown js-->
    <script src="{{ asset('user/js/simplyCountdown.js') }}"></script>
    <!--product zoomer js-->
    <script src="{{ asset('user/js/jquery.exzoom.js') }}"></script>
    <!--nice-number js-->
    <script src="{{ asset('user/js/jquery.nice-number.min.js') }}"></script>
    <!--counter js-->
    <script src="{{ asset('user/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('user/js/jquery.countup.min.js') }}"></script>
    <!--calender js-->
    <script src="{{ asset('user/js/jquery.calendar.js') }}"></script>
    <!--add row js-->
    <script src="{{ asset('user/js/add_row_custon.js') }}"></script>
    <!--multiple-image-video js-->
    <script src="{{ asset('user/js/multiple-image-video.js') }}"></script>
    <!--sticky sidebar js-->
    <script src="{{ asset('user/js/sticky_sidebar.js') }}"></script>
    <!--price ranger js-->
    <script src="{{ asset('user/js/ranger_jquery-ui.min.js') }}"></script>
    <script src="{{ asset('user/js/ranger_slider.js') }}"></script>
    <!--isotope js-->
    <script src="{{ asset('user/js/isotope.pkgd.min.js') }}"></script>
    <!--venobox js-->
    <script src="{{ asset('user/js/venobox.min.js') }}"></script>
    <!--classycountdown js-->
    <script src="{{ asset('user/js/jquery.classycountdown.js') }}"></script>

    <!--main/custom js-->
    <script src="{{ asset('user/js/main.js') }}"></script>

    <script src="{{ asset('toastr/toastr.min.js') }}"></script>

    <script>
        @if(Session::has('messege'))
        var type="{{Session::get('alert-type','info')}}"
        switch(type){
            case 'info':
                toastr.info("{{ Session::get('messege') }}");
                break;
            case 'success':
                toastr.success("{{ Session::get('messege') }}");
                break;
            case 'warning':
                toastr.warning("{{ Session::get('messege') }}");
                break;
            case 'error':
                toastr.error("{{ Session::get('messege') }}");
                break;
        }
        @endif
    </script>

    @if ($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            toastr.error('{{ $error }}');
        </script>
    @endforeach
    @endif



    <script>
        (function($) {
            "use strict";
            $(document).ready(function () {
                $(".click_first_cat").click();
                $(".click_featured_product").click();

                $("#modalSubscribeForm").on('submit', function(e){
                    e.preventDefault();



                    $("#modal-subscribe-spinner").removeClass('d-none')
                    $("#modalSubscribeBtn").addClass('after_subscribe')
                    $("#modalSubscribeBtn").attr('disabled',true);

                    $.ajax({
                        type: 'POST',
                        data: $('#modalSubscribeForm').serialize(),
                        url: "{{ route('subscribe-request') }}",
                        success: function (response) {
                            if(response.status == 1){
                                toastr.success(response.message);
                                $("#modal-subscribe-spinner").addClass('d-none')
                                $("#modalSubscribeBtn").removeClass('after_subscribe')
                                $("#modalSubscribeBtn").attr('disabled',false);
                                let expiredDate = $("#announcement_expired_date").val();
                                expiredDate = expiredDate*1;
                                let date = new Date();
                                date.setDate(date.getDate() + expiredDate);
                                let nextDate = date.toISOString().slice(0,10);
                                sessionStorage.setItem("announcementModal", "yes");
                                sessionStorage.setItem("announcementModalExpiration", nextDate);
                                $("#cross").click();

                            }

                            if(response.status == 0){
                                toastr.error(response.message);
                                $("#modal-subscribe-spinner").addClass('d-none')
                                $("#modalSubscribeBtn").removeClass('after_subscribe')
                                $("#modalSubscribeBtn").attr('disabled',false);
                            }
                        },
                        error: function(err) {
                            toastr.error('Something went wrong');
                            $("#modal-subscribe-spinner").addClass('d-none')
                            $("#modalSubscribeBtn").removeClass('after_subscribe')
                            $("#modalSubscribeBtn").attr('disabled',false);
                        }
                    });
                })

                $("#flexCheckDefault11").on("click", function(){
                    let expiredDate = $("#announcement_expired_date").val();
                    expiredDate = expiredDate*1;
                    let date = new Date();
                    date.setDate(date.getDate() + expiredDate);
                    let nextDate = date.toISOString().slice(0,10);
                    sessionStorage.setItem("announcementModal", "yes");
                    sessionStorage.setItem("announcementModalExpiration", nextDate);
                    $("#cross").click();
                })

                $("#subscriberForm").on('submit', function(e){
                    e.preventDefault();


                    $("#subscribe-spinner").removeClass('d-none')
                    $("#SubscribeBtn").addClass('after_subscribe')
                    $("#SubscribeBtn").attr('disabled',true);

                    $.ajax({
                        type: 'POST',
                        data: $('#subscriberForm').serialize(),
                        url: "{{ route('subscribe-request') }}",
                        success: function (response) {
                            if(response.status == 1){
                                toastr.success(response.message);
                                $("#subscribe-spinner").addClass('d-none')
                                $("#SubscribeBtn").removeClass('after_subscribe')
                                $("#SubscribeBtn").attr('disabled',false);
                                $("#subscriberForm").trigger("reset");
                            }

                            if(response.status == 0){
                                toastr.error(response.message);
                                $("#subscribe-spinner").addClass('d-none')
                                $("#SubscribeBtn").removeClass('after_subscribe')
                                $("#SubscribeBtn").attr('disabled',false);
                            }
                        },
                        error: function(err) {
                            toastr.error('Something went wrong');
                            $("#subscribe-spinner").addClass('d-none')
                            $("#SubscribeBtn").removeClass('after_subscribe')
                            $("#SubscribeBtn").attr('disabled',false);
                        }
                    });
                })

                $(".productModalVariant").on("change", function(){
                    let id = $(this).data("product");
                    calculateProductModalPrice(id);

                })



            });
        })(jQuery);

        function addToWishlist(id){



            let isAuth = "{{ $isAuth }}";
            if(!isAuth){
                toastr.error("{{__('Please Login First')}}");
                return;
            }
            $.ajax({
                type: 'get',
                url: "{{ url('user/add-to-wishlist/') }}"+ "/" + id,
                success: function (response) {
                    if(response.status == 1){
                        toastr.success(response.message)

                        let currentQty = $("#wishlistQty").text();
                        currentQty = currentQty * 1 + 1*1;
                        $("#wishlistQty").text(currentQty);

                        let mobileMenuCurrentQty = $("#mobileMenuwishlistQty").text();
                        mobileMenuCurrentQty = mobileMenuCurrentQty *1 + 1*1;
                        $("#mobileMenuwishlistQty").text(mobileMenuCurrentQty);
                    }
                    if(response.status == 0){
                        toastr.error(response.message)
                    }
                },
                error: function(response) {
                    alert('error');
                }
            });
        }


        function calculateProductModalPrice(productId){
            $.ajax({
                type: 'get',
                data: $('#productModalFormId-'+productId).serialize(),
                url: "{{ route('calculate-product-price') }}",
                success: function (response) {
                    let qty = $("#productModalQty-"+productId).val();
                    let price = response.productPrice * qty;
                    price = price.toFixed(2);
                    $("#productModalPrice-"+productId).text(price);
                    $("#mainProductModalPrice-"+productId).text(price);
                },
                error: function(err) {
                    alert('error')
                }
            });

        }

        function productModalIncrement(id){
            let qty = $("#productModalQty-"+id).val();
            qty = qty*1 + 1*1;
            $("#productModalQty-"+id).val(qty);
            calculateProductModalPrice(id)
        }

        function productModalDecrement(id){
            let qty = $("#productModalQty-"+id).val();
            if(qty > 1){
                qty = qty - 1;
                $("#productModalQty-"+id).val(qty);
                calculateProductModalPrice(id)
            }

        }

        function addToCartMainProduct(productId){
            addToCartInProductModal(productId);
        }


        function addToCartInProductModal(productId){
            $.ajax({
                type: 'get',
                data: $('#productModalFormId-'+productId).serialize(),
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
                                        $("#productModalView-"+productId).modal('hide');
                                    },
                                });
                            },
                        });
                    }
                },
                error: function(response) {

                }
            });
        }

        function addToBuyNow(id){
            $.ajax({
                type: 'get',
                data: $('#productModalFormId-'+id).serialize(),
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
        }

        function sidebarCartItemRemove(id){
            $.ajax({
                type: 'get',
                url: "{{ url('sidebar-cart-item-remove') }}" + "/" + id,
                success: function (response) {
                    toastr.success(response.message)
                    let ifCheckoutPage = "{{ Route::is('user.checkout.payment') || Route::is('user.checkout.checkout') || Route::is('user.checkout.billing-address') ? 'yes' : 'no' }}";
                    if(ifCheckoutPage == 'yes'){
                        window.location.reload();
                    }
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

                    $.ajax({
                        type: 'get',
                        url: "{{ route('load-main-cart') }}",
                        success: function (response) {
                            $("#CartResponse").html(response)
                        },
                    });
                },
            });

        }

        function addToCompare(id){
            $.ajax({
                type: 'get',
                url: "{{ url('add-to-compare') }}" + "/" + id,
                success: function (response) {
                    if(response.status == 1){
                        toastr.success(response.message)
                        let currentQty = $("#compareQty").text();
                        currentQty = currentQty * 1 + 1*1;
                        $("#compareQty").text(currentQty);

                        let mobileMenuCurrentQty = $("#mobileMenuCompareQty").text();
                        mobileMenuCurrentQty = mobileMenuCurrentQty *1 + 1*1;
                        $("#mobileMenuCompareQty").text(mobileMenuCurrentQty);

                    }else{
                        toastr.error(response.message)
                    }
                },
            });

        }

    </script>
</body>

</html>
