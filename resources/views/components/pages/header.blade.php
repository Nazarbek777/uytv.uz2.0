<!-- ============================================================== -->
<!-- Top header  -->
<!-- ============================================================== -->
<!-- Start Navigation -->
<div class="header
 @if (request()->is('/'))
 header-transparent
 @endif
   @if (!request()->is('/'))
        header-light head-shadow
    @endif ">
    <div class="container">
        <nav id="navigation" class="navigation navigation-landscape">
            <div class="nav-header">
                <a class="nav-brand text-logo exchange" href="#">
								<span class="svg-icon svg-icon-2hx">
									<svg width="50" height="50" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
										<path
                                            d="M15.8797 15.375C15.9797 15.075 15.9797 14.775 15.9797 14.475C15.9797 13.775 15.7797 13.075 15.4797 12.475C14.7797 11.275 13.4797 10.475 11.9797 10.475C11.7797 10.475 11.5797 10.475 11.3797 10.575C7.37971 11.075 4.67971 14.575 2.57971 18.075L10.8797 3.675C11.3797 2.775 12.5797 2.775 13.0797 3.675C13.1797 3.875 13.2797 3.975 13.3797 4.175C15.2797 7.575 16.9797 11.675 15.8797 15.375Z"
                                            fill="currentColor"/>
										<path opacity="0.3"
                                              d="M20.6797 20.6749C16.7797 20.6749 12.3797 20.275 9.57972 17.575C10.2797 18.075 11.0797 18.375 11.9797 18.375C13.4797 18.375 14.7797 17.5749 15.4797 16.2749C15.6797 15.9749 15.7797 15.675 15.7797 15.375V15.2749C16.8797 11.5749 15.2797 7.47495 13.2797 4.07495L21.6797 18.6749C22.2797 19.5749 21.6797 20.6749 20.6797 20.6749ZM8.67972 18.6749C8.17972 17.8749 7.97972 16.975 7.77972 15.975C7.37972 13.575 8.67972 10.775 11.3797 10.375C7.37972 10.875 4.67972 14.375 2.57972 17.875C2.47972 18.075 2.27972 18.375 2.17972 18.575C1.67972 19.475 2.27972 20.475 3.27972 20.475H10.3797C9.67972 20.175 9.07972 19.3749 8.67972 18.6749Z"
                                              fill="currentColor"/>
									</svg>
								</span><h5 class="fs-3 fw-semibold ms-1 my-0">UyTv</h5>
                </a>
                <div class="nav-toggle"></div>
                <div class="mobile_nav">
                    <ul>
                        @auth
                            <li>
                                <a href="{{ route('provider.properties.index') }}" class="text-muted">
                                    <i  style="font-size: 21px;" class="bi bi-person-fill"></i>
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="JavaScript:Void(0);" data-bs-toggle="modal" data-bs-target="#login"
                                   class="text-muted">
											<span class="svg-icon svg-icon-2hx">
												<svg width="35" height="35" viewBox="0 0 18 18" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
													<path opacity="0.3"
                                                          d="M16.5 9C16.5 13.125 13.125 16.5 9 16.5C4.875 16.5 1.5 13.125 1.5 9C1.5 4.875 4.875 1.5 9 1.5C13.125 1.5 16.5 4.875 16.5 9Z"
                                                          fill="currentColor"/>
													<path
                                                        d="M9 16.5C10.95 16.5 12.75 15.75 14.025 14.55C13.425 12.675 11.4 11.25 9 11.25C6.6 11.25 4.57499 12.675 3.97499 14.55C5.24999 15.75 7.05 16.5 9 16.5Z"
                                                        fill="currentColor"/>
													<rect x="7" y="6" width="4" height="4" rx="2" fill="currentColor"/>
												</svg>
											</span>
                                </a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
            <div class="nav-menus-wrapper" style="transition-property: none;">
                <ul class="nav-menu">
                    @php
                        $locale = app()->getLocale();
                    @endphp
                    <li class="active"><a href="/">{{ $locale === 'uz' ? 'Bosh sahifa' : ($locale === 'ru' ? 'Главная' : 'Home') }}</a></li>
                    <li><a href="/">{{ $locale === 'uz' ? 'Biz haqimizda' : ($locale === 'ru' ? 'О нас' : 'About') }}</a></li>
                    <li><a href="{{route('listings')}}">{{ $locale === 'uz' ? 'Uy-joylar' : ($locale === 'ru' ? 'Недвижимость' : 'Listings') }}</a></li>
                    <li>
                        <a href="{{route('map')}}">{{ $locale === 'uz' ? 'Xarita' : ($locale === 'ru' ? 'Карта' : 'Map') }}</a>
                    </li>
                    <li><a href="">{{ $locale === 'uz' ? 'Aloqa' : ($locale === 'ru' ? 'Контакты' : 'Contact') }}</a></li>
                    <!-- Language Switcher -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle fw-medium text-invers" data-bs-toggle="dropdown"
                           aria-expanded="false">
                            <i class="bi bi-globe me-1"></i>
                            @php
                                $currentLocale = app()->getLocale();
                                $localeNames = [
                                    'uz' => 'O\'zbek',
                                    'ru' => 'Русский',
                                    'en' => 'English'
                                ];
                            @endphp
                            {{ $localeNames[$currentLocale] ?? 'O\'zbek' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item {{ $currentLocale == 'uz' ? 'active' : '' }}"
                                   href="{{ route('locale.switch', 'uz') }}">🇺🇿 O'zbek</a></li>
                            <li><a class="dropdown-item {{ $currentLocale == 'ru' ? 'active' : '' }}"
                                   href="{{ route('locale.switch', 'ru') }}">🇷🇺 Русский</a></li>
                            <li><a class="dropdown-item {{ $currentLocale == 'en' ? 'active' : '' }}"
                                   href="{{ route('locale.switch', 'en') }}">🇬🇧 English</a></li>
                        </ul>
                    </li>
                </ul>

                <ul class="nav-menu align-to-right">
                    @auth
                    <li>
                        <a href="{{ route('provider.properties.index') }}" class="fw-medium text-invers">
                            {{\Illuminate\Support\Facades\Auth::user()->name}}
                        </a>
                    </li>

                    @else
                    <li>
                        <a href="JavaScript:Void(0);" data-bs-toggle="modal" data-bs-target="#login"
                           class="fw-medium text-invers">
										<span class="svg-icon svg-icon-2hx me-1">
											<svg width="22" height="22" viewBox="0 0 18 18" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
												<path opacity="0.3"
                                                      d="M16.5 9C16.5 13.125 13.125 16.5 9 16.5C4.875 16.5 1.5 13.125 1.5 9C1.5 4.875 4.875 1.5 9 1.5C13.125 1.5 16.5 4.875 16.5 9Z"
                                                      fill="currentColor"/>
												<path
                                                    d="M9 16.5C10.95 16.5 12.75 15.75 14.025 14.55C13.425 12.675 11.4 11.25 9 11.25C6.6 11.25 4.57499 12.675 3.97499 14.55C5.24999 15.75 7.05 16.5 9 16.5Z"
                                                    fill="currentColor"/>
												<rect x="7" y="6" width="4" height="4" rx="2" fill="currentColor"/>
											</svg>
										</span>
                            {{ $locale === 'uz' ? 'Kirish' : ($locale === 'ru' ? 'Войти' : 'Sign In') }}
                        </a>
                    </li>
                    @endauth

                </ul>
            </div>
        </nav>
    </div>
</div>
