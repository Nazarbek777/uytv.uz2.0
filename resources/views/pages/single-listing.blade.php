@extends('layouts.page')
@section('content')

<!-- ============================ Hero Banner  Start================================== -->
@php
    $propertyImages = $property->images ?? [];
    $featuredImage = $property->featured_image;
    if ($featuredImage && !in_array($featuredImage, $propertyImages)) {
        array_unshift($propertyImages, $featuredImage);
    }
    if (empty($propertyImages) && $featuredImage) {
        $propertyImages = [$featuredImage];
    }
@endphp
@if(!empty($propertyImages))
<div class="featured_slick_gallery gray">
    <div class="featured_slick_gallery-slide">
        @foreach($propertyImages as $image)
            <div class="featured_slick_padd">
                <a href="{{ asset('storage/' . $image) }}" class="mfp-gallery">
                    <img src="{{ asset('storage/' . $image) }}" class="img-fluid mx-auto" alt="{{ $property->title }}" />
                </a>
            </div>
        @endforeach
    </div>
    @if(count($propertyImages) > 1)
        <a href="JavaScript:Void(0);" class="btn-view-pic">{{ $locale === 'uz' ? 'Barcha rasmlarni ko\'rish' : ($locale === 'ru' ? 'Посмотреть все фото' : 'View photos') }}</a>
    @endif
</div>
@elseif($featuredImage)
<div class="featured_slick_gallery gray">
    <div class="featured_slick_gallery-slide">
        <div class="featured_slick_padd">
            <img src="{{ asset('storage/' . $featuredImage) }}" class="img-fluid mx-auto" alt="{{ $property->title }}" />
        </div>
    </div>
</div>
@endif
<!-- ============================ Hero Banner End ================================== -->

