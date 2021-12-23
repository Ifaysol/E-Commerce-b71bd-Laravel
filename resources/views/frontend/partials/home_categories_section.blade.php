@foreach (\App\HomeCategory::where('status', 1)->orderBy('serial', 'ASC')->get() as $key => $homeCategory)
    @if ($homeCategory->category != null)
    @php
        $explode = explode(',',$homeCategory->subcategory_id);
        $subcategoryy = \App\SubCategory::where('id', $explode[0])->first();
       
    @endphp
        <section class="mb-4">
            <div class="container container-lg">
                <div class="px-2 py-4 p-md-4 bg-white shadow-sm">
                    <div class="section-title-1 clearfix">
                        <h3 class="heading-5 strong-700 mb-0 float-lg-left">
                            <span class="mr-4">{{ translate($homeCategory->title) }}</span>
                        </h3>
                        <ul class="inline-links float-lg-right nav mt-3 mb-2 m-lg-0">
                            <li><a href="{{ route('products.subcategory', $subcategoryy->slug) }}" class="active">View More</a></li>
                        </ul>
                    </div>
                    <div class="caorusel-box arrow-round gutters-5">
                        <div class="slick-carousel" data-slick-items="6" data-slick-xl-items="5" data-slick-lg-items="4"  data-slick-md-items="3" data-slick-sm-items="2" data-slick-xs-items="2">
                            
                        @foreach (filter_products(\App\Product::where('published', 1)->whereNull('pre_sale')->whereIn('subcategory_id', $explode)->orderBy(\DB::raw('-`serial`'), 'desc'))->latest()->limit(10)->get() as $key => $product)
                            <div class="caorusel-card">
                                <div class="product-box-2 bg-white alt-box my-2 sayeem">
                                    <div class="position-relative overflow-hidden">
                                        <a href="{{ route('product', $product->slug) }}" class="d-block product-image h-100 text-center">
                                            <img class="img-fit lazyload" src="{{ my_asset('frontend/images/placeholder.jpg') }}" data-src="{{ my_asset($product->thumbnail_img) }}" alt="{{ __($product->name) }}">
                                        </a>
                                        <div class="product-btns clearfix">
                                            <button class="btn add-wishlist" title="Add to Wishlist" onclick="addToWishList({{ $product->id }})" tabindex="0">
                                                <i class="la la-heart-o"></i>
                                            </button>
                                            <button class="btn add-compare" title="Add to Compare" onclick="addToCompare({{ $product->id }})" tabindex="0">
                                                <i class="la la-refresh"></i>
                                            </button>
                                            <button class="btn quick-view" title="Quick view" onclick="showAddToCartModal({{ $product->id }})" tabindex="0">
                                                <i class="la la-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="p-md-3 p-2 discount">
                                        {{-- @if($product->discount != null)
                                            @if($product->discount != '0.00')
                                            <div class="discount-section">
                                                {{ $product->discount }}
                                                @if($product->discount_type == 'percent')
                                                <span>%</span>
                                                @endif
                                                @if($product->discount_type == 'amount')
                                                <span>{{currency_symbol()}}</span>
                                                @endif
                                                
                                            </div>
                                            @endif
                                        @endif --}}
                                        <div class="price-box">
                                            @if(home_base_price($product->id) != home_discounted_base_price($product->id))
                                                <del class="old-product-price strong-400">{{ home_base_price($product->id) }}</del>
                                            @endif
                                            <span class="product-price strong-600">{{ home_discounted_base_price($product->id) }}</span>
                                        </div>
                                        <div class="star-rating star-rating-sm mt-1">
                                            {{ renderStarRating($product->rating) }}
                                        </div>
                                        <h2 class="product-title p-0">
                                            <a href="{{ route('product', $product->slug) }}" class=" text-truncate">{{ __($product->name) }}</a>
                                        </h2>
                                        @if (\App\Addon::where('unique_identifier', 'club_point')->first() != null && \App\Addon::where('unique_identifier', 'club_point')->first()->activated)
                                            <div class="club-point mt-2 bg-soft-base-1 border-light-base-1 border">
                                                {{ translate('Club Point') }}:
                                                <span class="strong-700 float-right">{{ $product->earn_point }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endforeach
