@php
    $setting = App\Models\Setting::first();
    $nots_count = App\Models\Notification::whereNull('read_at')->count();

@endphp


<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <ul class="sidebar-menu">
            <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}"><a class="nav-link"
                                                                              href="{{ route('admin.dashboard') }}"><i
                        class="fas fa-home"></i> <span>{{__('admin.Dashboard')}}</span></a></li>
            @if(Auth::guard()->user()->admin_type=="1")
                <li class="{{ Route::is('admin.notifications.*') ? 'active' : '' }}"><a class="nav-link"
                                                                                        href="{{ route('admin.notifications.index') }}"><i
                            class="fas fa-bell"></i> <span>{{__('admin.Notifications')}} ({{$nots_count}})</span></a>
                </li>
            @endif
            <li class="nav-item dropdown {{ Route::is('admin.all-order') || Route::is('admin.order-show') || Route::is('admin.pending-order') || Route::is('admin.pregress-order') || Route::is('admin.delivered-order') ||  Route::is('admin.completed-order') || Route::is('admin.declined-order') || Route::is('admin.cash-on-delivery')  ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i
                        class="fas fa-shopping-cart"></i><span>{{__('admin.Orders')}}</span></a>

                <ul class="dropdown-menu">

                    <li class="{{ Route::is('admin.all-order') || Route::is('admin.order-show') ? 'active' : '' }}"><a
                            class="nav-link" href="{{ route('admin.all-order') }}">{{__('admin.All Orders')}}</a></li>

                    <li class="{{ Route::is('admin.pending-order') ? 'active' : '' }}"><a class="nav-link"
                                                                                          href="{{ route('admin.pending-order') }}">{{__('admin.Pending Orders')}}</a>
                    </li>

                    <li class="{{ Route::is('admin.pregress-order') ? 'active' : '' }}"><a class="nav-link"
                                                                                           href="{{ route('admin.pregress-order') }}">{{__('admin.Progress Orders')}}</a>
                    </li>
                    <li class="{{ Route::is('admin.delivered-order') ? 'active' : '' }}"><a class="nav-link"
                                                                                            href="{{ route('admin.delivered-order') }}">{{__('admin.Delivered Orders')}}</a>
                    </li>
                    <li class="{{ Route::is('admin.completed-order') ? 'active' : '' }}"><a class="nav-link"
                                                                                            href="{{ route('admin.completed-order') }}">{{__('admin.Completed Orders')}}</a>
                    </li>

                    <li class="{{ Route::is('admin.declined-order') ? 'active' : '' }}"><a class="nav-link"
                                                                                           href="{{ route('admin.declined-order') }}">{{__('admin.Declined Orders')}}</a>
                    </li>
                    <li class="{{ Route::is('admin.cash-on-delivery') ? 'active' : '' }}"><a class="nav-link"
                                                                                             href="{{ route('admin.cash-on-delivery') }}">{{__('admin.Cash On Delivery')}}</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown {{ Route::is('admin.product-category.*') || Route::is('admin.product-sub-category.*') || Route::is('admin.product-child-category.*') || Route::is('admin.mega-menu-category.*') || Route::is('admin.mega-menu-sub-category') || Route::is('admin.create-mega-menu-sub-category') || Route::is('admin.edit-mega-menu-sub-category') || Route::is('admin.mega-menu-banner') || Route::is('admin.popular-category') || Route::is('admin.featured-category') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i
                        class="fas fa-th-large"></i><span>{{__('admin.Manage Categories')}}</span></a>

                <ul class="dropdown-menu">

                    <li class="{{ Route::is('admin.product-category.*') ? 'active' : '' }}"><a class="nav-link"
                                                                                               href="{{ route('admin.product-category.index') }}">{{__('admin.Categories')}}</a>
                    </li>
                </ul>
            </li>


            <li class="nav-item dropdown {{ Route::is('admin.product.*') || Route::is('admin.product-brand.*') || Route::is('admin.product-variant') || Route::is('admin.create-product-variant') || Route::is('admin.edit-product-variant') || Route::is('admin.product-gallery') || Route::is('admin.product-variant-item') || Route::is('admin.create-product-variant-item') || Route::is('admin.edit-product-variant-item') || Route::is('admin.product-review') || Route::is('admin.show-product-review') || Route::is('admin.wholesale') || Route::is('admin.create-wholesale') || Route::is('admin.edit-wholesale') || Route::is('admin.product-highlight') ||  Route::is('admin.product-report') || Route::is('admin.show-product-report') || Route::is('admin.specification-key.*') || Route::is('admin.stockout-product') || Route::is('admin.product-import-page') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i
                        class="fas fa-th-large"></i><span>{{__('admin.Manage Products')}}</span></a>

                <ul class="dropdown-menu">

                    <li class="{{ Route::is('admin.product-brand.*') ? 'active' : '' }}"><a class="nav-link"
                                                                                            href="{{ route('admin.product-brand.index') }}">{{__('admin.Brands')}}</a>
                    </li>

                    <li><a class="nav-link"
                           href="{{ route('admin.product.create') }}">{{__('admin.Create Product')}}</a></li>

                    <li class="{{ Route::is('admin.product.*') || Route::is('admin.product-variant') || Route::is('admin.create-product-variant') || Route::is('admin.edit-product-variant') || Route::is('admin.product-gallery') || Route::is('admin.product-variant-item') || Route::is('admin.create-product-variant-item') || Route::is('admin.edit-product-variant-item') || Route::is('admin.wholesale') || Route::is('admin.create-wholesale') || Route::is('admin.edit-wholesale') || Route::is('admin.product-highlight') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.product.index') }}">{{__('admin.Products')}}</a></li>


                    <li class="{{ Route::is('admin.product-variant.*') ? 'active' : '' }}"><a class="nav-link"
                                                                                              href="{{ route('admin.product-variant.index') }}">{{__('admin.Product Variant')}}</a>
                    </li>
                </ul>
            </li>


            <li class="nav-item dropdown {{  Route::is('admin.customer-list') || Route::is('admin.customer-show') || Route::is('admin.pending-customer-list') || Route::is('admin.email-history') || Route::is('admin.send-email-to-all-customer') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-user"></i><span>{{__('admin.Users')}}</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Route::is('admin.customer-list') || Route::is('admin.customer-show') || Route::is('admin.send-email-to-all-customer') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.customer-list') }}">{{__('admin.Customer List')}}</a>
                    </li>
                    @if(Auth::guard()->user()->is_moder!="1")
                        <li class="{{ Route::is('admin.admin-list.*')  ? 'active' : '' }}"><a class="nav-link"
                                                                                              href="{{ route('admin.admin-list.index') }}">{{__('admin.Admin List')}}</a>
                        </li>
                    @endif
                    <li class="{{ Route::is('admin.customer-card-status') ? 'active' : '' }}"><a class="nav-link"
                                                                                                 href="{{ route('admin.customer-card-status.index') }}">{{__('admin.Customer card status')}}</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown {{ Route::is('admin.service.*') || Route::is('admin.maintainance-mode') || Route::is('admin.announcement') ||  Route::is('admin.slider.*') || Route::is('admin.home-page') || Route::is('admin.banner-image.index') || Route::is('admin.homepage-one-visibility') || Route::is('admin.cart-bottom-banner') || Route::is('admin.shop-page') || Route::is('admin.seo-setup') || Route::is('admin.menu-visibility') || Route::is('admin.product-detail-page') || Route::is('admin.default-avatar') || Route::is('admin.subscription-banner') || Route::is('admin.testimonial.*') || Route::is('admin.homepage-section-title') || Route::is('admin.image-content') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i
                        class="fas fa-globe"></i><span>{{__('admin.Manage Website')}}</span></a>

                <ul class="dropdown-menu">

                    <li class="{{ Route::is('admin.slider.*') ? 'active' : '' }}"><a class="nav-link"
                                                                                     href="{{ route('admin.slider.index') }}">{{__('admin.Slider')}}</a>
                    </li>
                    <li class="{{ Route::is('admin.partner.*') ? 'active' : '' }}"><a class="nav-link"
                                                                                      href="{{ route('admin.partner.index') }}">{{__('admin.Partners')}}</a>
                    </li>

                </ul>
            </li>

            <li class="nav-item dropdown {{ Route::is('admin.footer.*') || Route::is('admin.social-link.*') || Route::is('admin.footer-link.*') || Route::is('admin.second-col-footer-link') || Route::is('admin.third-col-footer-link') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i
                        class="fas fa-th-large"></i><span>{{__('admin.Website Footer')}}</span></a>

                <ul class="dropdown-menu">
                    <li class="{{ Route::is('admin.footer.*') ? 'active' : '' }}"><a class="nav-link"
                                                                                     href="{{ route('admin.footer.index') }}">{{__('admin.Footer')}}</a>
                    </li>

                    <li class="{{ Route::is('admin.footer-link.*') ? 'active' : '' }}"><a class="nav-link"
                                                                                          href="{{ route('admin.footer-link.index') }}">{{__('admin.Link')}}</a>
                    </li>

                    <li class="{{ Route::is('admin.second-col-footer-link') ? 'active' : '' }}"><a class="nav-link"
                                                                                                   href="{{ route('admin.second-col-footer-link') }}">Last
                            Column Data</a></li>

                </ul>
            </li>


            <li class="nav-item dropdown {{ Route::is('admin.about-us.*') || Route::is('admin.custom-page.*') || Route::is('admin.terms-and-condition.*') || Route::is('admin.privacy-policy.*') || Route::is('admin.faq.*') || Route::is('admin.error-page.*') || Route::is('admin.contact-us.*') || Route::is('admin.login-page') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i
                        class="fas fa-columns"></i><span>{{__('admin.Pages')}}</span></a>

                <ul class="dropdown-menu">
                    <li class="{{ Route::is('admin.contact-us.*') ? 'active' : '' }}"><a class="nav-link"
                                                                                         href="{{ route('admin.contact-us.index') }}">{{__('admin.Contact Us')}}</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown {{ Route::is('admin.blog-category.*') || Route::is('admin.blog.*') || Route::is('admin.popular-blog.*') || Route::is('admin.blog-comment.*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i
                        class="fas fa-th-large"></i><span>{{__('admin.Blogs')}}</span></a>

                <ul class="dropdown-menu">
                    <li class="{{ Route::is('admin.blog-category.*') ? 'active' : '' }}"><a class="nav-link"
                                                                                            href="{{ route('admin.blog-category.index') }}">{{__('admin.Categories')}}</a>
                    </li>

                    <li class="{{ Route::is('admin.blog.*') ? 'active' : '' }}"><a class="nav-link"
                                                                                   href="{{ route('admin.blog.index') }}">{{__('admin.Blogs')}}</a>
                    </li>

                    <li class="{{ Route::is('admin.blog-comment.*') ? 'active' : '' }}"><a class="nav-link"
                                                                                           href="{{ route('admin.blog-comment.index') }}">{{__('admin.Comments')}}</a>
                    </li>
                </ul>
            </li>
        </ul>

    </aside>
</div>
