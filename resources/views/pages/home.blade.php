@extends('layouts.page')
@section('content')

    <!-- ============================ Hero Banner  Start================================== -->
    <div class="image-bottom hero-banner" style="background:#087ce1 url(https://shreethemes.net/resido-2.3/resido/assets/img/banner.png) no-repeat;" data-overlay="0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9 col-md-11 col-sm-12">
                    <div class="inner-banner-text text-center mb-2">
                        <h2 class="mb-4"><span class="font-normal">Find Your</span> Perfect Place.</h2>
                        <p class="fs-5 fw-light px-xl-4 px-lg-4">Cicero famously orated against his political opponent Lucius Sergius Catilina. Occasionally the first Oration against Catiline is taken for type specimens</p>
                    </div>
                    <div class="full-search-2 eclip-search italian-search hero-search-radius shadow-hard mt-5">
                        <div class="hero-search-content">
                            <div class="row">

                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 b-r">
                                    <div class="form-group">
                                        <div class="choose-propert-type">
                                            <ul>
                                                <li>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" id="typbuy" name="typeprt">
                                                        <label class="form-check-label" for="typbuy">
                                                            For Buy
                                                        </label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" id="typrent" name="typeprt" checked>
                                                        <label class="form-check-label" for="typrent">
                                                            For Rent
                                                        </label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-7 col-lg-7 col-md-5 col-sm-12 p-md-0 elio">
                                    <div class="form-group border-start borders">
                                        <div class="position-relative">
                                            <input type="text" class="form-control border-0 ps-5" placeholder="Search for a location">
                                            <div class="position-absolute top-50 start-0 translate-middle-y ms-2">
														<span class="svg-icon text-main svg-icon-2hx">
															<svg width="25" height="25" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path opacity="0.3" d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z" fill="currentColor"/>
																<path d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z" fill="currentColor"/>
															</svg>
														</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-dark full-width">Search</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- ============================ Hero Banner End ================================== -->


    <!-- ============================ Popular Promotions Start ================================== -->
    <section class="py-5 pb-0">
        <div class="container">

            <div class="row justify-content-center g-3">

                <!-- Single Promotion -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <div class="card rounded-2 p-3 border">

                        <div class="mortgage-caption mb-3">
                            <h5 class="fs-5 mb-0">Mortgage</h5>
                            <p>Special offer for houses in Chicago</p>
                        </div>

                        <div class="mortgage-footer d-flex align-items-center justify-content-between">
                            <div class="promotion-rates">
                                <span class="text-md text-muted">Rates</span>
                                <h6 class="fs-5 fw-medium m-0">4.42%</h6>
                            </div>
                            <div class="promotion-bank"><img src="https://shreethemes.net/resido-2.3/resido/assets/img/bank-1.png" class="img-fluid w-20" alt="Bank 1"></div>
                        </div>

                    </div>
                </div>

                <!-- Single Promotion -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <div class="card rounded-2 p-3 border">

                        <div class="mortgage-caption mb-3">
                            <h5 class="fs-5 mb-0">Mortgage</h5>
                            <p>Special offer for houses in Chicago</p>
                        </div>

                        <div class="mortgage-footer d-flex align-items-center justify-content-between">
                            <div class="promotion-rates">
                                <span class="text-md text-muted">Rates</span>
                                <h6 class="fs-5 fw-medium m-0">4.50%</h6>
                            </div>
                            <div class="promotion-bank"><img src="https://shreethemes.net/resido-2.3/resido/assets/img/bank-2.png" class="img-fluid w-20" alt="Bank 1"></div>
                        </div>

                    </div>
                </div>

                <!-- Single Promotion -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <div class="card rounded-2 p-3 border">

                        <div class="mortgage-caption mb-3">
                            <h5 class="fs-5 mb-0">Mortgage</h5>
                            <p>Special offer for houses in Chicago</p>
                        </div>

                        <div class="mortgage-footer d-flex align-items-center justify-content-between">
                            <div class="promotion-rates">
                                <span class="text-md text-muted">Rates</span>
                                <h6 class="fs-5 fw-medium m-0">7.12%</h6>
                            </div>
                            <div class="promotion-bank"><img src="https://shreethemes.net/resido-2.3/resido/assets/img/bank-3.png" class="img-fluid w-20" alt="Bank 1"></div>
                        </div>

                    </div>
                </div>

                <!-- Single Promotion -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <div class="card rounded-2 p-3 border">

                        <div class="mortgage-caption mb-3">
                            <h5 class="fs-5 mb-0">Mortgage</h5>
                            <p>Special offer for houses in Chicago</p>
                        </div>

                        <div class="mortgage-footer d-flex align-items-center justify-content-between">
                            <div class="promotion-rates">
                                <span class="text-md text-muted">Special Discount</span>
                                <h6 class="fs-5 fw-medium m-0">Up to 7%</h6>
                            </div>
                            <div class="promotion-bank"><img src="https://shreethemes.net/resido-2.3/resido/assets/img/bank-4.png" class="img-fluid w-20" alt="Bank 1"></div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </section>
    <!-- ============================ Popular Promotions End ================================== -->



    <!-- ============================ Latest Property For Sale Start ================================== -->
    <section>
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-10 text-center">
                    <div class="tabOptions">
                        <ul class="nav nav-pills simple-tabs gray-simple mb-4" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-sell-tab" data-bs-toggle="pill" data-bs-target="#pills-sell" type="button" role="tab" aria-controls="pills-sell" aria-selected="true">Listing for Sell</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-rent-tab" data-bs-toggle="pill" data-bs-target="#pills-rent" type="button" role="tab" aria-controls="pills-rent" aria-selected="false">Listing for Rent</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12">

                    <!-- Property for Rent -->
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-sell" role="tabpanel" aria-labelledby="pills-sell-tab" tabindex="0">
                            <div class="row align-items-center justify-content-center g-4">

                                <!-- Single Property -->
                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="property-listing card border rounded-3">

                                        <div class="listing-img-wrapper p-3">
                                            <div class="list-img-slide position-relative">
                                                <div class="position-absolute top-0 left-0 ms-3 mt-3 z-1">
                                                    <div class="label verified-listing d-inline-flex align-items-center justify-content-center">
																<span class="svg-icon text-light svg-icon-2hx me-1">
																	<svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"></path>
																		<path d="M14.854 11.321C14.7568 11.2282 14.6388 11.1818 14.4998 11.1818H14.3333V10.2272C14.3333 9.61741 14.1041 9.09378 13.6458 8.65628C13.1875 8.21876 12.639 8 12 8C11.361 8 10.8124 8.21876 10.3541 8.65626C9.89574 9.09378 9.66663 9.61739 9.66663 10.2272V11.1818H9.49999C9.36115 11.1818 9.24306 11.2282 9.14583 11.321C9.0486 11.4138 9 11.5265 9 11.6591V14.5227C9 14.6553 9.04862 14.768 9.14583 14.8609C9.24306 14.9536 9.36115 15 9.49999 15H14.5C14.6389 15 14.7569 14.9536 14.8542 14.8609C14.9513 14.768 15 14.6553 15 14.5227V11.6591C15.0001 11.5265 14.9513 11.4138 14.854 11.321ZM13.3333 11.1818H10.6666V10.2272C10.6666 9.87594 10.7969 9.57597 11.0573 9.32743C11.3177 9.07886 11.6319 8.9546 12 8.9546C12.3681 8.9546 12.6823 9.07884 12.9427 9.32743C13.2031 9.57595 13.3333 9.87594 13.3333 10.2272V11.1818Z" fill="currentColor"></path>
																	</svg>
																</span>Verified
                                                    </div>
                                                </div>
                                                <div class="clicks rounded-3 overflow-hidden mb-0">
                                                    <a href="single-property-1.html"><img src="https://shreethemes.net/resido-2.3/resido/assets/img/p-1.jpg" class="img-fluid" alt="" /></a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="listing-caption-wrapper px-3">
                                            <div class="listing-detail-wrapper">
                                                <div class="listing-short-detail-wrap">
                                                    <div class="listing-short-detail">
                                                        <div class="d-flex align-items-center">
                                                            <span class="label for-sale prt-type me-2">For Sell</span><span class="label property-type property-cats">Apartment</span>
                                                        </div>
                                                        <h4 class="listing-name fw-medium fs-5 mb-1"><a href="single-property-1.html">The Green Canton Chrysler</a></h4>
                                                        <div class="prt-location text-muted-2">
																	<span class="svg-icon svg-icon-2hx">
																		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<path opacity="0.3" d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z" fill="currentColor"/>
																			<path d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z" fill="currentColor"/>
																		</svg>
																	</span>
                                                            210 Zirak Road, Canada
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="price-features-wrapper">
                                                <div class="list-fx-features d-flex align-items-center justify-content-between">
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-building-shield fs-sm"></i></div><span class="text-muted-2">4BHK</span>
                                                    </div>
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-bed fs-sm"></i></div><span class="text-muted-2">3 Beds</span>
                                                    </div>
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-clone fs-sm"></i></div><span class="text-muted-2">1800 SQFT</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="listing-detail-footer d-flex align-items-center justify-content-between py-4">
                                                <div class="listing-short-detail-flex">
                                                    <h6 class="listing-card-info-price m-0">$235.8M</h6>
                                                </div>
                                                <div class="footer-flex">
                                                    <a href="property-detail.html" class="prt-view">
																<span class="svg-icon text-main svg-icon-2hx">
																	<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M15.43 8.56949L10.744 15.1395C10.6422 15.282 10.5804 15.4492 10.5651 15.6236C10.5498 15.7981 10.5815 15.9734 10.657 16.1315L13.194 21.4425C13.2737 21.6097 13.3991 21.751 13.5557 21.8499C13.7123 21.9488 13.8938 22.0014 14.079 22.0015H14.117C14.3087 21.9941 14.4941 21.9307 14.6502 21.8191C14.8062 21.7075 14.9261 21.5526 14.995 21.3735L21.933 3.33649C22.0011 3.15918 22.0164 2.96594 21.977 2.78013C21.9376 2.59432 21.8452 2.4239 21.711 2.28949L15.43 8.56949Z" fill="currentColor"/>
																		<path opacity="0.3" d="M20.664 2.06648L2.62602 9.00148C2.44768 9.07085 2.29348 9.19082 2.1824 9.34663C2.07131 9.50244 2.00818 9.68731 2.00074 9.87853C1.99331 10.0697 2.04189 10.259 2.14054 10.4229C2.23919 10.5869 2.38359 10.7185 2.55601 10.8015L7.86601 13.3365C8.02383 13.4126 8.19925 13.4448 8.37382 13.4297C8.54839 13.4145 8.71565 13.3526 8.85801 13.2505L15.43 8.56548L21.711 2.28448C21.5762 2.15096 21.4055 2.05932 21.2198 2.02064C21.034 1.98196 20.8409 1.99788 20.664 2.06648Z" fill="currentColor"/>
																	</svg>
																</span>
                                                    </a>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <!-- Single Property -->
                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="property-listing card border rounded-3">

                                        <div class="listing-img-wrapper p-3">
                                            <div class="list-img-slide position-relative">
                                                <div class="position-absolute top-0 left-0 ms-3 mt-3 z-1">
                                                    <div class="label super-agent d-inline-flex align-items-center justify-content-center">
																<span class="svg-icon text-light svg-icon-2hx me-1">
																	<svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"/>
																		<path d="M12.0006 11.1542C13.1434 11.1542 14.0777 10.22 14.0777 9.0771C14.0777 7.93424 13.1434 7 12.0006 7C10.8577 7 9.92348 7.93424 9.92348 9.0771C9.92348 10.22 10.8577 11.1542 12.0006 11.1542Z" fill="currentColor"/>
																		<path d="M15.5652 13.814C15.5108 13.6779 15.4382 13.551 15.3566 13.4331C14.9393 12.8163 14.2954 12.4081 13.5697 12.3083C13.479 12.2993 13.3793 12.3174 13.3067 12.3718C12.9257 12.653 12.4722 12.7981 12.0006 12.7981C11.5289 12.7981 11.0754 12.653 10.6944 12.3718C10.6219 12.3174 10.5221 12.2902 10.4314 12.3083C9.70578 12.4081 9.05272 12.8163 8.64456 13.4331C8.56293 13.551 8.49036 13.687 8.43595 13.814C8.40875 13.8684 8.41781 13.9319 8.44502 13.9864C8.51759 14.1133 8.60828 14.2403 8.68991 14.3492C8.81689 14.5215 8.95295 14.6757 9.10715 14.8208C9.23413 14.9478 9.37925 15.0657 9.52439 15.1836C10.2409 15.7188 11.1026 15.9999 11.9915 15.9999C12.8804 15.9999 13.7421 15.7188 14.4586 15.1836C14.6038 15.0748 14.7489 14.9478 14.8759 14.8208C15.021 14.6757 15.1661 14.5215 15.2931 14.3492C15.3838 14.2312 15.4655 14.1133 15.538 13.9864C15.5833 13.9319 15.5924 13.8684 15.5652 13.814Z" fill="currentColor"/>
																	</svg>
																</span>SuperAgent
                                                    </div>
                                                </div>
                                                <div class="clicks rounded-3 overflow-hidden mb-0">
                                                    <a href="single-property-1.html"><img src="https://shreethemes.net/resido-2.3/resido/assets/img/p-2.jpg" class="img-fluid" alt="" /></a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="listing-caption-wrapper px-3">
                                            <div class="listing-detail-wrapper">
                                                <div class="listing-short-detail-wrap">
                                                    <div class="listing-short-detail">
                                                        <div class="d-flex align-items-center">
                                                            <span class="label for-sale prt-type me-2">For Sell</span><span class="label property-type property-cats">House</span>
                                                        </div>
                                                        <h4 class="listing-name fw-medium fs-5 mb-1"><a href="single-property-1.html">Purple Flatiron House</a></h4>
                                                        <div class="prt-location text-muted-2">
																	<span class="svg-icon svg-icon-2hx">
																		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<path opacity="0.3" d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z" fill="currentColor"/>
																			<path d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z" fill="currentColor"/>
																		</svg>
																	</span>
                                                            210 Zirak Road, Canada
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="price-features-wrapper">
                                                <div class="list-fx-features d-flex align-items-center justify-content-between">
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-building-shield fs-sm"></i></div><span class="text-muted-2">3BHK</span>
                                                    </div>
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-bed fs-sm"></i></div><span class="text-muted-2">3 Beds</span>
                                                    </div>
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-clone fs-sm"></i></div><span class="text-muted-2">2200 SQFT</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="listing-detail-footer d-flex align-items-center justify-content-between py-4">
                                                <div class="listing-short-detail-flex">
                                                    <h6 class="listing-card-info-price m-0">$285.8M</h6>
                                                </div>
                                                <div class="footer-flex">
                                                    <a href="property-detail.html" class="prt-view">
																<span class="svg-icon text-main svg-icon-2hx">
																	<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M15.43 8.56949L10.744 15.1395C10.6422 15.282 10.5804 15.4492 10.5651 15.6236C10.5498 15.7981 10.5815 15.9734 10.657 16.1315L13.194 21.4425C13.2737 21.6097 13.3991 21.751 13.5557 21.8499C13.7123 21.9488 13.8938 22.0014 14.079 22.0015H14.117C14.3087 21.9941 14.4941 21.9307 14.6502 21.8191C14.8062 21.7075 14.9261 21.5526 14.995 21.3735L21.933 3.33649C22.0011 3.15918 22.0164 2.96594 21.977 2.78013C21.9376 2.59432 21.8452 2.4239 21.711 2.28949L15.43 8.56949Z" fill="currentColor"/>
																		<path opacity="0.3" d="M20.664 2.06648L2.62602 9.00148C2.44768 9.07085 2.29348 9.19082 2.1824 9.34663C2.07131 9.50244 2.00818 9.68731 2.00074 9.87853C1.99331 10.0697 2.04189 10.259 2.14054 10.4229C2.23919 10.5869 2.38359 10.7185 2.55601 10.8015L7.86601 13.3365C8.02383 13.4126 8.19925 13.4448 8.37382 13.4297C8.54839 13.4145 8.71565 13.3526 8.85801 13.2505L15.43 8.56548L21.711 2.28448C21.5762 2.15096 21.4055 2.05932 21.2198 2.02064C21.034 1.98196 20.8409 1.99788 20.664 2.06648Z" fill="currentColor"/>
																	</svg>
																</span>
                                                    </a>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <!-- Single Property -->
                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="property-listing card border rounded-3">

                                        <div class="listing-img-wrapper p-3">
                                            <div class="list-img-slide position-relative">
                                                <div class="position-absolute top-0 left-0 ms-3 mt-3 z-1">
                                                    <div class="label verified-listing d-inline-flex align-items-center justify-content-center">
																<span class="svg-icon text-light svg-icon-2hx me-1">
																	<svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"></path>
																		<path d="M14.854 11.321C14.7568 11.2282 14.6388 11.1818 14.4998 11.1818H14.3333V10.2272C14.3333 9.61741 14.1041 9.09378 13.6458 8.65628C13.1875 8.21876 12.639 8 12 8C11.361 8 10.8124 8.21876 10.3541 8.65626C9.89574 9.09378 9.66663 9.61739 9.66663 10.2272V11.1818H9.49999C9.36115 11.1818 9.24306 11.2282 9.14583 11.321C9.0486 11.4138 9 11.5265 9 11.6591V14.5227C9 14.6553 9.04862 14.768 9.14583 14.8609C9.24306 14.9536 9.36115 15 9.49999 15H14.5C14.6389 15 14.7569 14.9536 14.8542 14.8609C14.9513 14.768 15 14.6553 15 14.5227V11.6591C15.0001 11.5265 14.9513 11.4138 14.854 11.321ZM13.3333 11.1818H10.6666V10.2272C10.6666 9.87594 10.7969 9.57597 11.0573 9.32743C11.3177 9.07886 11.6319 8.9546 12 8.9546C12.3681 8.9546 12.6823 9.07884 12.9427 9.32743C13.2031 9.57595 13.3333 9.87594 13.3333 10.2272V11.1818Z" fill="currentColor"></path>
																	</svg>
																</span>Verified
                                                    </div>
                                                    <div class="label new-listing d-inline-flex align-items-center justify-content-center ms-1">
																<span class="svg-icon text-light svg-icon-2hx me-1">
																	<svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M19.0647 5.43757C19.3421 5.43757 19.567 5.21271 19.567 4.93534C19.567 4.65796 19.3421 4.43311 19.0647 4.43311C18.7874 4.43311 18.5625 4.65796 18.5625 4.93534C18.5625 5.21271 18.7874 5.43757 19.0647 5.43757Z" fill="currentColor"/>
																		<path d="M20.0692 9.48884C20.3466 9.48884 20.5714 9.26398 20.5714 8.98661C20.5714 8.70923 20.3466 8.48438 20.0692 8.48438C19.7918 8.48438 19.567 8.70923 19.567 8.98661C19.567 9.26398 19.7918 9.48884 20.0692 9.48884Z" fill="currentColor"/>
																		<path d="M12.0335 20.5714C15.6943 20.5714 18.9426 18.2053 20.1168 14.7338C20.1884 14.5225 20.1114 14.289 19.9284 14.161C19.746 14.034 19.5003 14.0418 19.3257 14.1821C18.2432 15.0546 16.9371 15.5156 15.5491 15.5156C12.2257 15.5156 9.48884 12.8122 9.48884 9.48886C9.48884 7.41079 10.5773 5.47137 12.3449 4.35752C12.5342 4.23832 12.6 4.00733 12.5377 3.79251C12.4759 3.57768 12.2571 3.42859 12.0335 3.42859C7.32556 3.42859 3.42857 7.29209 3.42857 12C3.42857 16.7079 7.32556 20.5714 12.0335 20.5714Z" fill="currentColor"/>
																		<path d="M13.0379 7.47998C13.8688 7.47998 14.5446 8.15585 14.5446 8.98668C14.5446 9.26428 14.7693 9.48891 15.0469 9.48891C15.3245 9.48891 15.5491 9.26428 15.5491 8.98668C15.5491 8.15585 16.225 7.47998 17.0558 7.47998C17.3334 7.47998 17.558 7.25535 17.558 6.97775C17.558 6.70015 17.3334 6.47552 17.0558 6.47552C16.225 6.47552 15.5491 5.76616 15.5491 4.93534C15.5491 4.65774 15.3245 4.43311 15.0469 4.43311C14.7693 4.43311 14.5446 4.65774 14.5446 4.93534C14.5446 5.76616 13.8688 6.47552 13.0379 6.47552C12.7603 6.47552 12.5357 6.70015 12.5357 6.97775C12.5357 7.25535 12.7603 7.47998 13.0379 7.47998Z" fill="currentColor"/>
																	</svg>
																</span>New
                                                    </div>
                                                </div>
                                                <div class="clicks rounded-3 overflow-hidden mb-0">
                                                    <a href="single-property-1.html"><img src="https://shreethemes.net/resido-2.3/resido/assets/img/p-3.jpg" class="img-fluid" alt="" /></a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="listing-caption-wrapper px-3">
                                            <div class="listing-detail-wrapper">
                                                <div class="listing-short-detail-wrap">
                                                    <div class="listing-short-detail">
                                                        <div class="d-flex align-items-center">
                                                            <span class="label for-sale prt-type me-2">For Sell</span><span class="label property-type property-cats">Building</span>
                                                        </div>
                                                        <h4 class="listing-name fw-medium fs-5 mb-1"><a href="single-property-1.html">Rustic Reunion Tower</a></h4>
                                                        <div class="prt-location text-muted-2">
																	<span class="svg-icon svg-icon-2hx">
																		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<path opacity="0.3" d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z" fill="currentColor"/>
																			<path d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z" fill="currentColor"/>
																		</svg>
																	</span>
                                                            210 Zirak Road, Canada
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="price-features-wrapper">
                                                <div class="list-fx-features d-flex align-items-center justify-content-between">
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-building-shield fs-sm"></i></div><span class="text-muted-2">3BHK</span>
                                                    </div>
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-bed fs-sm"></i></div><span class="text-muted-2">2 Beds</span>
                                                    </div>
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-clone fs-sm"></i></div><span class="text-muted-2">2500 SQFT</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="listing-detail-footer d-flex align-items-center justify-content-between py-4">
                                                <div class="listing-short-detail-flex">
                                                    <h6 class="listing-card-info-price m-0">$850M</h6>
                                                </div>
                                                <div class="footer-flex">
                                                    <a href="property-detail.html" class="prt-view">
																<span class="svg-icon text-main svg-icon-2hx">
																	<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M15.43 8.56949L10.744 15.1395C10.6422 15.282 10.5804 15.4492 10.5651 15.6236C10.5498 15.7981 10.5815 15.9734 10.657 16.1315L13.194 21.4425C13.2737 21.6097 13.3991 21.751 13.5557 21.8499C13.7123 21.9488 13.8938 22.0014 14.079 22.0015H14.117C14.3087 21.9941 14.4941 21.9307 14.6502 21.8191C14.8062 21.7075 14.9261 21.5526 14.995 21.3735L21.933 3.33649C22.0011 3.15918 22.0164 2.96594 21.977 2.78013C21.9376 2.59432 21.8452 2.4239 21.711 2.28949L15.43 8.56949Z" fill="currentColor"/>
																		<path opacity="0.3" d="M20.664 2.06648L2.62602 9.00148C2.44768 9.07085 2.29348 9.19082 2.1824 9.34663C2.07131 9.50244 2.00818 9.68731 2.00074 9.87853C1.99331 10.0697 2.04189 10.259 2.14054 10.4229C2.23919 10.5869 2.38359 10.7185 2.55601 10.8015L7.86601 13.3365C8.02383 13.4126 8.19925 13.4448 8.37382 13.4297C8.54839 13.4145 8.71565 13.3526 8.85801 13.2505L15.43 8.56548L21.711 2.28448C21.5762 2.15096 21.4055 2.05932 21.2198 2.02064C21.034 1.98196 20.8409 1.99788 20.664 2.06648Z" fill="currentColor"/>
																	</svg>
																</span>
                                                    </a>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <!-- Single Property -->
                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="property-listing card border rounded-3">

                                        <div class="listing-img-wrapper p-3">
                                            <div class="list-img-slide position-relative">
                                                <div class="position-absolute top-0 left-0 ms-3 mt-3 z-1">
                                                    <div class="label super-agent d-inline-flex align-items-center justify-content-center">
																<span class="svg-icon text-light svg-icon-2hx me-1">
																	<svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"/>
																		<path d="M12.0006 11.1542C13.1434 11.1542 14.0777 10.22 14.0777 9.0771C14.0777 7.93424 13.1434 7 12.0006 7C10.8577 7 9.92348 7.93424 9.92348 9.0771C9.92348 10.22 10.8577 11.1542 12.0006 11.1542Z" fill="currentColor"/>
																		<path d="M15.5652 13.814C15.5108 13.6779 15.4382 13.551 15.3566 13.4331C14.9393 12.8163 14.2954 12.4081 13.5697 12.3083C13.479 12.2993 13.3793 12.3174 13.3067 12.3718C12.9257 12.653 12.4722 12.7981 12.0006 12.7981C11.5289 12.7981 11.0754 12.653 10.6944 12.3718C10.6219 12.3174 10.5221 12.2902 10.4314 12.3083C9.70578 12.4081 9.05272 12.8163 8.64456 13.4331C8.56293 13.551 8.49036 13.687 8.43595 13.814C8.40875 13.8684 8.41781 13.9319 8.44502 13.9864C8.51759 14.1133 8.60828 14.2403 8.68991 14.3492C8.81689 14.5215 8.95295 14.6757 9.10715 14.8208C9.23413 14.9478 9.37925 15.0657 9.52439 15.1836C10.2409 15.7188 11.1026 15.9999 11.9915 15.9999C12.8804 15.9999 13.7421 15.7188 14.4586 15.1836C14.6038 15.0748 14.7489 14.9478 14.8759 14.8208C15.021 14.6757 15.1661 14.5215 15.2931 14.3492C15.3838 14.2312 15.4655 14.1133 15.538 13.9864C15.5833 13.9319 15.5924 13.8684 15.5652 13.814Z" fill="currentColor"/>
																	</svg>
																</span>SuperAgent
                                                    </div>
                                                </div>
                                                <div class="clicks rounded-3 overflow-hidden mb-0">
                                                    <a href="single-property-1.html"><img src="https://shreethemes.net/resido-2.3/resido/assets/img/p-4.jpg" class="img-fluid" alt="" /></a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="listing-caption-wrapper px-3">
                                            <div class="listing-detail-wrapper">
                                                <div class="listing-short-detail-wrap">
                                                    <div class="listing-short-detail">
                                                        <div class="d-flex align-items-center">
                                                            <span class="label for-sale prt-type me-2">For Sell</span><span class="label property-type property-cats">Condos</span>
                                                        </div>
                                                        <h4 class="listing-name fw-medium fs-5 mb-1"><a href="single-property-1.html">The Red Freedom Tower</a></h4>
                                                        <div class="prt-location text-muted-2">
																	<span class="svg-icon svg-icon-2hx">
																		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<path opacity="0.3" d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z" fill="currentColor"/>
																			<path d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z" fill="currentColor"/>
																		</svg>
																	</span>
                                                            210 Zirak Road, Canada
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="price-features-wrapper">
                                                <div class="list-fx-features d-flex align-items-center justify-content-between">
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-building-shield fs-sm"></i></div><span class="text-muted-2">4BHK</span>
                                                    </div>
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-bed fs-sm"></i></div><span class="text-muted-2">4 Beds</span>
                                                    </div>
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-clone fs-sm"></i></div><span class="text-muted-2">1900 SQFT</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="listing-detail-footer d-flex align-items-center justify-content-between py-4">
                                                <div class="listing-short-detail-flex">
                                                    <h6 class="listing-card-info-price m-0">$620.5M</h6>
                                                </div>
                                                <div class="footer-flex">
                                                    <a href="property-detail.html" class="prt-view">
																<span class="svg-icon text-main svg-icon-2hx">
																	<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M15.43 8.56949L10.744 15.1395C10.6422 15.282 10.5804 15.4492 10.5651 15.6236C10.5498 15.7981 10.5815 15.9734 10.657 16.1315L13.194 21.4425C13.2737 21.6097 13.3991 21.751 13.5557 21.8499C13.7123 21.9488 13.8938 22.0014 14.079 22.0015H14.117C14.3087 21.9941 14.4941 21.9307 14.6502 21.8191C14.8062 21.7075 14.9261 21.5526 14.995 21.3735L21.933 3.33649C22.0011 3.15918 22.0164 2.96594 21.977 2.78013C21.9376 2.59432 21.8452 2.4239 21.711 2.28949L15.43 8.56949Z" fill="currentColor"/>
																		<path opacity="0.3" d="M20.664 2.06648L2.62602 9.00148C2.44768 9.07085 2.29348 9.19082 2.1824 9.34663C2.07131 9.50244 2.00818 9.68731 2.00074 9.87853C1.99331 10.0697 2.04189 10.259 2.14054 10.4229C2.23919 10.5869 2.38359 10.7185 2.55601 10.8015L7.86601 13.3365C8.02383 13.4126 8.19925 13.4448 8.37382 13.4297C8.54839 13.4145 8.71565 13.3526 8.85801 13.2505L15.43 8.56548L21.711 2.28448C21.5762 2.15096 21.4055 2.05932 21.2198 2.02064C21.034 1.98196 20.8409 1.99788 20.664 2.06648Z" fill="currentColor"/>
																	</svg>
																</span>
                                                    </a>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <!-- Single Property -->
                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="property-listing card border rounded-3">

                                        <div class="listing-img-wrapper p-3">
                                            <div class="list-img-slide position-relative">
                                                <div class="position-absolute top-0 left-0 ms-3 mt-3 z-1">
                                                    <div class="label verified-listing d-inline-flex align-items-center justify-content-center">
																<span class="svg-icon text-light svg-icon-2hx me-1">
																	<svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"></path>
																		<path d="M14.854 11.321C14.7568 11.2282 14.6388 11.1818 14.4998 11.1818H14.3333V10.2272C14.3333 9.61741 14.1041 9.09378 13.6458 8.65628C13.1875 8.21876 12.639 8 12 8C11.361 8 10.8124 8.21876 10.3541 8.65626C9.89574 9.09378 9.66663 9.61739 9.66663 10.2272V11.1818H9.49999C9.36115 11.1818 9.24306 11.2282 9.14583 11.321C9.0486 11.4138 9 11.5265 9 11.6591V14.5227C9 14.6553 9.04862 14.768 9.14583 14.8609C9.24306 14.9536 9.36115 15 9.49999 15H14.5C14.6389 15 14.7569 14.9536 14.8542 14.8609C14.9513 14.768 15 14.6553 15 14.5227V11.6591C15.0001 11.5265 14.9513 11.4138 14.854 11.321ZM13.3333 11.1818H10.6666V10.2272C10.6666 9.87594 10.7969 9.57597 11.0573 9.32743C11.3177 9.07886 11.6319 8.9546 12 8.9546C12.3681 8.9546 12.6823 9.07884 12.9427 9.32743C13.2031 9.57595 13.3333 9.87594 13.3333 10.2272V11.1818Z" fill="currentColor"></path>
																	</svg>
																</span>Verified
                                                    </div>
                                                    <div class="label new-listing d-inline-flex align-items-center justify-content-center ms-1">
																<span class="svg-icon text-light svg-icon-2hx me-1">
																	<svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M19.0647 5.43757C19.3421 5.43757 19.567 5.21271 19.567 4.93534C19.567 4.65796 19.3421 4.43311 19.0647 4.43311C18.7874 4.43311 18.5625 4.65796 18.5625 4.93534C18.5625 5.21271 18.7874 5.43757 19.0647 5.43757Z" fill="currentColor"/>
																		<path d="M20.0692 9.48884C20.3466 9.48884 20.5714 9.26398 20.5714 8.98661C20.5714 8.70923 20.3466 8.48438 20.0692 8.48438C19.7918 8.48438 19.567 8.70923 19.567 8.98661C19.567 9.26398 19.7918 9.48884 20.0692 9.48884Z" fill="currentColor"/>
																		<path d="M12.0335 20.5714C15.6943 20.5714 18.9426 18.2053 20.1168 14.7338C20.1884 14.5225 20.1114 14.289 19.9284 14.161C19.746 14.034 19.5003 14.0418 19.3257 14.1821C18.2432 15.0546 16.9371 15.5156 15.5491 15.5156C12.2257 15.5156 9.48884 12.8122 9.48884 9.48886C9.48884 7.41079 10.5773 5.47137 12.3449 4.35752C12.5342 4.23832 12.6 4.00733 12.5377 3.79251C12.4759 3.57768 12.2571 3.42859 12.0335 3.42859C7.32556 3.42859 3.42857 7.29209 3.42857 12C3.42857 16.7079 7.32556 20.5714 12.0335 20.5714Z" fill="currentColor"/>
																		<path d="M13.0379 7.47998C13.8688 7.47998 14.5446 8.15585 14.5446 8.98668C14.5446 9.26428 14.7693 9.48891 15.0469 9.48891C15.3245 9.48891 15.5491 9.26428 15.5491 8.98668C15.5491 8.15585 16.225 7.47998 17.0558 7.47998C17.3334 7.47998 17.558 7.25535 17.558 6.97775C17.558 6.70015 17.3334 6.47552 17.0558 6.47552C16.225 6.47552 15.5491 5.76616 15.5491 4.93534C15.5491 4.65774 15.3245 4.43311 15.0469 4.43311C14.7693 4.43311 14.5446 4.65774 14.5446 4.93534C14.5446 5.76616 13.8688 6.47552 13.0379 6.47552C12.7603 6.47552 12.5357 6.70015 12.5357 6.97775C12.5357 7.25535 12.7603 7.47998 13.0379 7.47998Z" fill="currentColor"/>
																	</svg>
																</span>New
                                                    </div>
                                                </div>
                                                <div class="clicks rounded-3 overflow-hidden mb-0">
                                                    <a href="single-property-1.html"><img src="https://shreethemes.net/resido-2.3/resido/assets/img/p-5.jpg" class="img-fluid" alt="" /></a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="listing-caption-wrapper px-3">
                                            <div class="listing-detail-wrapper">
                                                <div class="listing-short-detail-wrap">
                                                    <div class="listing-short-detail">
                                                        <div class="d-flex align-items-center">
                                                            <span class="label for-sale prt-type me-2">For Sell</span><span class="label property-type property-cats">Villa</span>
                                                        </div>
                                                        <h4 class="listing-name fw-medium fs-5 mb-1"><a href="single-property-1.html">The Donald Dwelling</a></h4>
                                                        <div class="prt-location text-muted-2">
																	<span class="svg-icon svg-icon-2hx">
																		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<path opacity="0.3" d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z" fill="currentColor"/>
																			<path d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z" fill="currentColor"/>
																		</svg>
																	</span>
                                                            210 Zirak Road, Canada
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="price-features-wrapper">
                                                <div class="list-fx-features d-flex align-items-center justify-content-between">
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-building-shield fs-sm"></i></div><span class="text-muted-2">2BHK</span>
                                                    </div>
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-bed fs-sm"></i></div><span class="text-muted-2">2 Beds</span>
                                                    </div>
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-clone fs-sm"></i></div><span class="text-muted-2">2000 SQFT</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="listing-detail-footer d-flex align-items-center justify-content-between py-4">
                                                <div class="listing-short-detail-flex">
                                                    <h6 class="listing-card-info-price m-0">$360.5M</h6>
                                                </div>
                                                <div class="footer-flex">
                                                    <a href="property-detail.html" class="prt-view">
																<span class="svg-icon text-main svg-icon-2hx">
																	<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M15.43 8.56949L10.744 15.1395C10.6422 15.282 10.5804 15.4492 10.5651 15.6236C10.5498 15.7981 10.5815 15.9734 10.657 16.1315L13.194 21.4425C13.2737 21.6097 13.3991 21.751 13.5557 21.8499C13.7123 21.9488 13.8938 22.0014 14.079 22.0015H14.117C14.3087 21.9941 14.4941 21.9307 14.6502 21.8191C14.8062 21.7075 14.9261 21.5526 14.995 21.3735L21.933 3.33649C22.0011 3.15918 22.0164 2.96594 21.977 2.78013C21.9376 2.59432 21.8452 2.4239 21.711 2.28949L15.43 8.56949Z" fill="currentColor"/>
																		<path opacity="0.3" d="M20.664 2.06648L2.62602 9.00148C2.44768 9.07085 2.29348 9.19082 2.1824 9.34663C2.07131 9.50244 2.00818 9.68731 2.00074 9.87853C1.99331 10.0697 2.04189 10.259 2.14054 10.4229C2.23919 10.5869 2.38359 10.7185 2.55601 10.8015L7.86601 13.3365C8.02383 13.4126 8.19925 13.4448 8.37382 13.4297C8.54839 13.4145 8.71565 13.3526 8.85801 13.2505L15.43 8.56548L21.711 2.28448C21.5762 2.15096 21.4055 2.05932 21.2198 2.02064C21.034 1.98196 20.8409 1.99788 20.664 2.06648Z" fill="currentColor"/>
																	</svg>
																</span>
                                                    </a>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <!-- Single Property -->
                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="property-listing card border rounded-3">

                                        <div class="listing-img-wrapper p-3">
                                            <div class="list-img-slide position-relative">
                                                <div class=" position-absolute top-0 left-0 ms-3 mt-3 z-1">
                                                    <div class="label super-agent d-inline-flex align-items-center justify-content-center">
																<span class="svg-icon text-light svg-icon-2hx me-1">
																	<svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"/>
																		<path d="M12.0006 11.1542C13.1434 11.1542 14.0777 10.22 14.0777 9.0771C14.0777 7.93424 13.1434 7 12.0006 7C10.8577 7 9.92348 7.93424 9.92348 9.0771C9.92348 10.22 10.8577 11.1542 12.0006 11.1542Z" fill="currentColor"/>
																		<path d="M15.5652 13.814C15.5108 13.6779 15.4382 13.551 15.3566 13.4331C14.9393 12.8163 14.2954 12.4081 13.5697 12.3083C13.479 12.2993 13.3793 12.3174 13.3067 12.3718C12.9257 12.653 12.4722 12.7981 12.0006 12.7981C11.5289 12.7981 11.0754 12.653 10.6944 12.3718C10.6219 12.3174 10.5221 12.2902 10.4314 12.3083C9.70578 12.4081 9.05272 12.8163 8.64456 13.4331C8.56293 13.551 8.49036 13.687 8.43595 13.814C8.40875 13.8684 8.41781 13.9319 8.44502 13.9864C8.51759 14.1133 8.60828 14.2403 8.68991 14.3492C8.81689 14.5215 8.95295 14.6757 9.10715 14.8208C9.23413 14.9478 9.37925 15.0657 9.52439 15.1836C10.2409 15.7188 11.1026 15.9999 11.9915 15.9999C12.8804 15.9999 13.7421 15.7188 14.4586 15.1836C14.6038 15.0748 14.7489 14.9478 14.8759 14.8208C15.021 14.6757 15.1661 14.5215 15.2931 14.3492C15.3838 14.2312 15.4655 14.1133 15.538 13.9864C15.5833 13.9319 15.5924 13.8684 15.5652 13.814Z" fill="currentColor"/>
																	</svg>
																</span>SuperAgent
                                                    </div>
                                                </div>
                                                <div class="clicks rounded-3 overflow-hidden mb-0">
                                                    <a href="single-property-1.html"><img src="https://shreethemes.net/resido-2.3/resido/assets/img/p-6.jpg" class="img-fluid" alt="" /></a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="listing-caption-wrapper px-3">
                                            <div class="listing-detail-wrapper">
                                                <div class="listing-short-detail-wrap">
                                                    <div class="listing-short-detail">
                                                        <div class="d-flex align-items-center">
                                                            <span class="label for-sale prt-type me-2">For Sell</span><span class="label property-type property-cats">Building</span>
                                                        </div>
                                                        <h4 class="listing-name fw-medium fs-5 mb-1"><a href="single-property-1.html">Red Tiny Hearst Castle</a></h4>
                                                        <div class="prt-location text-muted-2">
																	<span class="svg-icon svg-icon-2hx">
																		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<path opacity="0.3" d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z" fill="currentColor"/>
																			<path d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z" fill="currentColor"/>
																		</svg>
																	</span>
                                                            210 Zirak Road, Canada
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="price-features-wrapper">
                                                <div class="list-fx-features d-flex align-items-center justify-content-between">
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-building-shield fs-sm"></i></div><span class="text-muted-2">3BHK</span>
                                                    </div>
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-bed fs-sm"></i></div><span class="text-muted-2">3 Beds</span>
                                                    </div>
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-clone fs-sm"></i></div><span class="text-muted-2">1700 SQFT</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="listing-detail-footer d-flex align-items-center justify-content-between py-4">
                                                <div class="listing-short-detail-flex">
                                                    <h6 class="listing-card-info-price m-0">$290.8M</h6>
                                                </div>
                                                <div class="footer-flex">
                                                    <a href="property-detail.html" class="prt-view">
																<span class="svg-icon text-main svg-icon-2hx">
																	<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M15.43 8.56949L10.744 15.1395C10.6422 15.282 10.5804 15.4492 10.5651 15.6236C10.5498 15.7981 10.5815 15.9734 10.657 16.1315L13.194 21.4425C13.2737 21.6097 13.3991 21.751 13.5557 21.8499C13.7123 21.9488 13.8938 22.0014 14.079 22.0015H14.117C14.3087 21.9941 14.4941 21.9307 14.6502 21.8191C14.8062 21.7075 14.9261 21.5526 14.995 21.3735L21.933 3.33649C22.0011 3.15918 22.0164 2.96594 21.977 2.78013C21.9376 2.59432 21.8452 2.4239 21.711 2.28949L15.43 8.56949Z" fill="currentColor"/>
																		<path opacity="0.3" d="M20.664 2.06648L2.62602 9.00148C2.44768 9.07085 2.29348 9.19082 2.1824 9.34663C2.07131 9.50244 2.00818 9.68731 2.00074 9.87853C1.99331 10.0697 2.04189 10.259 2.14054 10.4229C2.23919 10.5869 2.38359 10.7185 2.55601 10.8015L7.86601 13.3365C8.02383 13.4126 8.19925 13.4448 8.37382 13.4297C8.54839 13.4145 8.71565 13.3526 8.85801 13.2505L15.43 8.56548L21.711 2.28448C21.5762 2.15096 21.4055 2.05932 21.2198 2.02064C21.034 1.98196 20.8409 1.99788 20.664 2.06648Z" fill="currentColor"/>
																	</svg>
																</span>
                                                    </a>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Property for Sale -->
                        <div class="tab-pane fade" id="pills-rent" role="tabpanel" aria-labelledby="pills-rent-tab" tabindex="0">
                            <div class="row align-items-center justify-content-center g-4">
                                <!-- Single Property -->
                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="property-listing card border rounded-3">

                                        <div class="listing-img-wrapper p-3">
                                            <div class="list-img-slide position-relative">
                                                <div class="position-absolute top-0 left-0 ms-3 mt-3 z-1">
                                                    <div class="label verified-listing d-inline-flex align-items-center justify-content-center">
																<span class="svg-icon text-light svg-icon-2hx me-1">
																	<svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"></path>
																		<path d="M14.854 11.321C14.7568 11.2282 14.6388 11.1818 14.4998 11.1818H14.3333V10.2272C14.3333 9.61741 14.1041 9.09378 13.6458 8.65628C13.1875 8.21876 12.639 8 12 8C11.361 8 10.8124 8.21876 10.3541 8.65626C9.89574 9.09378 9.66663 9.61739 9.66663 10.2272V11.1818H9.49999C9.36115 11.1818 9.24306 11.2282 9.14583 11.321C9.0486 11.4138 9 11.5265 9 11.6591V14.5227C9 14.6553 9.04862 14.768 9.14583 14.8609C9.24306 14.9536 9.36115 15 9.49999 15H14.5C14.6389 15 14.7569 14.9536 14.8542 14.8609C14.9513 14.768 15 14.6553 15 14.5227V11.6591C15.0001 11.5265 14.9513 11.4138 14.854 11.321ZM13.3333 11.1818H10.6666V10.2272C10.6666 9.87594 10.7969 9.57597 11.0573 9.32743C11.3177 9.07886 11.6319 8.9546 12 8.9546C12.3681 8.9546 12.6823 9.07884 12.9427 9.32743C13.2031 9.57595 13.3333 9.87594 13.3333 10.2272V11.1818Z" fill="currentColor"></path>
																	</svg>
																</span>Verified
                                                    </div>
                                                </div>
                                                <div class="clicks rounded-3 overflow-hidden mb-0">
                                                    <a href="single-property-1.html"><img src="https://shreethemes.net/resido-2.3/resido/assets/img/p-13.jpg" class="img-fluid" alt="" /></a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="listing-caption-wrapper px-3">
                                            <div class="listing-detail-wrapper">
                                                <div class="listing-short-detail-wrap">
                                                    <div class="listing-short-detail">
                                                        <div class="d-flex align-items-center">
                                                            <span class="label for-rent prt-type me-2">For Rent</span><span class="label property-type property-cats">Apartment</span>
                                                        </div>
                                                        <h4 class="listing-name fw-medium fs-5 mb-1"><a href="single-property-1.html">The Green Canton Chrysler</a></h4>
                                                        <div class="prt-location text-muted-2">
																	<span class="svg-icon svg-icon-2hx">
																		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<path opacity="0.3" d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z" fill="currentColor"/>
																			<path d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z" fill="currentColor"/>
																		</svg>
																	</span>
                                                            210 Zirak Road, Canada
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="price-features-wrapper">
                                                <div class="list-fx-features d-flex align-items-center justify-content-between">
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-building-shield fs-sm"></i></div><span class="text-muted-2">4BHK</span>
                                                    </div>
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-bed fs-sm"></i></div><span class="text-muted-2">3 Beds</span>
                                                    </div>
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-clone fs-sm"></i></div><span class="text-muted-2">1800 SQFT</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="listing-detail-footer d-flex align-items-center justify-content-between py-4">
                                                <div class="listing-short-detail-flex">
                                                    <h6 class="listing-card-info-price m-0">$80,000</h6>
                                                </div>
                                                <div class="footer-flex">
                                                    <a href="property-detail.html" class="prt-view">
																<span class="svg-icon text-main svg-icon-2hx">
																	<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M15.43 8.56949L10.744 15.1395C10.6422 15.282 10.5804 15.4492 10.5651 15.6236C10.5498 15.7981 10.5815 15.9734 10.657 16.1315L13.194 21.4425C13.2737 21.6097 13.3991 21.751 13.5557 21.8499C13.7123 21.9488 13.8938 22.0014 14.079 22.0015H14.117C14.3087 21.9941 14.4941 21.9307 14.6502 21.8191C14.8062 21.7075 14.9261 21.5526 14.995 21.3735L21.933 3.33649C22.0011 3.15918 22.0164 2.96594 21.977 2.78013C21.9376 2.59432 21.8452 2.4239 21.711 2.28949L15.43 8.56949Z" fill="currentColor"/>
																		<path opacity="0.3" d="M20.664 2.06648L2.62602 9.00148C2.44768 9.07085 2.29348 9.19082 2.1824 9.34663C2.07131 9.50244 2.00818 9.68731 2.00074 9.87853C1.99331 10.0697 2.04189 10.259 2.14054 10.4229C2.23919 10.5869 2.38359 10.7185 2.55601 10.8015L7.86601 13.3365C8.02383 13.4126 8.19925 13.4448 8.37382 13.4297C8.54839 13.4145 8.71565 13.3526 8.85801 13.2505L15.43 8.56548L21.711 2.28448C21.5762 2.15096 21.4055 2.05932 21.2198 2.02064C21.034 1.98196 20.8409 1.99788 20.664 2.06648Z" fill="currentColor"/>
																	</svg>
																</span>
                                                    </a>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <!-- Single Property -->
                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="property-listing card border rounded-3">

                                        <div class="listing-img-wrapper p-3">
                                            <div class="list-img-slide position-relative">
                                                <div class="position-absolute top-0 left-0 ms-3 mt-3 z-1">
                                                    <div class="label super-agent d-inline-flex align-items-center justify-content-center">
																<span class="svg-icon text-light svg-icon-2hx me-1">
																	<svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"/>
																		<path d="M12.0006 11.1542C13.1434 11.1542 14.0777 10.22 14.0777 9.0771C14.0777 7.93424 13.1434 7 12.0006 7C10.8577 7 9.92348 7.93424 9.92348 9.0771C9.92348 10.22 10.8577 11.1542 12.0006 11.1542Z" fill="currentColor"/>
																		<path d="M15.5652 13.814C15.5108 13.6779 15.4382 13.551 15.3566 13.4331C14.9393 12.8163 14.2954 12.4081 13.5697 12.3083C13.479 12.2993 13.3793 12.3174 13.3067 12.3718C12.9257 12.653 12.4722 12.7981 12.0006 12.7981C11.5289 12.7981 11.0754 12.653 10.6944 12.3718C10.6219 12.3174 10.5221 12.2902 10.4314 12.3083C9.70578 12.4081 9.05272 12.8163 8.64456 13.4331C8.56293 13.551 8.49036 13.687 8.43595 13.814C8.40875 13.8684 8.41781 13.9319 8.44502 13.9864C8.51759 14.1133 8.60828 14.2403 8.68991 14.3492C8.81689 14.5215 8.95295 14.6757 9.10715 14.8208C9.23413 14.9478 9.37925 15.0657 9.52439 15.1836C10.2409 15.7188 11.1026 15.9999 11.9915 15.9999C12.8804 15.9999 13.7421 15.7188 14.4586 15.1836C14.6038 15.0748 14.7489 14.9478 14.8759 14.8208C15.021 14.6757 15.1661 14.5215 15.2931 14.3492C15.3838 14.2312 15.4655 14.1133 15.538 13.9864C15.5833 13.9319 15.5924 13.8684 15.5652 13.814Z" fill="currentColor"/>
																	</svg>
																</span>SuperAgent
                                                    </div>
                                                </div>
                                                <div class="clicks rounded-3 overflow-hidden mb-0">
                                                    <a href="single-property-1.html"><img src="https://shreethemes.net/resido-2.3/resido/assets/img/p-14.jpg" class="img-fluid" alt="" /></a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="listing-caption-wrapper px-3">
                                            <div class="listing-detail-wrapper">
                                                <div class="listing-short-detail-wrap">
                                                    <div class="listing-short-detail">
                                                        <div class="d-flex align-items-center">
                                                            <span class="label for-rent prt-type me-2">For Rent</span><span class="label property-type property-cats">House</span>
                                                        </div>
                                                        <h4 class="listing-name fw-medium fs-5 mb-1"><a href="single-property-1.html">Purple Flatiron House</a></h4>
                                                        <div class="prt-location text-muted-2">
																	<span class="svg-icon svg-icon-2hx">
																		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<path opacity="0.3" d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z" fill="currentColor"/>
																			<path d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z" fill="currentColor"/>
																		</svg>
																	</span>
                                                            210 Zirak Road, Canada
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="price-features-wrapper">
                                                <div class="list-fx-features d-flex align-items-center justify-content-between">
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-building-shield fs-sm"></i></div><span class="text-muted-2">3BHK</span>
                                                    </div>
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-bed fs-sm"></i></div><span class="text-muted-2">3 Beds</span>
                                                    </div>
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-clone fs-sm"></i></div><span class="text-muted-2">2200 SQFT</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="listing-detail-footer d-flex align-items-center justify-content-between py-4">
                                                <div class="listing-short-detail-flex">
                                                    <h6 class="listing-card-info-price m-0">$67,000</h6>
                                                </div>
                                                <div class="footer-flex">
                                                    <a href="property-detail.html" class="prt-view">
																<span class="svg-icon text-main svg-icon-2hx">
																	<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M15.43 8.56949L10.744 15.1395C10.6422 15.282 10.5804 15.4492 10.5651 15.6236C10.5498 15.7981 10.5815 15.9734 10.657 16.1315L13.194 21.4425C13.2737 21.6097 13.3991 21.751 13.5557 21.8499C13.7123 21.9488 13.8938 22.0014 14.079 22.0015H14.117C14.3087 21.9941 14.4941 21.9307 14.6502 21.8191C14.8062 21.7075 14.9261 21.5526 14.995 21.3735L21.933 3.33649C22.0011 3.15918 22.0164 2.96594 21.977 2.78013C21.9376 2.59432 21.8452 2.4239 21.711 2.28949L15.43 8.56949Z" fill="currentColor"/>
																		<path opacity="0.3" d="M20.664 2.06648L2.62602 9.00148C2.44768 9.07085 2.29348 9.19082 2.1824 9.34663C2.07131 9.50244 2.00818 9.68731 2.00074 9.87853C1.99331 10.0697 2.04189 10.259 2.14054 10.4229C2.23919 10.5869 2.38359 10.7185 2.55601 10.8015L7.86601 13.3365C8.02383 13.4126 8.19925 13.4448 8.37382 13.4297C8.54839 13.4145 8.71565 13.3526 8.85801 13.2505L15.43 8.56548L21.711 2.28448C21.5762 2.15096 21.4055 2.05932 21.2198 2.02064C21.034 1.98196 20.8409 1.99788 20.664 2.06648Z" fill="currentColor"/>
																	</svg>
																</span>
                                                    </a>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <!-- Single Property -->
                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="property-listing card border rounded-3">

                                        <div class="listing-img-wrapper p-3">
                                            <div class="list-img-slide position-relative">
                                                <div class="position-absolute top-0 left-0 ms-3 mt-3 z-1">
                                                    <div class="label verified-listing d-inline-flex align-items-center justify-content-center">
																<span class="svg-icon text-light svg-icon-2hx me-1">
																	<svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"></path>
																		<path d="M14.854 11.321C14.7568 11.2282 14.6388 11.1818 14.4998 11.1818H14.3333V10.2272C14.3333 9.61741 14.1041 9.09378 13.6458 8.65628C13.1875 8.21876 12.639 8 12 8C11.361 8 10.8124 8.21876 10.3541 8.65626C9.89574 9.09378 9.66663 9.61739 9.66663 10.2272V11.1818H9.49999C9.36115 11.1818 9.24306 11.2282 9.14583 11.321C9.0486 11.4138 9 11.5265 9 11.6591V14.5227C9 14.6553 9.04862 14.768 9.14583 14.8609C9.24306 14.9536 9.36115 15 9.49999 15H14.5C14.6389 15 14.7569 14.9536 14.8542 14.8609C14.9513 14.768 15 14.6553 15 14.5227V11.6591C15.0001 11.5265 14.9513 11.4138 14.854 11.321ZM13.3333 11.1818H10.6666V10.2272C10.6666 9.87594 10.7969 9.57597 11.0573 9.32743C11.3177 9.07886 11.6319 8.9546 12 8.9546C12.3681 8.9546 12.6823 9.07884 12.9427 9.32743C13.2031 9.57595 13.3333 9.87594 13.3333 10.2272V11.1818Z" fill="currentColor"></path>
																	</svg>
																</span>Verified
                                                    </div>
                                                    <div class="label new-listing d-inline-flex align-items-center justify-content-center ms-1">
																<span class="svg-icon text-light svg-icon-2hx me-1">
																	<svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M19.0647 5.43757C19.3421 5.43757 19.567 5.21271 19.567 4.93534C19.567 4.65796 19.3421 4.43311 19.0647 4.43311C18.7874 4.43311 18.5625 4.65796 18.5625 4.93534C18.5625 5.21271 18.7874 5.43757 19.0647 5.43757Z" fill="currentColor"/>
																		<path d="M20.0692 9.48884C20.3466 9.48884 20.5714 9.26398 20.5714 8.98661C20.5714 8.70923 20.3466 8.48438 20.0692 8.48438C19.7918 8.48438 19.567 8.70923 19.567 8.98661C19.567 9.26398 19.7918 9.48884 20.0692 9.48884Z" fill="currentColor"/>
																		<path d="M12.0335 20.5714C15.6943 20.5714 18.9426 18.2053 20.1168 14.7338C20.1884 14.5225 20.1114 14.289 19.9284 14.161C19.746 14.034 19.5003 14.0418 19.3257 14.1821C18.2432 15.0546 16.9371 15.5156 15.5491 15.5156C12.2257 15.5156 9.48884 12.8122 9.48884 9.48886C9.48884 7.41079 10.5773 5.47137 12.3449 4.35752C12.5342 4.23832 12.6 4.00733 12.5377 3.79251C12.4759 3.57768 12.2571 3.42859 12.0335 3.42859C7.32556 3.42859 3.42857 7.29209 3.42857 12C3.42857 16.7079 7.32556 20.5714 12.0335 20.5714Z" fill="currentColor"/>
																		<path d="M13.0379 7.47998C13.8688 7.47998 14.5446 8.15585 14.5446 8.98668C14.5446 9.26428 14.7693 9.48891 15.0469 9.48891C15.3245 9.48891 15.5491 9.26428 15.5491 8.98668C15.5491 8.15585 16.225 7.47998 17.0558 7.47998C17.3334 7.47998 17.558 7.25535 17.558 6.97775C17.558 6.70015 17.3334 6.47552 17.0558 6.47552C16.225 6.47552 15.5491 5.76616 15.5491 4.93534C15.5491 4.65774 15.3245 4.43311 15.0469 4.43311C14.7693 4.43311 14.5446 4.65774 14.5446 4.93534C14.5446 5.76616 13.8688 6.47552 13.0379 6.47552C12.7603 6.47552 12.5357 6.70015 12.5357 6.97775C12.5357 7.25535 12.7603 7.47998 13.0379 7.47998Z" fill="currentColor"/>
																	</svg>
																</span>New
                                                    </div>
                                                </div>
                                                <div class="clicks rounded-3 overflow-hidden mb-0">
                                                    <a href="single-property-1.html"><img src="https://shreethemes.net/resido-2.3/resido/assets/img/p-15.jpg" class="img-fluid" alt="" /></a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="listing-caption-wrapper px-3">
                                            <div class="listing-detail-wrapper">
                                                <div class="listing-short-detail-wrap">
                                                    <div class="listing-short-detail">
                                                        <div class="d-flex align-items-center">
                                                            <span class="label for-rent prt-type me-2">For Rent</span><span class="label property-type property-cats">Building</span>
                                                        </div>
                                                        <h4 class="listing-name fw-medium fs-5 mb-1"><a href="single-property-1.html">Rustic Reunion Tower</a></h4>
                                                        <div class="prt-location text-muted-2">
																	<span class="svg-icon svg-icon-2hx">
																		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<path opacity="0.3" d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z" fill="currentColor"/>
																			<path d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z" fill="currentColor"/>
																		</svg>
																	</span>
                                                            210 Zirak Road, Canada
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="price-features-wrapper">
                                                <div class="list-fx-features d-flex align-items-center justify-content-between">
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-building-shield fs-sm"></i></div><span class="text-muted-2">3BHK</span>
                                                    </div>
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-bed fs-sm"></i></div><span class="text-muted-2">2 Beds</span>
                                                    </div>
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-clone fs-sm"></i></div><span class="text-muted-2">2500 SQFT</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="listing-detail-footer d-flex align-items-center justify-content-between py-4">
                                                <div class="listing-short-detail-flex">
                                                    <h6 class="listing-card-info-price m-0">$92,500</h6>
                                                </div>
                                                <div class="footer-flex">
                                                    <a href="property-detail.html" class="prt-view">
																<span class="svg-icon text-main svg-icon-2hx">
																	<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M15.43 8.56949L10.744 15.1395C10.6422 15.282 10.5804 15.4492 10.5651 15.6236C10.5498 15.7981 10.5815 15.9734 10.657 16.1315L13.194 21.4425C13.2737 21.6097 13.3991 21.751 13.5557 21.8499C13.7123 21.9488 13.8938 22.0014 14.079 22.0015H14.117C14.3087 21.9941 14.4941 21.9307 14.6502 21.8191C14.8062 21.7075 14.9261 21.5526 14.995 21.3735L21.933 3.33649C22.0011 3.15918 22.0164 2.96594 21.977 2.78013C21.9376 2.59432 21.8452 2.4239 21.711 2.28949L15.43 8.56949Z" fill="currentColor"/>
																		<path opacity="0.3" d="M20.664 2.06648L2.62602 9.00148C2.44768 9.07085 2.29348 9.19082 2.1824 9.34663C2.07131 9.50244 2.00818 9.68731 2.00074 9.87853C1.99331 10.0697 2.04189 10.259 2.14054 10.4229C2.23919 10.5869 2.38359 10.7185 2.55601 10.8015L7.86601 13.3365C8.02383 13.4126 8.19925 13.4448 8.37382 13.4297C8.54839 13.4145 8.71565 13.3526 8.85801 13.2505L15.43 8.56548L21.711 2.28448C21.5762 2.15096 21.4055 2.05932 21.2198 2.02064C21.034 1.98196 20.8409 1.99788 20.664 2.06648Z" fill="currentColor"/>
																	</svg>
																</span>
                                                    </a>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <!-- Single Property -->
                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="property-listing card border rounded-3">

                                        <div class="listing-img-wrapper p-3">
                                            <div class="list-img-slide position-relative">
                                                <div class="position-absolute top-0 left-0 ms-3 mt-3 z-1">
                                                    <div class="label super-agent d-inline-flex align-items-center justify-content-center">
																<span class="svg-icon text-light svg-icon-2hx me-1">
																	<svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"/>
																		<path d="M12.0006 11.1542C13.1434 11.1542 14.0777 10.22 14.0777 9.0771C14.0777 7.93424 13.1434 7 12.0006 7C10.8577 7 9.92348 7.93424 9.92348 9.0771C9.92348 10.22 10.8577 11.1542 12.0006 11.1542Z" fill="currentColor"/>
																		<path d="M15.5652 13.814C15.5108 13.6779 15.4382 13.551 15.3566 13.4331C14.9393 12.8163 14.2954 12.4081 13.5697 12.3083C13.479 12.2993 13.3793 12.3174 13.3067 12.3718C12.9257 12.653 12.4722 12.7981 12.0006 12.7981C11.5289 12.7981 11.0754 12.653 10.6944 12.3718C10.6219 12.3174 10.5221 12.2902 10.4314 12.3083C9.70578 12.4081 9.05272 12.8163 8.64456 13.4331C8.56293 13.551 8.49036 13.687 8.43595 13.814C8.40875 13.8684 8.41781 13.9319 8.44502 13.9864C8.51759 14.1133 8.60828 14.2403 8.68991 14.3492C8.81689 14.5215 8.95295 14.6757 9.10715 14.8208C9.23413 14.9478 9.37925 15.0657 9.52439 15.1836C10.2409 15.7188 11.1026 15.9999 11.9915 15.9999C12.8804 15.9999 13.7421 15.7188 14.4586 15.1836C14.6038 15.0748 14.7489 14.9478 14.8759 14.8208C15.021 14.6757 15.1661 14.5215 15.2931 14.3492C15.3838 14.2312 15.4655 14.1133 15.538 13.9864C15.5833 13.9319 15.5924 13.8684 15.5652 13.814Z" fill="currentColor"/>
																	</svg>
																</span>SuperAgent
                                                    </div>
                                                </div>
                                                <div class="clicks rounded-3 overflow-hidden mb-0">
                                                    <a href="single-property-1.html"><img src="https://shreethemes.net/resido-2.3/resido/assets/img/p-16.jpg" class="img-fluid" alt="" /></a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="listing-caption-wrapper px-3">
                                            <div class="listing-detail-wrapper">
                                                <div class="listing-short-detail-wrap">
                                                    <div class="listing-short-detail">
                                                        <div class="d-flex align-items-center">
                                                            <span class="label for-rent prt-type me-2">For Rent</span><span class="label property-type property-cats">Condos</span>
                                                        </div>
                                                        <h4 class="listing-name fw-medium fs-5 mb-1"><a href="single-property-1.html">The Red Freedom Tower</a></h4>
                                                        <div class="prt-location text-muted-2">
																	<span class="svg-icon svg-icon-2hx">
																		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<path opacity="0.3" d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z" fill="currentColor"/>
																			<path d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z" fill="currentColor"/>
																		</svg>
																	</span>
                                                            210 Zirak Road, Canada
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="price-features-wrapper">
                                                <div class="list-fx-features d-flex align-items-center justify-content-between">
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-building-shield fs-sm"></i></div><span class="text-muted-2">4BHK</span>
                                                    </div>
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-bed fs-sm"></i></div><span class="text-muted-2">4 Beds</span>
                                                    </div>
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-clone fs-sm"></i></div><span class="text-muted-2">1900 SQFT</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="listing-detail-footer d-flex align-items-center justify-content-between py-4">
                                                <div class="listing-short-detail-flex">
                                                    <h6 class="listing-card-info-price m-0">$89,000</h6>
                                                </div>
                                                <div class="footer-flex">
                                                    <a href="property-detail.html" class="prt-view">
																<span class="svg-icon text-main svg-icon-2hx">
																	<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M15.43 8.56949L10.744 15.1395C10.6422 15.282 10.5804 15.4492 10.5651 15.6236C10.5498 15.7981 10.5815 15.9734 10.657 16.1315L13.194 21.4425C13.2737 21.6097 13.3991 21.751 13.5557 21.8499C13.7123 21.9488 13.8938 22.0014 14.079 22.0015H14.117C14.3087 21.9941 14.4941 21.9307 14.6502 21.8191C14.8062 21.7075 14.9261 21.5526 14.995 21.3735L21.933 3.33649C22.0011 3.15918 22.0164 2.96594 21.977 2.78013C21.9376 2.59432 21.8452 2.4239 21.711 2.28949L15.43 8.56949Z" fill="currentColor"/>
																		<path opacity="0.3" d="M20.664 2.06648L2.62602 9.00148C2.44768 9.07085 2.29348 9.19082 2.1824 9.34663C2.07131 9.50244 2.00818 9.68731 2.00074 9.87853C1.99331 10.0697 2.04189 10.259 2.14054 10.4229C2.23919 10.5869 2.38359 10.7185 2.55601 10.8015L7.86601 13.3365C8.02383 13.4126 8.19925 13.4448 8.37382 13.4297C8.54839 13.4145 8.71565 13.3526 8.85801 13.2505L15.43 8.56548L21.711 2.28448C21.5762 2.15096 21.4055 2.05932 21.2198 2.02064C21.034 1.98196 20.8409 1.99788 20.664 2.06648Z" fill="currentColor"/>
																	</svg>
																</span>
                                                    </a>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <!-- Single Property -->
                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="property-listing card border rounded-3">

                                        <div class="listing-img-wrapper p-3">
                                            <div class="list-img-slide position-relative">
                                                <div class="position-absolute top-0 left-0 ms-3 mt-3 z-1">
                                                    <div class="label verified-listing d-inline-flex align-items-center justify-content-center">
																<span class="svg-icon text-light svg-icon-2hx me-1">
																	<svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"></path>
																		<path d="M14.854 11.321C14.7568 11.2282 14.6388 11.1818 14.4998 11.1818H14.3333V10.2272C14.3333 9.61741 14.1041 9.09378 13.6458 8.65628C13.1875 8.21876 12.639 8 12 8C11.361 8 10.8124 8.21876 10.3541 8.65626C9.89574 9.09378 9.66663 9.61739 9.66663 10.2272V11.1818H9.49999C9.36115 11.1818 9.24306 11.2282 9.14583 11.321C9.0486 11.4138 9 11.5265 9 11.6591V14.5227C9 14.6553 9.04862 14.768 9.14583 14.8609C9.24306 14.9536 9.36115 15 9.49999 15H14.5C14.6389 15 14.7569 14.9536 14.8542 14.8609C14.9513 14.768 15 14.6553 15 14.5227V11.6591C15.0001 11.5265 14.9513 11.4138 14.854 11.321ZM13.3333 11.1818H10.6666V10.2272C10.6666 9.87594 10.7969 9.57597 11.0573 9.32743C11.3177 9.07886 11.6319 8.9546 12 8.9546C12.3681 8.9546 12.6823 9.07884 12.9427 9.32743C13.2031 9.57595 13.3333 9.87594 13.3333 10.2272V11.1818Z" fill="currentColor"></path>
																	</svg>
																</span>Verified
                                                    </div>
                                                    <div class="label new-listing d-inline-flex align-items-center justify-content-center ms-1">
																<span class="svg-icon text-light svg-icon-2hx me-1">
																	<svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M19.0647 5.43757C19.3421 5.43757 19.567 5.21271 19.567 4.93534C19.567 4.65796 19.3421 4.43311 19.0647 4.43311C18.7874 4.43311 18.5625 4.65796 18.5625 4.93534C18.5625 5.21271 18.7874 5.43757 19.0647 5.43757Z" fill="currentColor"/>
																		<path d="M20.0692 9.48884C20.3466 9.48884 20.5714 9.26398 20.5714 8.98661C20.5714 8.70923 20.3466 8.48438 20.0692 8.48438C19.7918 8.48438 19.567 8.70923 19.567 8.98661C19.567 9.26398 19.7918 9.48884 20.0692 9.48884Z" fill="currentColor"/>
																		<path d="M12.0335 20.5714C15.6943 20.5714 18.9426 18.2053 20.1168 14.7338C20.1884 14.5225 20.1114 14.289 19.9284 14.161C19.746 14.034 19.5003 14.0418 19.3257 14.1821C18.2432 15.0546 16.9371 15.5156 15.5491 15.5156C12.2257 15.5156 9.48884 12.8122 9.48884 9.48886C9.48884 7.41079 10.5773 5.47137 12.3449 4.35752C12.5342 4.23832 12.6 4.00733 12.5377 3.79251C12.4759 3.57768 12.2571 3.42859 12.0335 3.42859C7.32556 3.42859 3.42857 7.29209 3.42857 12C3.42857 16.7079 7.32556 20.5714 12.0335 20.5714Z" fill="currentColor"/>
																		<path d="M13.0379 7.47998C13.8688 7.47998 14.5446 8.15585 14.5446 8.98668C14.5446 9.26428 14.7693 9.48891 15.0469 9.48891C15.3245 9.48891 15.5491 9.26428 15.5491 8.98668C15.5491 8.15585 16.225 7.47998 17.0558 7.47998C17.3334 7.47998 17.558 7.25535 17.558 6.97775C17.558 6.70015 17.3334 6.47552 17.0558 6.47552C16.225 6.47552 15.5491 5.76616 15.5491 4.93534C15.5491 4.65774 15.3245 4.43311 15.0469 4.43311C14.7693 4.43311 14.5446 4.65774 14.5446 4.93534C14.5446 5.76616 13.8688 6.47552 13.0379 6.47552C12.7603 6.47552 12.5357 6.70015 12.5357 6.97775C12.5357 7.25535 12.7603 7.47998 13.0379 7.47998Z" fill="currentColor"/>
																	</svg>
																</span>New
                                                    </div>
                                                </div>
                                                <div class="clicks rounded-3 overflow-hidden mb-0">
                                                    <a href="single-property-1.html"><img src="https://shreethemes.net/resido-2.3/resido/assets/img/p-18.jpg" class="img-fluid" alt="" /></a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="listing-caption-wrapper px-3">
                                            <div class="listing-detail-wrapper">
                                                <div class="listing-short-detail-wrap">
                                                    <div class="listing-short-detail">
                                                        <div class="d-flex align-items-center">
                                                            <span class="label for-rent prt-type me-2">For Rent</span><span class="label property-type property-cats">Villa</span>
                                                        </div>
                                                        <h4 class="listing-name fw-medium fs-5 mb-1"><a href="single-property-1.html">The Donald Dwelling</a></h4>
                                                        <div class="prt-location text-muted-2">
																	<span class="svg-icon svg-icon-2hx">
																		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<path opacity="0.3" d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z" fill="currentColor"/>
																			<path d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z" fill="currentColor"/>
																		</svg>
																	</span>
                                                            210 Zirak Road, Canada
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="price-features-wrapper">
                                                <div class="list-fx-features d-flex align-items-center justify-content-between">
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-building-shield fs-sm"></i></div><span class="text-muted-2">2BHK</span>
                                                    </div>
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-bed fs-sm"></i></div><span class="text-muted-2">2 Beds</span>
                                                    </div>
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-clone fs-sm"></i></div><span class="text-muted-2">2000 SQFT</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="listing-detail-footer d-flex align-items-center justify-content-between py-4">
                                                <div class="listing-short-detail-flex">
                                                    <h6 class="listing-card-info-price m-0">$88,000</h6>
                                                </div>
                                                <div class="footer-flex">
                                                    <a href="property-detail.html" class="prt-view">
																<span class="svg-icon text-main svg-icon-2hx">
																	<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M15.43 8.56949L10.744 15.1395C10.6422 15.282 10.5804 15.4492 10.5651 15.6236C10.5498 15.7981 10.5815 15.9734 10.657 16.1315L13.194 21.4425C13.2737 21.6097 13.3991 21.751 13.5557 21.8499C13.7123 21.9488 13.8938 22.0014 14.079 22.0015H14.117C14.3087 21.9941 14.4941 21.9307 14.6502 21.8191C14.8062 21.7075 14.9261 21.5526 14.995 21.3735L21.933 3.33649C22.0011 3.15918 22.0164 2.96594 21.977 2.78013C21.9376 2.59432 21.8452 2.4239 21.711 2.28949L15.43 8.56949Z" fill="currentColor"/>
																		<path opacity="0.3" d="M20.664 2.06648L2.62602 9.00148C2.44768 9.07085 2.29348 9.19082 2.1824 9.34663C2.07131 9.50244 2.00818 9.68731 2.00074 9.87853C1.99331 10.0697 2.04189 10.259 2.14054 10.4229C2.23919 10.5869 2.38359 10.7185 2.55601 10.8015L7.86601 13.3365C8.02383 13.4126 8.19925 13.4448 8.37382 13.4297C8.54839 13.4145 8.71565 13.3526 8.85801 13.2505L15.43 8.56548L21.711 2.28448C21.5762 2.15096 21.4055 2.05932 21.2198 2.02064C21.034 1.98196 20.8409 1.99788 20.664 2.06648Z" fill="currentColor"/>
																	</svg>
																</span>
                                                    </a>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <!-- Single Property -->
                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="property-listing card border rounded-3">

                                        <div class="listing-img-wrapper p-3">
                                            <div class="list-img-slide position-relative">
                                                <div class=" position-absolute top-0 left-0 ms-3 mt-3 z-1">
                                                    <div class="label super-agent d-inline-flex align-items-center justify-content-center">
																<span class="svg-icon text-light svg-icon-2hx me-1">
																	<svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"/>
																		<path d="M12.0006 11.1542C13.1434 11.1542 14.0777 10.22 14.0777 9.0771C14.0777 7.93424 13.1434 7 12.0006 7C10.8577 7 9.92348 7.93424 9.92348 9.0771C9.92348 10.22 10.8577 11.1542 12.0006 11.1542Z" fill="currentColor"/>
																		<path d="M15.5652 13.814C15.5108 13.6779 15.4382 13.551 15.3566 13.4331C14.9393 12.8163 14.2954 12.4081 13.5697 12.3083C13.479 12.2993 13.3793 12.3174 13.3067 12.3718C12.9257 12.653 12.4722 12.7981 12.0006 12.7981C11.5289 12.7981 11.0754 12.653 10.6944 12.3718C10.6219 12.3174 10.5221 12.2902 10.4314 12.3083C9.70578 12.4081 9.05272 12.8163 8.64456 13.4331C8.56293 13.551 8.49036 13.687 8.43595 13.814C8.40875 13.8684 8.41781 13.9319 8.44502 13.9864C8.51759 14.1133 8.60828 14.2403 8.68991 14.3492C8.81689 14.5215 8.95295 14.6757 9.10715 14.8208C9.23413 14.9478 9.37925 15.0657 9.52439 15.1836C10.2409 15.7188 11.1026 15.9999 11.9915 15.9999C12.8804 15.9999 13.7421 15.7188 14.4586 15.1836C14.6038 15.0748 14.7489 14.9478 14.8759 14.8208C15.021 14.6757 15.1661 14.5215 15.2931 14.3492C15.3838 14.2312 15.4655 14.1133 15.538 13.9864C15.5833 13.9319 15.5924 13.8684 15.5652 13.814Z" fill="currentColor"/>
																	</svg>
																</span>SuperAgent
                                                    </div>
                                                </div>
                                                <div class="clicks rounded-3 overflow-hidden mb-0">
                                                    <a href="single-property-1.html"><img src="https://shreethemes.net/resido-2.3/resido/assets/img/p-10.jpg" class="img-fluid" alt="" /></a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="listing-caption-wrapper px-3">
                                            <div class="listing-detail-wrapper">
                                                <div class="listing-short-detail-wrap">
                                                    <div class="listing-short-detail">
                                                        <div class="d-flex align-items-center">
                                                            <span class="label for-rent prt-type me-2">For Rent</span><span class="label property-type property-cats">Building</span>
                                                        </div>
                                                        <h4 class="listing-name fw-medium fs-5 mb-1"><a href="single-property-1.html">Red Tiny Hearst Castle</a></h4>
                                                        <div class="prt-location text-muted-2">
																	<span class="svg-icon svg-icon-2hx">
																		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<path opacity="0.3" d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z" fill="currentColor"/>
																			<path d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z" fill="currentColor"/>
																		</svg>
																	</span>
                                                            210 Zirak Road, Canada
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="price-features-wrapper">
                                                <div class="list-fx-features d-flex align-items-center justify-content-between">
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-building-shield fs-sm"></i></div><span class="text-muted-2">3BHK</span>
                                                    </div>
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-bed fs-sm"></i></div><span class="text-muted-2">3 Beds</span>
                                                    </div>
                                                    <div class="listing-card d-flex align-items-center">
                                                        <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-clone fs-sm"></i></div><span class="text-muted-2">1700 SQFT</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="listing-detail-footer d-flex align-items-center justify-content-between py-4">
                                                <div class="listing-short-detail-flex">
                                                    <h6 class="listing-card-info-price m-0">$55,000</h6>
                                                </div>
                                                <div class="footer-flex">
                                                    <a href="property-detail.html" class="prt-view">
																<span class="svg-icon text-main svg-icon-2hx">
																	<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M15.43 8.56949L10.744 15.1395C10.6422 15.282 10.5804 15.4492 10.5651 15.6236C10.5498 15.7981 10.5815 15.9734 10.657 16.1315L13.194 21.4425C13.2737 21.6097 13.3991 21.751 13.5557 21.8499C13.7123 21.9488 13.8938 22.0014 14.079 22.0015H14.117C14.3087 21.9941 14.4941 21.9307 14.6502 21.8191C14.8062 21.7075 14.9261 21.5526 14.995 21.3735L21.933 3.33649C22.0011 3.15918 22.0164 2.96594 21.977 2.78013C21.9376 2.59432 21.8452 2.4239 21.711 2.28949L15.43 8.56949Z" fill="currentColor"/>
																		<path opacity="0.3" d="M20.664 2.06648L2.62602 9.00148C2.44768 9.07085 2.29348 9.19082 2.1824 9.34663C2.07131 9.50244 2.00818 9.68731 2.00074 9.87853C1.99331 10.0697 2.04189 10.259 2.14054 10.4229C2.23919 10.5869 2.38359 10.7185 2.55601 10.8015L7.86601 13.3365C8.02383 13.4126 8.19925 13.4448 8.37382 13.4297C8.54839 13.4145 8.71565 13.3526 8.85801 13.2505L15.43 8.56548L21.711 2.28448C21.5762 2.15096 21.4055 2.05932 21.2198 2.02064C21.034 1.98196 20.8409 1.99788 20.664 2.06648Z" fill="currentColor"/>
																	</svg>
																</span>
                                                    </a>
                                                </div>
                                            </div>

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
    <!-- ============================ Latest Property For Sale End ================================== -->

    <!-- ============================ All Property ================================== -->
    <section class="bg-light">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-10 text-center">
                    <div class="sec-heading center">
                        <h2>Featured Property For Sale</h2>
                        <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores</p>
                    </div>
                </div>
            </div>

            <div class="row list-layout">

                <!-- Single Property Start -->
                <div class="col-xl-6 col-lg-6 col-md-12">
                    <div class="property-listing property-1 bg-white p-2 rounded">

                        <div class="listing-img-wrapper">
                            <a href="single-property-2.html">
                                <img src="https://shreethemes.net/resido-2.3/resido/assets/img/p-1.jpg" class="img-fluid mx-auto rounded" alt="" />
                            </a>
                        </div>

                        <div class="listing-content">

                            <div class="listing-detail-wrapper-box">
                                <div class="listing-detail-wrapper d-flex align-items-center justify-content-between">
                                    <div class="listing-short-detail">
                                        <span class="label for-sale d-inline-flex mb-1">For Sale</span>
                                        <h4 class="listing-name mb-0"><a href="single-property-2.html">Adobe Property Advisors</a></h4>
                                        <div class="fr-can-rating">
                                            <i class="fas fa-star fs-xs filled"></i>
                                            <i class="fas fa-star fs-xs filled"></i>
                                            <i class="fas fa-star fs-xs filled"></i>
                                            <i class="fas fa-star fs-xs filled"></i>
                                            <i class="fas fa-star fs-xs"></i>
                                            <span class="reviews_text fs-sm text-muted ms-2">(42 Reviews)</span>
                                        </div>

                                    </div>
                                    <div class="list-price">
                                        <h6 class="listing-card-info-price text-main">$120M</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="price-features-wrapper">
                                <div class="list-fx-features d-flex align-items-center justify-content-between mt-3 mb-1">
                                    <div class="listing-card d-flex align-items-center">
                                        <div class="square--25 text-muted-2 fs-sm circle gray-simple me-1"><i class="fa-solid fa-building-shield fs-xs"></i></div><span class="text-muted-2 fs-sm">3BHK</span>
                                    </div>
                                    <div class="listing-card d-flex align-items-center">
                                        <div class="square--25 text-muted-2 fs-sm circle gray-simple me-1"><i class="fa-solid fa-bed fs-xs"></i></div><span class="text-muted-2 fs-sm">3 Beds</span>
                                    </div>
                                    <div class="listing-card d-flex align-items-center">
                                        <div class="square--25 text-muted-2 fs-sm circle gray-simple me-1"><i class="fa-solid fa-clone fs-xs"></i></div><span class="text-muted-2 fs-sm">1800 SQFT</span>
                                    </div>
                                </div>
                            </div>

                            <div class="listing-footer-wrapper">
                                <div class="listing-locate">
                                    <span class="listing-location text-muted-2"><i class="fa-solid fa-location-pin me-1"></i>Quice Market, Canada</span>
                                </div>
                                <div class="listing-detail-btn">
                                    <a href="single-property-2.html" class="btn btn-sm px-4 fw-medium btn-main">View</a>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <!-- Single Property End -->

                <!-- Single Property Start -->
                <div class="col-xl-6 col-lg-6 col-md-12">
                    <div class="property-listing property-1 bg-white p-2 rounded">

                        <div class="listing-img-wrapper">
                            <a href="single-property-2.html">
                                <img src="https://shreethemes.net/resido-2.3/resido/assets/img/p-2.jpg" class="img-fluid mx-auto rounded" alt="" />
                            </a>
                        </div>

                        <div class="listing-content">

                            <div class="listing-detail-wrapper-box">
                                <div class="listing-detail-wrapper d-flex align-items-center justify-content-between">
                                    <div class="listing-short-detail">
                                        <span class="label for-sale d-inline-flex mb-1">For Sale</span>
                                        <h4 class="listing-name mb-0"><a href="single-property-2.html">Agile Real Estate Group</a></h4>
                                        <div class="fr-can-rating">
                                            <i class="fas fa-star fs-xs filled"></i>
                                            <i class="fas fa-star fs-xs filled"></i>
                                            <i class="fas fa-star fs-xs filled"></i>
                                            <i class="fas fa-star fs-xs filled"></i>
                                            <i class="fas fa-star fs-xs"></i>
                                            <span class="reviews_text fs-sm text-muted ms-2">(34 Reviews)</span>
                                        </div>

                                    </div>
                                    <div class="list-price">
                                        <h6 class="listing-card-info-price text-main">$132M</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="price-features-wrapper">
                                <div class="list-fx-features d-flex align-items-center justify-content-between mt-3 mb-1">
                                    <div class="listing-card d-flex align-items-center">
                                        <div class="square--25 text-muted-2 fs-sm circle gray-simple me-1"><i class="fa-solid fa-building-shield fs-xs"></i></div><span class="text-muted-2 fs-sm">3BHK</span>
                                    </div>
                                    <div class="listing-card d-flex align-items-center">
                                        <div class="square--25 text-muted-2 fs-sm circle gray-simple me-1"><i class="fa-solid fa-bed fs-xs"></i></div><span class="text-muted-2 fs-sm">3 Beds</span>
                                    </div>
                                    <div class="listing-card d-flex align-items-center">
                                        <div class="square--25 text-muted-2 fs-sm circle gray-simple me-1"><i class="fa-solid fa-clone fs-xs"></i></div><span class="text-muted-2 fs-sm">1800 SQFT</span>
                                    </div>
                                </div>
                            </div>

                            <div class="listing-footer-wrapper">
                                <div class="listing-locate">
                                    <span class="listing-location text-muted-2"><i class="fa-solid fa-location-pin me-1"></i>Quice Market, Canada</span>
                                </div>
                                <div class="listing-detail-btn">
                                    <a href="single-property-2.html" class="btn btn-sm px-4 fw-medium btn-main">View</a>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <!-- Single Property End -->

                <!-- Single Property Start -->
                <div class="col-xl-6 col-lg-6 col-md-12">
                    <div class="property-listing property-1 bg-white p-2 rounded">

                        <div class="listing-img-wrapper">
                            <a href="single-property-2.html">
                                <img src="https://shreethemes.net/resido-2.3/resido/assets/img/p-3.jpg" class="img-fluid mx-auto rounded" alt="" />
                            </a>
                        </div>

                        <div class="listing-content">

                            <div class="listing-detail-wrapper-box">
                                <div class="listing-detail-wrapper d-flex align-items-center justify-content-between">
                                    <div class="listing-short-detail">
                                        <span class="label for-sale d-inline-flex mb-1">For Sale</span>
                                        <h4 class="listing-name mb-0"><a href="single-property-2.html">Bluebell Real Estate</a></h4>
                                        <div class="fr-can-rating">
                                            <i class="fas fa-star fs-xs filled"></i>
                                            <i class="fas fa-star fs-xs filled"></i>
                                            <i class="fas fa-star fs-xs filled"></i>
                                            <i class="fas fa-star fs-xs filled"></i>
                                            <i class="fas fa-star fs-xs"></i>
                                            <span class="reviews_text fs-sm text-muted ms-2">(124 Reviews)</span>
                                        </div>

                                    </div>
                                    <div class="list-price">
                                        <h6 class="listing-card-info-price text-main">$127M</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="price-features-wrapper">
                                <div class="list-fx-features d-flex align-items-center justify-content-between mt-3 mb-1">
                                    <div class="listing-card d-flex align-items-center">
                                        <div class="square--25 text-muted-2 fs-sm circle gray-simple me-1"><i class="fa-solid fa-building-shield fs-xs"></i></div><span class="text-muted-2 fs-sm">3BHK</span>
                                    </div>
                                    <div class="listing-card d-flex align-items-center">
                                        <div class="square--25 text-muted-2 fs-sm circle gray-simple me-1"><i class="fa-solid fa-bed fs-xs"></i></div><span class="text-muted-2 fs-sm">3 Beds</span>
                                    </div>
                                    <div class="listing-card d-flex align-items-center">
                                        <div class="square--25 text-muted-2 fs-sm circle gray-simple me-1"><i class="fa-solid fa-clone fs-xs"></i></div><span class="text-muted-2 fs-sm">1800 SQFT</span>
                                    </div>
                                </div>
                            </div>

                            <div class="listing-footer-wrapper">
                                <div class="listing-locate">
                                    <span class="listing-location text-muted-2"><i class="fa-solid fa-location-pin me-1"></i>Quice Market, Canada</span>
                                </div>
                                <div class="listing-detail-btn">
                                    <a href="single-property-2.html" class="btn btn-sm px-4 fw-medium btn-main">View</a>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <!-- Single Property End -->

                <!-- Single Property Start -->
                <div class="col-xl-6 col-lg-6 col-md-12">
                    <div class="property-listing property-1 bg-white p-2 rounded">

                        <div class="listing-img-wrapper">
                            <a href="single-property-2.html">
                                <img src="https://shreethemes.net/resido-2.3/resido/assets/img/p-4.jpg" class="img-fluid mx-auto rounded" alt="" />
                            </a>
                        </div>

                        <div class="listing-content">

                            <div class="listing-detail-wrapper-box">
                                <div class="listing-detail-wrapper d-flex align-items-center justify-content-between">
                                    <div class="listing-short-detail">
                                        <span class="label for-sale d-inline-flex mb-1">For Sale</span>
                                        <h4 class="listing-name mb-0"><a href="single-property-2.html">Strive Partners Realty</a></h4>
                                        <div class="fr-can-rating">
                                            <i class="fas fa-star fs-xs filled"></i>
                                            <i class="fas fa-star fs-xs filled"></i>
                                            <i class="fas fa-star fs-xs filled"></i>
                                            <i class="fas fa-star fs-xs filled"></i>
                                            <i class="fas fa-star fs-xs filled"></i>
                                            <span class="reviews_text fs-sm text-muted ms-2">(56 Reviews)</span>
                                        </div>

                                    </div>
                                    <div class="list-price">
                                        <h6 class="listing-card-info-price text-main">$132M</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="price-features-wrapper">
                                <div class="list-fx-features d-flex align-items-center justify-content-between mt-3 mb-1">
                                    <div class="listing-card d-flex align-items-center">
                                        <div class="square--25 text-muted-2 fs-sm circle gray-simple me-1"><i class="fa-solid fa-building-shield fs-xs"></i></div><span class="text-muted-2 fs-sm">3BHK</span>
                                    </div>
                                    <div class="listing-card d-flex align-items-center">
                                        <div class="square--25 text-muted-2 fs-sm circle gray-simple me-1"><i class="fa-solid fa-bed fs-xs"></i></div><span class="text-muted-2 fs-sm">3 Beds</span>
                                    </div>
                                    <div class="listing-card d-flex align-items-center">
                                        <div class="square--25 text-muted-2 fs-sm circle gray-simple me-1"><i class="fa-solid fa-clone fs-xs"></i></div><span class="text-muted-2 fs-sm">1800 SQFT</span>
                                    </div>
                                </div>
                            </div>

                            <div class="listing-footer-wrapper">
                                <div class="listing-locate">
                                    <span class="listing-location text-muted-2"><i class="fa-solid fa-location-pin me-1"></i>Quice Market, Canada</span>
                                </div>
                                <div class="listing-detail-btn">
                                    <a href="single-property-2.html" class="btn btn-sm px-4 fw-medium btn-main">View</a>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <!-- Single Property End -->

            </div>

            <!-- Pagination -->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 text-center mt-4">
                    <a href="listings-list-with-sidebar.html" class="btn btn-main px-lg-5 rounded">Browse More Properties</a>
                </div>
            </div>

        </div>
    </section>
    <!-- ============================ All Featured Property ================================== -->

    <!-- ============================ Explore Featured Agents Start ================================== -->
    <section>
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-10 text-center">
                    <div class="sec-heading center">
                        <h2>Explore Featured Agents</h2>
                        <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores</p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center g-4">

                <!-- Single Agent -->
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="agents-grid card rounded-3 shadow p-4">
                        <div class="agents-grid-wrap mb-4">
                            <div class="fr-grid-thumb mx-auto text-center mt-4 mb-3">
                                <a href="agent-page.html" class="d-inline-flex p-1 circle border">
                                    <img src="https://shreethemes.net/resido-2.3/resido/assets/img/user-1.jpg" class="img-fluid circle" width="100" alt="" />
                                </a>
                            </div>
                            <div class="fr-grid-deatil text-center">
                                <div class="rating-wrap d-block">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <span class="text-warning"><i class="bi bi-star-fill"></i></span>
                                        <span class="text-dark fw-semibold">4.8</span>
                                        <span class="text-muted fw-medium text-sm">(1.12k)</span>
                                    </div>
                                </div>
                                <div class="fr-grid-deatil-flex">
                                    <h5 class="fr-can-name lh-base mb-0"><a href="#">James N. Green</a></h5>
                                    <span class="agent-location text-muted"><i class="bi bi-geo-alt me-2"></i>San Francisco</span>
                                </div>
                            </div>
                        </div>

                        <div class="fr-grid-info d-flex align-items-center justify-content-center">
                            <a href="#" class="btn btn-outline-gray rounded-pill border-2 w-100">View Profile<i class="bi bi-arrow-up-right ms-2"></i></a>
                        </div>

                    </div>
                </div>

                <!-- Single Agent -->
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="agents-grid card rounded-3 shadow p-4">

                        <div class="superAgent position-absolute top-0 end-0 mt-3 me-3">
                            <div class="d-flex align-items-end justify-content-end gap-2">
                                <span class="label rounded-pill bg-green">Super Agent</span>
                            </div>
                        </div>
                        <div class="agents-grid-wrap mb-4">
                            <div class="fr-grid-thumb mx-auto text-center mt-4 mb-3">
                                <a href="agent-page.html" class="d-inline-flex p-1 circle border">
                                    <img src="https://shreethemes.net/resido-2.3/resido/assets/img/user-1.jpg" class="img-fluid circle" width="100" alt="" />
                                </a>
                            </div>
                            <div class="fr-grid-deatil text-center">
                                <div class="rating-wrap d-block">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <span class="text-warning"><i class="bi bi-star-fill"></i></span>
                                        <span class="text-dark fw-semibold">4.9</span>
                                        <span class="text-muted fw-medium text-sm">(1.62k)</span>
                                    </div>
                                </div>
                                <div class="fr-grid-deatil-flex">
                                    <h5 class="fr-can-name lh-base mb-0"><a href="#">Seema Gauranki</a></h5>
                                    <span class="agent-location text-muted"><i class="bi bi-geo-alt me-2"></i>San Diego</span>
                                </div>
                            </div>
                        </div>

                        <div class="fr-grid-info d-flex align-items-center justify-content-center">
                            <a href="#" class="btn btn-outline-gray rounded-pill border-2 w-100">View Profile<i class="bi bi-arrow-up-right ms-2"></i></a>
                        </div>

                    </div>
                </div>

                <!-- Single Agent -->
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="agents-grid card rounded-3 shadow p-4">
                        <div class="agents-grid-wrap mb-4">
                            <div class="fr-grid-thumb mx-auto text-center mt-4 mb-3">
                                <a href="agent-page.html" class="d-inline-flex p-1 circle border">
                                    <img src="https://shreethemes.net/resido-2.3/resido/assets/img/user-1.jpg" class="img-fluid circle" width="100" alt="" />
                                </a>
                            </div>
                            <div class="fr-grid-deatil text-center">
                                <div class="rating-wrap d-block">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <span class="text-warning"><i class="bi bi-star-fill"></i></span>
                                        <span class="text-dark fw-semibold">4.7</span>
                                        <span class="text-muted fw-medium text-sm">(2.14k)</span>
                                    </div>
                                </div>
                                <div class="fr-grid-deatil-flex">
                                    <h5 class="fr-can-name lh-base mb-0"><a href="#">Adam Walcorn</a></h5>
                                    <span class="agent-location text-muted"><i class="bi bi-geo-alt me-2"></i>San Antonio</span>
                                </div>
                            </div>
                        </div>

                        <div class="fr-grid-info d-flex align-items-center justify-content-center">
                            <a href="#" class="btn btn-outline-gray rounded-pill border-2 w-100">View Profile<i class="bi bi-arrow-up-right ms-2"></i></a>
                        </div>

                    </div>
                </div>

                <!-- Single Agent -->
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="agents-grid card rounded-3 shadow p-4">

                        <div class="hotAgent position-absolute top-0 end-0 mt-3 me-3">
                            <div class="d-flex align-items-end justify-content-end gap-2">
                                <span class="label rounded-pill bg-dark">Hot</span>
                            </div>
                        </div>
                        <div class="agents-grid-wrap mb-4">
                            <div class="fr-grid-thumb mx-auto text-center mt-4 mb-3">
                                <a href="agent-page.html" class="d-inline-flex p-1 circle border">
                                    <img src="https://shreethemes.net/resido-2.3/resido/assets/img/user-4.jpg" class="img-fluid circle" width="100" alt="" />
                                </a>
                            </div>
                            <div class="fr-grid-deatil text-center">
                                <div class="rating-wrap d-block">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <span class="text-warning"><i class="bi bi-star-fill"></i></span>
                                        <span class="text-dark fw-semibold">4.8</span>
                                        <span class="text-muted fw-medium text-sm">(1.63k)</span>
                                    </div>
                                </div>
                                <div class="fr-grid-deatil-flex">
                                    <h5 class="fr-can-name lh-base mb-0"><a href="#">Jasmin Khatri</a></h5>
                                    <span class="agent-location text-muted"><i class="bi bi-geo-alt me-2"></i>Los Angeles</span>
                                </div>
                            </div>
                        </div>

                        <div class="fr-grid-info d-flex align-items-center justify-content-center">
                            <a href="#" class="btn btn-outline-gray rounded-pill border-2 w-100">View Profile<i class="bi bi-arrow-up-right ms-2"></i></a>
                        </div>

                    </div>
                </div>

                <!-- Single Agent -->
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="agents-grid card rounded-3 shadow p-4">

                        <div class="hotAgent position-absolute top-0 end-0 mt-4 me-3">
                            <div class="d-flex align-items-end justify-content-end gap-2">
                                <span class="label rounded-pill bg-dark">Hot</span>
                            </div>
                        </div>
                        <div class="agents-grid-wrap mb-4">
                            <div class="fr-grid-thumb mx-auto text-center mt-3 mb-3">
                                <a href="agent-page.html" class="d-inline-flex p-1 circle border">
                                    <img src="https://shreethemes.net/resido-2.3/resido/assets/img/user-5.jpg" class="img-fluid circle" width="100" alt="" />
                                </a>
                            </div>
                            <div class="fr-grid-deatil text-center">
                                <div class="rating-wrap d-block">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <span class="text-warning"><i class="bi bi-star-fill"></i></span>
                                        <span class="text-dark fw-semibold">4.9</span>
                                        <span class="text-muted fw-medium text-sm">(1.35k)</span>
                                    </div>
                                </div>
                                <div class="fr-grid-deatil-flex">
                                    <h5 class="fr-can-name lh-base mb-0"><a href="#">Rudra K. Mathan</a></h5>
                                    <span class="agent-location text-muted"><i class="bi bi-geo-alt me-2"></i>Kansas City</span>
                                </div>
                            </div>
                        </div>

                        <div class="fr-grid-info d-flex align-items-center justify-content-center">
                            <a href="#" class="btn btn-outline-gray rounded-pill border-2 w-100">View Profile<i class="bi bi-arrow-up-right ms-2"></i></a>
                        </div>

                    </div>
                </div>

                <!-- Single Agent -->
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="agents-grid card rounded-3 shadow p-4">

                        <div class="agents-grid-wrap mb-4">
                            <div class="fr-grid-thumb mx-auto text-center mt-4 mb-3">
                                <a href="agent-page.html" class="d-inline-flex p-1 circle border">
                                    <img src="https://shreethemes.net/resido-2.3/resido/assets/img/user-6.jpg" class="img-fluid circle" width="100" alt="" />
                                </a>
                            </div>
                            <div class="fr-grid-deatil text-center">
                                <div class="rating-wrap d-block">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <span class="text-warning"><i class="bi bi-star-fill"></i></span>
                                        <span class="text-dark fw-semibold">4.7</span>
                                        <span class="text-muted fw-medium text-sm">(453)</span>
                                    </div>
                                </div>
                                <div class="fr-grid-deatil-flex">
                                    <h5 class="fr-can-name lh-base mb-0"><a href="#">Niharika Muthurk</a></h5>
                                    <span class="agent-location text-muted"><i class="bi bi-geo-alt me-2"></i>New Orleans</span>
                                </div>
                            </div>
                        </div>

                        <div class="fr-grid-info d-flex align-items-center justify-content-center">
                            <a href="#" class="btn btn-outline-gray rounded-pill border-2 w-100">View Profile<i class="bi bi-arrow-up-right ms-2"></i></a>
                        </div>

                    </div>
                </div>

                <!-- Single Agent -->
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="agents-grid card rounded-3 shadow p-4">

                        <div class="superAgent position-absolute top-0 end-0 mt-3 me-3">
                            <div class="d-flex align-items-end justify-content-end gap-2">
                                <span class="label rounded-pill bg-green">Super Agent</span>
                            </div>
                        </div>
                        <div class="agents-grid-wrap mb-4">
                            <div class="fr-grid-thumb mx-auto text-center mt-4 mb-3">
                                <a href="agent-page.html" class="d-inline-flex p-1 circle border">
                                    <img src="https://shreethemes.net/resido-2.3/resido/assets/img/user-7.jpg" class="img-fluid circle" width="100" alt="" />
                                </a>
                            </div>
                            <div class="fr-grid-deatil text-center">
                                <div class="rating-wrap d-block">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <span class="text-warning"><i class="bi bi-star-fill"></i></span>
                                        <span class="text-dark fw-semibold">4.8</span>
                                        <span class="text-muted fw-medium text-sm">(1.17k)</span>
                                    </div>
                                </div>
                                <div class="fr-grid-deatil-flex">
                                    <h5 class="fr-can-name lh-base mb-0"><a href="#">Grack Chappel</a></h5>
                                    <span class="agent-location text-muted"><i class="bi bi-geo-alt me-2"></i>Jacksonville</span>
                                </div>
                            </div>
                        </div>

                        <div class="fr-grid-info d-flex align-items-center justify-content-center">
                            <a href="#" class="btn btn-outline-gray rounded-pill border-2 w-100">View Profile<i class="bi bi-arrow-up-right ms-2"></i></a>
                        </div>

                    </div>
                </div>

                <!-- Single Agent -->
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="agents-grid card rounded-3 shadow p-4">
                        <div class="agents-grid-wrap mb-4">
                            <div class="fr-grid-thumb mx-auto text-center mt-3 mb-3">
                                <a href="agent-page.html" class="d-inline-flex p-1 circle border">
                                    <img src="https://shreethemes.net/resido-2.3/resido/assets/img/user-8.jpg" class="img-fluid circle" width="100" alt="" />
                                </a>
                            </div>
                            <div class="fr-grid-deatil text-center">
                                <div class="rating-wrap d-block">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <span class="text-warning"><i class="bi bi-star-fill"></i></span>
                                        <span class="text-dark fw-semibold">4.9</span>
                                        <span class="text-muted fw-medium text-sm">(2.22k)</span>
                                    </div>
                                </div>
                                <div class="fr-grid-deatil-flex">
                                    <h5 class="fr-can-name lh-base mb-0"><a href="#">Nikita Rajaswi</a></h5>
                                    <span class="agent-location text-muted"><i class="bi bi-geo-alt me-2"></i>Long Beach</span>
                                </div>
                            </div>
                        </div>

                        <div class="fr-grid-info d-flex align-items-center justify-content-center">
                            <a href="#" class="btn btn-outline-gray rounded-pill border-2 w-100">View Profile<i class="bi bi-arrow-up-right ms-2"></i></a>
                        </div>

                    </div>
                </div>

            </div>

            <!-- Pagination -->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 text-center mt-5">
                    <a href="listings-list-with-sidebar.html" class="btn btn-main px-lg-5 rounded">Explore More Agents</a>
                </div>
            </div>

        </div>
    </section>
    <div class="clearfix"></div>
    <!-- ============================ Explore Featured Agents End ================================== -->


    <!-- ============================ Smart Testimonials ================================== -->
    <section class="gray-bg">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-10 text-center">
                    <div class="sec-heading center">
                        <h2>Good Reviews by Customers</h2>
                        <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores</p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">

                <div class="col-lg-12 col-md-12">

                    <div class="smart-textimonials smart-center" id="smart-textimonials">

                        <!-- Single Item -->
                        <div class="item">
                            <div class="item-box">
                                <div class="smart-tes-author">
                                    <div class="st-author-box">
                                        <div class="st-author-thumb">
                                            <div class="quotes bg-main"><i class="fa-solid fa-quote-left"></i></div>
                                            <img src="https://shreethemes.net/resido-2.3/resido/assets/img/user-3.jpg" class="img-fluid" alt="" />
                                        </div>
                                    </div>
                                </div>

                                <div class="smart-tes-content">
                                    <p>Cicero famously orated against his political opponent Lucius Sergius Catilina. Occasionally the first Oration against Catiline is taken specimens.</p>
                                </div>

                                <div class="st-author-info">
                                    <h4 class="st-author-title">Adam Williams</h4>
                                    <span class="st-author-subtitle">CEO Of Microwoft</span>
                                </div>
                            </div>
                        </div>

                        <!-- Single Item -->
                        <div class="item">
                            <div class="item-box">
                                <div class="smart-tes-author">
                                    <div class="st-author-box">
                                        <div class="st-author-thumb">
                                            <div class="quotes bg-danger"><i class="fa-solid fa-quote-left"></i></div>
                                            <img src="https://shreethemes.net/resido-2.3/resido/assets/img/user-8.jpg" class="img-fluid" alt="" />
                                        </div>
                                    </div>
                                </div>

                                <div class="smart-tes-content">
                                    <p>Cicero famously orated against his political opponent Lucius Sergius Catilina. Occasionally the first Oration against Catiline is taken specimens.</p>
                                </div>

                                <div class="st-author-info">
                                    <h4 class="st-author-title">Retha Deowalim</h4>
                                    <span class="st-author-subtitle">CEO Of Apple</span>
                                </div>
                            </div>
                        </div>

                        <!-- Single Item -->
                        <div class="item">
                            <div class="item-box">
                                <div class="smart-tes-author">
                                    <div class="st-author-box">
                                        <div class="st-author-thumb">
                                            <div class="quotes bg-primary"><i class="fa-solid fa-quote-left"></i></div>
                                            <img src="https://shreethemes.net/resido-2.3/resido/assets/img/user-4.jpg" class="img-fluid" alt="" />
                                        </div>
                                    </div>
                                </div>

                                <div class="smart-tes-content">
                                    <p>Cicero famously orated against his political opponent Lucius Sergius Catilina. Occasionally the first Oration against Catiline is taken specimens.</p>
                                </div>

                                <div class="st-author-info">
                                    <h4 class="st-author-title">Sam J. Wasim</h4>
                                    <span class="st-author-subtitle">Pio Founder</span>
                                </div>
                            </div>
                        </div>

                        <!-- Single Item -->
                        <div class="item">
                            <div class="item-box">
                                <div class="smart-tes-author">
                                    <div class="st-author-box">
                                        <div class="st-author-thumb">
                                            <div class="quotes bg-success"><i class="fa-solid fa-quote-left"></i></div>
                                            <img src="https://shreethemes.net/resido-2.3/resido/assets/img/user-5.jpg" class="img-fluid" alt="" />
                                        </div>
                                    </div>
                                </div>

                                <div class="smart-tes-content">
                                    <p>Cicero famously orated against his political opponent Lucius Sergius Catilina. Occasionally the first Oration against Catiline is taken specimens.</p>
                                </div>

                                <div class="st-author-info">
                                    <h4 class="st-author-title">Usan Gulwarm</h4>
                                    <span class="st-author-subtitle">CEO Of Facewarm</span>
                                </div>
                            </div>
                        </div>

                        <!-- Single Item -->
                        <div class="item">
                            <div class="item-box">
                                <div class="smart-tes-author">
                                    <div class="st-author-box">
                                        <div class="st-author-thumb">
                                            <div class="quotes bg-primary"><i class="fa-solid fa-quote-left"></i></div>
                                            <img src="https://shreethemes.net/resido-2.3/resido/assets/img/user-6.jpg" class="img-fluid" alt="" />
                                        </div>
                                    </div>
                                </div>

                                <div class="smart-tes-content">
                                    <p>Cicero famously orated against his political opponent Lucius Sergius Catilina. Occasionally the first Oration against Catiline is taken specimens.</p>
                                </div>

                                <div class="st-author-info">
                                    <h4 class="st-author-title">Shilpa Shethy</h4>
                                    <span class="st-author-subtitle">CEO Of Zapple</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </section>
    <!-- ============================ Smart Testimonials End ================================== -->


    <!-- ============================ Price Table Start ================================== -->
    <section>
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-10 text-center">
                    <div class="sec-heading center">
                        <h2>See our packages</h2>
                        <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores</p>
                    </div>
                </div>
            </div>

            <div class="row align-items-center justify-content-center g-lg-4 g-md-3 g-4">

                <!-- Single Package -->
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <div class="card border rounded-4 pricing-wrap p-3">
                        <div class="card-body">

                            <div class="cards-heads mb-3">
                                <div class="d-flex align-items-center justify-content-between gap-1">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="price-icon"><i class="bi bi-brilliance"></i></div>
                                        <div class="price-title"><h3 class="m-0">Basic</h3></div>
                                    </div>
                                </div>
                            </div>

                            <div class="pricing-midds mb-3">
                                <h2 class="lh-base m-0">$79</h2>
                                <p class="text-md text-muted">Per user/month. Billed monthly</p>
                            </div>

                            <div class="pricing-caps py-3">
                                <div class="list-heading mb-2"><h5 class="fw-normal">For medium-size team</h5></div>
                                <div class="list-wrap">
                                    <ul class="p-0">
                                        <li><span class="check"><i class="bi bi-check"></i></span>5+ Listings</li>
                                        <li><span class="check"><i class="bi bi-check"></i></span>Contact With Agent</li>
                                        <li><span class="check"><i class="bi bi-check"></i></span>3 Month Validity</li>
                                        <li><span class="check"><i class="bi bi-check"></i></span>7x24 Fully Support</li>
                                        <li><span class="check"><i class="bi bi-check"></i></span>50GB Space</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="pricing-button">
                                <a href="#" class="btn btn-dark rounded full-width">Choose Plan</a>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Single Package -->
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <div class="card border rounded-4 pricing-wrap p-3">
                        <div class="card-body">

                            <div class="cards-heads mb-3">
                                <div class="d-flex align-items-center justify-content-between gap-1">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="price-icon"><i class="bi bi-gem"></i></div>
                                        <div class="price-title"><h3 class="m-0">Standard</h3></div>
                                    </div>
                                    <div class="mostpopular"><span class="popular">Most Popular</span></div>
                                </div>
                            </div>

                            <div class="pricing-midds mb-3">
                                <h2 class="lh-base m-0">$299</h2>
                                <p class="text-md text-muted">Per user/month. Billed monthly</p>
                            </div>

                            <div class="pricing-caps py-3">
                                <div class="list-heading mb-2"><h5 class="fw-normal">For multi group team</h5></div>
                                <div class="list-wrap">
                                    <ul class="p-0">
                                        <li><span class="check"><i class="bi bi-check"></i></span>5+ Listings</li>
                                        <li><span class="check"><i class="bi bi-check"></i></span>Contact With Agent</li>
                                        <li><span class="check"><i class="bi bi-check"></i></span>3 Month Validity</li>
                                        <li><span class="check"><i class="bi bi-check"></i></span>7x24 Fully Support</li>
                                        <li><span class="check"><i class="bi bi-check"></i></span>50GB Space</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="pricing-button">
                                <a href="#" class="btn btn-main rounded full-width">Choose Plan</a>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Single Package -->
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <div class="card border rounded-4 pricing-wrap p-3">
                        <div class="card-body">

                            <div class="cards-heads mb-3">
                                <div class="d-flex align-items-center justify-content-between gap-1">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="price-icon"><i class="bi bi-cup-hot-fill"></i></div>
                                        <div class="price-title"><h3 class="m-0">Enterprizes</h3></div>
                                    </div>
                                </div>
                            </div>

                            <div class="pricing-midds mb-3">
                                <h2 class="lh-base m-0">$799</h2>
                                <p class="text-md text-muted">For large industries and team.</p>
                            </div>

                            <div class="pricing-caps py-3">
                                <div class="list-heading mb-2"><h5 class="fw-normal">For large-size team</h5></div>
                                <div class="list-wrap">
                                    <ul class="p-0">
                                        <li><span class="check"><i class="bi bi-check"></i></span>5+ Listings</li>
                                        <li><span class="check"><i class="bi bi-check"></i></span>Contact With Agent</li>
                                        <li><span class="check"><i class="bi bi-check"></i></span>3 Month Validity</li>
                                        <li><span class="check"><i class="bi bi-check"></i></span>7x24 Fully Support</li>
                                        <li><span class="check"><i class="bi bi-check"></i></span>50GB Space</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="pricing-button">
                                <a href="#" class="btn btn-dark rounded full-width">Choose Plan</a>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>
    <!-- ============================ Price Table End ================================== -->

    <!-- ========================== Download App Section =============================== -->
    <section class="bg-light">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-7 col-md-12 col-sm-12 content-column">
                    <div class="content_block_2">
                        <div class="content-box">
                            <div class="sec-title light">
                                <p class="d-inline-flex align-items-center justify-content-center label bg-main text-light">Download apps</p>
                                <h2 class="fs-1 lh-base">Download App Free App For Android and iPhone</h2>
                            </div>
                            <div class="text">
                                <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto accusantium.</p>
                            </div>
                            <div class="btn-box clearfix mt-5">
                                <a href="index.html" class="download-btn play-store">
                                    <i class="fab fa-google-play"></i>
                                    <span>Download on</span>
                                    <h3>Google Play</h3>
                                </a>

                                <a href="index.html" class="download-btn app-store">
                                    <i class="fab fa-apple"></i>
                                    <span>Download on</span>
                                    <h3>App Store</h3>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 col-md-12 col-sm-12 image-column">
                    <div class="image-box">
                        <figure class="image"><img src="https://shreethemes.net/resido-2.3/resido/assets/img/app.png" class="img-fluid" alt=""></figure>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ========================== Download App Section =============================== -->

    <!-- ============================ Call To Action ================================== -->
    <section class="bg-main call-to-act-wrap">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    <div class="call-to-act">
                        <div class="call-to-act-head">
                            <h3>Want to Become a Real Estate Agent?</h3>
                            <span>We'll help you to grow your career and growth.</span>
                        </div>
                        <a href="#" class="btn btn-call-to-act">SignUp Today</a>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- ============================ Call To Action End ================================== -->

@endsection