<!-- ============================ Property Detail Start ================================== -->
<section class="gray-simple">
    <div class="container">
        <div class="row">

            <!-- property main detail -->
            <div class="col-lg-8 col-md-12 col-sm-12">

                <div class="property_block_wrap style-2 p-4">
                    <div class="prt-detail-title-desc">
                        <span class="label text-light {{ $property->listing_type === 'sale' ? 'bg-green' : 'bg-blue' }}">
                            {{ $property->listing_type === 'sale' ? ($locale === 'uz' ? 'Sotuv' : ($locale === 'ru' ? 'Продажа' : 'For Sale')) : ($locale === 'uz' ? 'Ijaraga' : ($locale === 'ru' ? 'Аренда' : 'For Rent')) }}
                        </span>
                        <h3>{{ $property->title }}</h3>
                        <span><i class="bi bi-geo-alt"></i> 
                            @if($property->address)
                                {{ $property->address }}
                            @else
                                {{ $property->city }}{{ $property->region ? ', ' . $property->region : '' }}{{ $property->country ? ', ' . $property->country : '' }}
                            @endif
                        </span>
                        <h3 class="prt-price-fix text-main">
                            @if($property->price)
                                {{ number_format($property->price, 0, '.', ' ') }} 
                                {{ $property->currency === 'USD' ? '$' : ($property->currency === 'EUR' ? '€' : 'so\'m') }}
                                @if($property->listing_type === 'rent')
                                    <sub>/{{ $locale === 'uz' ? 'oy' : ($locale === 'ru' ? 'мес' : 'month') }}</sub>
                                @endif
                            @else
                                {{ $locale === 'uz' ? 'Narx mavjud emas' : ($locale === 'ru' ? 'Цена не указана' : 'Price not available') }}
                            @endif
                        </h3>
                        <div class="list-fx-features">
                            @if($property->bedrooms)
                            <div class="listing-card-info-icon">
                                <div class="inc-fleat-icon me-1"><img src="https://shreethemes.net/resido-2.3/resido/assets/img/bed.svg" width="13" alt=""></div>
                                {{ $property->bedrooms }} {{ $locale === 'uz' ? 'Xona' : ($locale === 'ru' ? 'Комнат' : 'Beds') }}
                            </div>
                            @endif
                            @if($property->bathrooms)
                            <div class="listing-card-info-icon">
                                <div class="inc-fleat-icon me-1"><img src="https://shreethemes.net/resido-2.3/resido/assets/img/bathtub.svg" width="13" alt=""></div>
                                {{ $property->bathrooms }} {{ $locale === 'uz' ? 'Hammom' : ($locale === 'ru' ? 'Ванн' : 'Bath') }}
                            </div>
                            @endif
                            @if($property->area)
                            <div class="listing-card-info-icon">
                                <div class="inc-fleat-icon me-1"><img src="https://shreethemes.net/resido-2.3/resido/assets/img/move.svg" width="13" alt=""></div>
                                {{ number_format($property->area, 0, '.', ' ') }} {{ $property->area_unit ?? 'm²' }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Single Block Wrap -->
                <div class="property_block_wrap style-2">

                    <div class="property_block_wrap_header">
                        <a data-bs-toggle="collapse" data-parent="#features" data-bs-target="#clOne" aria-controls="clOne" href="javascript:void(0);" aria-expanded="false">
                            <h4 class="property_block_title">{{ $locale === 'uz' ? 'Tafsilotlar va Xususiyatlar' : ($locale === 'ru' ? 'Детали и Особенности' : 'Detail & Features') }}</h4>
                        </a>
                    </div>
                    <div id="clOne" class="panel-collapse collapse show" aria-labelledby="clOne">
                        <div class="block-body">
                            <ul class="deatil_features">
                                @if($property->bedrooms)
                                <li><strong>{{ $locale === 'uz' ? 'Xonalar:' : ($locale === 'ru' ? 'Комнаты:' : 'Bedrooms:') }}</strong>{{ $property->bedrooms }} {{ $locale === 'uz' ? 'Xona' : ($locale === 'ru' ? 'Комнат' : 'Beds') }}</li>
                                @endif
                                @if($property->bathrooms)
                                <li><strong>{{ $locale === 'uz' ? 'Hammomlar:' : ($locale === 'ru' ? 'Ванные:' : 'Bathrooms:') }}</strong>{{ $property->bathrooms }} {{ $locale === 'uz' ? 'Hammom' : ($locale === 'ru' ? 'Ванн' : 'Bath') }}</li>
                                @endif
                                @if($property->area)
                                <li><strong>{{ $locale === 'uz' ? 'Maydon:' : ($locale === 'ru' ? 'Площадь:' : 'Area:') }}</strong>{{ number_format($property->area, 0, '.', ' ') }} {{ $property->area_unit ?? 'm²' }}</li>
                                @endif
                                @if($property->garages)
                                <li><strong>{{ $locale === 'uz' ? 'Garaj:' : ($locale === 'ru' ? 'Гараж:' : 'Garage:') }}</strong>{{ $property->garages }}</li>
                                @endif
                                @if($property->property_type)
                                <li><strong>{{ $locale === 'uz' ? 'Uy-joy turi:' : ($locale === 'ru' ? 'Тип недвижимости:' : 'Property Type:') }}</strong>
                                    @php
                                        $propertyTypes = [
                                            'apartment' => ['uz' => 'Kvartira', 'ru' => 'Квартира', 'en' => 'Apartment'],
                                            'house' => ['uz' => 'Uy', 'ru' => 'Дом', 'en' => 'House'],
                                            'villa' => ['uz' => 'Villa', 'ru' => 'Вилла', 'en' => 'Villa'],
                                            'land' => ['uz' => 'Yer', 'ru' => 'Земля', 'en' => 'Land'],
                                            'commercial' => ['uz' => 'Tijorat', 'ru' => 'Коммерческая', 'en' => 'Commercial'],
                                            'office' => ['uz' => 'Ofis', 'ru' => 'Офис', 'en' => 'Office'],
                                        ];
                                        $type = $propertyTypes[$property->property_type] ?? ['uz' => $property->property_type, 'ru' => $property->property_type, 'en' => $property->property_type];
                                    @endphp
                                    {{ $type[$locale] ?? $type['uz'] }}
                                </li>
                                @endif
                                @if($property->year_built)
                                <li><strong>{{ $locale === 'uz' ? 'Qurilgan yili:' : ($locale === 'ru' ? 'Год постройки:' : 'Year Built:') }}</strong>{{ $property->year_built }}</li>
                                @endif
                                @if($property->floors)
                                <li><strong>{{ $locale === 'uz' ? 'Qavatlar:' : ($locale === 'ru' ? 'Этажи:' : 'Floors:') }}</strong>{{ $property->floors }}</li>
                                @endif
                                @if($property->floor)
                                <li><strong>{{ $locale === 'uz' ? 'Qavat:' : ($locale === 'ru' ? 'Этаж:' : 'Floor:') }}</strong>{{ $property->floor }}</li>
                                @endif
                                @if($property->construction_material)
                                <li><strong>{{ $locale === 'uz' ? 'Qurilish materiali:' : ($locale === 'ru' ? 'Материал:' : 'Construction Material:') }}</strong>
                                    @php
                                        $materials = [
                                            'gisht' => ['uz' => 'G\'isht', 'ru' => 'Кирпич', 'en' => 'Brick'],
                                            'pishgan_gisht' => ['uz' => 'Pishgan g\'isht', 'ru' => 'Обожженный кирпич', 'en' => 'Fired brick'],
                                            'beton' => ['uz' => 'Beton', 'ru' => 'Бетон', 'en' => 'Concrete'],
                                            'yogoch' => ['uz' => 'Yog\'och', 'ru' => 'Дерево', 'en' => 'Wood'],
                                            'paneli' => ['uz' => 'Paneli', 'ru' => 'Панельный', 'en' => 'Panel'],
                                            'monolit' => ['uz' => 'Monolit', 'ru' => 'Монолит', 'en' => 'Monolith'],
                                            'boshqa' => ['uz' => 'Boshqa', 'ru' => 'Другое', 'en' => 'Other'],
                                        ];
                                        $material = $materials[$property->construction_material] ?? ['uz' => $property->construction_material, 'ru' => $property->construction_material, 'en' => $property->construction_material];
                                    @endphp
                                    {{ $material[$locale] ?? $material['uz'] }}
                                </li>
                                @endif
                                @if($property->status)
                                <li><strong>{{ $locale === 'uz' ? 'Holat:' : ($locale === 'ru' ? 'Статус:' : 'Status:') }}</strong>
                                    @if($property->status === 'published')
                                        {{ $locale === 'uz' ? 'Nashr qilingan' : ($locale === 'ru' ? 'Опубликовано' : 'Published') }}
                                    @elseif($property->status === 'pending')
                                        {{ $locale === 'uz' ? 'Tasdiqlashda' : ($locale === 'ru' ? 'На рассмотрении' : 'Pending') }}
                                    @else
                                        {{ $property->status }}
                                    @endif
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                </div>

                <!-- Single Block Wrap -->
                <div class="property_block_wrap style-2">

                    <div class="property_block_wrap_header">
                        <a data-bs-toggle="collapse" data-parent="#dsrp" data-bs-target="#clTwo" aria-controls="clTwo" href="javascript:void(0);" aria-expanded="true">
                            <h4 class="property_block_title">{{ $locale === 'uz' ? 'Tavsif' : ($locale === 'ru' ? 'Описание' : 'Description') }}</h4>
                        </a>
                    </div>
                    <div id="clTwo" class="panel-collapse collapse show">
                        <div class="block-body">
                            @if($property->description)
                                <div>{!! nl2br(e($property->description)) !!}</div>
                            @elseif($property->short_description)
                                <div>{!! nl2br(e($property->short_description)) !!}</div>
                            @else
                                <p>{{ $locale === 'uz' ? 'Tavsif mavjud emas.' : ($locale === 'ru' ? 'Описание недоступно.' : 'Description not available.') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Single Block Wrap -->
                <div class="property_block_wrap style-2">

                    <div class="property_block_wrap_header">
                        <a data-bs-toggle="collapse" data-parent="#amen"  data-bs-target="#clThree" aria-controls="clThree" href="javascript:void(0);" aria-expanded="true">
                            <h4 class="property_block_title">{{ $locale === 'uz' ? 'Qulayliklar' : ($locale === 'ru' ? 'Удобства' : 'Amenities') }}</h4>
                        </a>
                    </div>

                    <div id="clThree" class="panel-collapse collapse show">
                        <div class="block-body">
                            @php
                                $features = [];
                                if ($property->features) {
                                    if (is_string($property->features)) {
                                        $features = json_decode($property->features, true) ?? [];
                                    } else {
                                        $features = $property->features;
                                    }
                                }
                            @endphp
                            @if(!empty($features))
                            <ul class="avl-features third color">
                                    @foreach($features as $feature)
                                        <li>{{ $feature }}</li>
                                    @endforeach
                            </ul>
                            @else
                                <p>{{ $locale === 'uz' ? 'Qulayliklar mavjud emas.' : ($locale === 'ru' ? 'Удобства недоступны.' : 'Amenities not available.') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Single Block Wrap -->
                <div class="property_block_wrap style-2">

                    <div class="property_block_wrap_header">
                        <a data-bs-toggle="collapse" data-parent="#loca"  data-bs-target="#clSix" aria-controls="clSix" href="javascript:void(0);" aria-expanded="true" class="collapsed">
                            <h4 class="property_block_title">{{ $locale === 'uz' ? 'Manzil' : ($locale === 'ru' ? 'Местоположение' : 'Location') }}</h4>
                        </a>
                    </div>

                    <div id="clSix" class="panel-collapse collapse">
                        <div class="block-body">
                            @if($property->latitude && $property->longitude)
                            <div class="map-container">
                                    <iframe 
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3000!2d{{ $property->longitude }}!3d{{ $property->latitude }}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2z{{ $property->latitude }},{{ $property->longitude }}!5e0!3m2!1sen!2s!4v{{ time() }}!5m2!1sen!2s" 
                                        width="100%" 
                                        height="450" 
                                        style="border:0;" 
                                        allowfullscreen="" 
                                        loading="lazy" 
                                        referrerpolicy="no-referrer-when-downgrade">
                                    </iframe>
                            </div>
                            @else
                                <p>{{ $locale === 'uz' ? 'Xarita koordinatalari mavjud emas.' : ($locale === 'ru' ? 'Координаты карты недоступны.' : 'Map coordinates not available.') }}</p>
                            @endif
                        </div>
                    </div>

                </div>

                <!-- Single Block Wrap -->
                <div class="property_block_wrap style-2">

                    <div class="property_block_wrap_header">
                        <a data-bs-toggle="collapse" data-parent="#clSev"  data-bs-target="#clSev" aria-controls="clOne" href="javascript:void(0);" aria-expanded="true" class="collapsed">
                            <h4 class="property_block_title">{{ $locale === 'uz' ? 'Galereya' : ($locale === 'ru' ? 'Галерея' : 'Gallery') }}</h4>
                        </a>
                    </div>

                    <div id="clSev" class="panel-collapse collapse">
                        <div class="block-body">
                            @if(!empty($propertyImages))
                            <ul class="list-gallery-inline">
                                    @foreach($propertyImages as $image)
                                <li>
                                            <a href="{{ asset('storage/' . $image) }}" class="mfp-gallery">
                                                <img src="{{ asset('storage/' . $image) }}" class="img-fluid mx-auto" alt="{{ $property->title }}" />
                                            </a>
                                </li>
                                    @endforeach
                            </ul>
                            @else
                                <p>{{ $locale === 'uz' ? 'Rasmlar mavjud emas.' : ($locale === 'ru' ? 'Фотографии недоступны.' : 'Images not available.') }}</p>
                            @endif
                        </div>
                    </div>

                </div>

                <!-- Single Reviews Block -->
                <div class="property_block_wrap style-2">

                    <div class="property_block_wrap_header">
                        <a data-bs-toggle="collapse" data-parent="#rev"  data-bs-target="#clEight" aria-controls="clEight" href="javascript:void(0);" aria-expanded="true">
                            <h4 class="property_block_title">
                                {{ $property->approvedComments->count() }} 
                                {{ $locale === 'uz' ? 'Sharh' : ($locale === 'ru' ? 'Отзывов' : 'Reviews') }}
                            </h4>
                        </a>
                    </div>

                    <div id="clEight" class="panel-collapse collapse show">
                        <div class="block-body">
                            @if($property->approvedComments->count() > 0)
                            <div class="author-review">
                                <div class="comment-list">
                                    <ul>
                                            @foreach($property->approvedComments->take(5) as $comment)
                                        <li class="article_comments_wrap">
                                            <article>
                                                <div class="article_comments_thumb">
                                                            @if($comment->user && $comment->user->avatar)
                                                                <img src="{{ asset('storage/' . $comment->user->avatar) }}" alt="{{ $comment->user->name }}">
                                                            @elseif($comment->user)
                                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name) }}&background=random" alt="{{ $comment->user->name }}">
                                                            @else
                                                                <img src="https://ui-avatars.com/api/?name={{ $locale === 'uz' ? 'Mehmon' : ($locale === 'ru' ? 'Гость' : 'Guest') }}&background=random" alt="{{ $locale === 'uz' ? 'Mehmon' : ($locale === 'ru' ? 'Гость' : 'Guest') }}">
                                                            @endif
                                                </div>
                                                <div class="comment-details">
                                                    <div class="comment-meta">
                                                        <div class="comment-left-meta">
                                                                    <h4 class="author-name">{{ $comment->user ? $comment->user->name : ($locale === 'uz' ? 'Mehmon' : ($locale === 'ru' ? 'Гость' : 'Guest')) }}</h4>
                                                                    <div class="comment-date">{{ $comment->created_at->format('d M Y') }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="comment-text">
                                                                <p>{{ $comment->content }}</p>
                                                    </div>
                                                </div>
                                            </article>
                                        </li>
                                            @endforeach
                                    </ul>
                                    </div>
                                </div>
                                @if($property->approvedComments->count() > 5)
                                    <a href="#" class="reviews-checked text-main">
                                        <i class="fas fa-arrow-alt-circle-down mr-2"></i>
                                        {{ $locale === 'uz' ? 'Ko\'proq sharhlarni ko\'rish' : ($locale === 'ru' ? 'Посмотреть больше отзывов' : 'See More Reviews') }}
                                    </a>
                                @endif
                            @else
                                <p>{{ $locale === 'uz' ? 'Hozircha sharhlar yo\'q.' : ($locale === 'ru' ? 'Пока нет отзывов.' : 'No reviews yet.') }}</p>
                            @endif
                        </div>
                    </div>

                </div>


            </div>

            <!-- property Sidebar -->
            <div class="col-lg-4 col-md-12 col-sm-12">

                <!-- Like And Share -->
                <div class="like_share_wrap b-0">
                    <ul class="like_share_list">
                        <li><a href="JavaScript:Void(0);" class="btn btn-gray" data-toggle="tooltip" data-original-title="{{ $locale === 'uz' ? 'Ulashish' : ($locale === 'ru' ? 'Поделиться' : 'Share') }}">
                            <i class="fas fa-share"></i>{{ $locale === 'uz' ? 'Ulashish' : ($locale === 'ru' ? 'Поделиться' : 'Share') }}
                        </a></li>
                        <li><a href="JavaScript:Void(0);" class="btn btn-gray" data-toggle="tooltip" data-original-title="{{ $locale === 'uz' ? 'Saqlash' : ($locale === 'ru' ? 'Сохранить' : 'Save') }}">
                            <i class="fas fa-heart"></i>{{ $locale === 'uz' ? 'Saqlash' : ($locale === 'ru' ? 'Сохранить' : 'Save') }}
                        </a></li>
                    </ul>
                </div>

                <div class="details-sidebar">

                    <!-- Agent Detail -->
                    @if($property->user)
                    <div class="sides-widget">
                        <div class="sides-widget-header bg-main">
                            <div class="agent-photo">
                                @if($property->user->avatar)
                                    <img src="{{ asset('storage/' . $property->user->avatar) }}" alt="{{ $property->user->name }}">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($property->user->name) }}&background=random" alt="{{ $property->user->name }}">
                                @endif
                            </div>
                            <div class="sides-widget-details">
                                <h4><a href="#">{{ $property->user->name }}</a></h4>
                                @if($property->owner_phone)
                                    <span><i class="lni-phone-handset"></i>{{ $property->owner_phone }}</span>
                                @elseif($property->user->phone)
                                    <span><i class="lni-phone-handset"></i>{{ $property->user->phone }}</span>
                                @endif
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="sides-widget-body simple-form">
                            <form action="#" method="POST">
                                @csrf
                            <div class="form-group">
                                    <label>{{ $locale === 'uz' ? 'Email' : ($locale === 'ru' ? 'Эл. почта' : 'Email') }}</label>
                                    <input type="email" name="email" class="form-control" placeholder="{{ $locale === 'uz' ? 'Sizning emailingiz' : ($locale === 'ru' ? 'Ваш email' : 'Your Email') }}" required>
                                </div>
                            <div class="form-group">
                                    <label>{{ $locale === 'uz' ? 'Telefon raqami' : ($locale === 'ru' ? 'Телефон' : 'Phone No.') }}</label>
                                    <input type="text" name="phone" class="form-control" placeholder="{{ $locale === 'uz' ? 'Sizning telefon raqamingiz' : ($locale === 'ru' ? 'Ваш телефон' : 'Your Phone') }}" required>
                                </div>
                            <div class="form-group">
                                    <label>{{ $locale === 'uz' ? 'Xabar' : ($locale === 'ru' ? 'Сообщение' : 'Message') }}</label>
                                    <textarea name="message" class="form-control" placeholder="{{ $locale === 'uz' ? 'Men bu uy-joyga qiziqaman.' : ($locale === 'ru' ? 'Меня интересует эта недвижимость.' : 'I\'m interested in this property.') }}">{{ $locale === 'uz' ? 'Men bu uy-joyga qiziqaman.' : ($locale === 'ru' ? 'Меня интересует эта недвижимость.' : 'I\'m interested in this property.') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-light-main fw-medium rounded full-width">{{ $locale === 'uz' ? 'Xabar yuborish' : ($locale === 'ru' ? 'Отправить сообщение' : 'Send Message') }}</button>
                            </form>
                        </div>
                    </div>
                    @endif


                    <!-- Related Properties -->
                    @if(isset($relatedProperties) && $relatedProperties->count() > 0)
                    <div class="sidebar-widgets">
                        <h4>{{ $locale === 'uz' ? 'O\'xshash uy-joylar' : ($locale === 'ru' ? 'Похожие объекты' : 'Related Properties') }}</h4>
                        <div class="sidebar_featured_property">
                            @foreach($relatedProperties->take(4) as $relatedProperty)
                            <div class="sides_list_property">
                                <div class="sides_list_property_thumb">
                                        @if($relatedProperty->featured_image)
                                            <img src="{{ asset('storage/' . $relatedProperty->featured_image) }}" class="img-fluid" alt="{{ $relatedProperty->title }}">
                                        @elseif($relatedProperty->images && count($relatedProperty->images) > 0)
                                            <img src="{{ asset('storage/' . $relatedProperty->images[0]) }}" class="img-fluid" alt="{{ $relatedProperty->title }}">
                                        @else
                                            <img src="https://via.placeholder.com/300x200?text=No+Image" class="img-fluid" alt="{{ $relatedProperty->title }}">
                                        @endif
                                </div>
                                <div class="sides_list_property_detail">
                                        <h4><a href="{{ route('listing.show', $relatedProperty->slug) }}">{{ $relatedProperty->title }}</a></h4>
                                        <span><i class="fa-solid fa-location-dot"></i>{{ $relatedProperty->city }}{{ $relatedProperty->region ? ', ' . $relatedProperty->region : '' }}</span>
                                    <div class="lists_property_price">
                                        <div class="lists_property_types">
                                                <div class="property_types_vlix {{ $relatedProperty->listing_type === 'sale' ? 'sale' : '' }}">
                                                    {{ $relatedProperty->listing_type === 'sale' ? ($locale === 'uz' ? 'Sotuv' : ($locale === 'ru' ? 'Продажа' : 'For Sale')) : ($locale === 'uz' ? 'Ijaraga' : ($locale === 'ru' ? 'Аренда' : 'For Rent')) }}
                                </div>
                                        </div>
                                        <div class="lists_property_price_value">
                                                @if($relatedProperty->price)
                                                    <h4>
                                                        {{ number_format($relatedProperty->price, 0, '.', ' ') }} 
                                                        {{ $relatedProperty->currency === 'USD' ? '$' : ($relatedProperty->currency === 'EUR' ? '€' : 'so\'m') }}
                                                    </h4>
                                                @endif
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</section>
<!-- ============================ Property Detail End ================================== -->

@endsection

