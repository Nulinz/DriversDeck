@extends('landing.layouts.app')
@section('title', 'Corprate')
@section('content')
    <style>
        .text-justi {
            text-align: justify;
            text-justify: inter-word;
        }

        .fs-work {
            font-size: 14px;
        }

        .fs-work-title {
            font-size: 17.5px;
            font-weight: 550;
            color: #000;
        }

        .carousel-indicators [data-bs-target] {
            width: 30px;
            height: 5px;
            background-color: #0d6efd;
        }

        .carousel-item img {
            max-height: 500px;
            object-fit: contain;
        }

        .tab-btns {
            margin-bottom: 20px;
        }

        /* counter */
        .counter-box {
            display: block;
            background: #f6f6f6;
            padding: 40px 20px 37px;
            text-align: center
        }

        .counter-box p {
            margin: 5px 0 0;
            padding: 0;
            color: #909090;
            font-size: 18px;
            font-weight: 500
        }

        .counter-box i {
            font-size: 60px;
            margin: 0 0 15px;
            color: #d2d2d2
        }

        .counter {
            display: block;
            font-size: 32px;
            font-weight: 700;
            color: #666;
            line-height: 28px
        }

        .counter-box.colored {
            background: #ffc107;
            transition: all 0.3s ease;
        }

        .counter-box1:hover {
            background-color: rgb(116, 141, 215) !important;
            transition: all 0.3s ease;
        }

        .counter-box2:hover {
            background-color: #65ab5e !important;
            transition: all 0.3s ease;
        }

        .counter-box3:hover {
            background-color: #ffc107 !important;
            transition: all 0.3s ease;
        }

        .counter-box:hover i,
        .counter-box:hover .counter,
        .counter-box:hover p {
            color: #fff;
        }

        .counter-box:hover {
            background-color: #ffc107;
            transition: all 0.3s ease;
        }

        .counter-box.colored p,
        .counter-box.colored i,
        .counter-box.colored .counter {
            color: #fff
        }

        /* slider */
        .logo-slider {
            position: relative;
            height: 100px;
        }

        .logo-track {
            display: flex;
            animation: scroll-left 30s linear infinite;
            width: max-content;
        }

        .logo-item img {
            height: 60px;
            object-fit: contain;
            filter: grayscale(100%);
            transition: all 0.3s;
        }

        .logo-item img:hover {
            filter: grayscale(0%);
            transform: scale(1.1);
        }

        @keyframes scroll-left {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .post-img {
            width: 100px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
    <div class="py-lg-5 container py-4">
        <div class="row align-items-center gy-3">
            <!-- Left Text Section -->
            <div class="col-lg-6 col-sm-12">
                <h1 class="fw-semibold display-4">Hire a Truck or Car Driver Today!</h1>
                <p class="text-muted fw-semibold lh-base mt-4 text-justi">
                    Hire as many full-time drivers as your business needs, no restrictions.
                    Our platform provides a steady supply of verified, experienced, and
                    professional drivers ready for long-term roles. Scale your operations with
                    ease, reduce downtime, and ensure consistent performance with
                    dependable drivers on demand.
                </p>
                <!--<a href="#" class="btn btn-dark mt-lg-4 mt-1 px-5">-->
                <!--    Get Start <i class="fas fa-arrow-right ms-4"></i>-->
                <!--</a>-->
            </div>
            <!-- Right Images Section -->
            <div class="col-lg-6 col-sm-12">
                <div class="row g-4">
                    <div class="col-12 d-none d-lg-block">
                        <div class="d-flex align-items-stretch justify-content-center" style="min-height: 100%;">
                            <!-- Left Column -->
                            <div class="quarter-circle position-relative overflow-hidden">
                                <img src="{{ asset('assets/images/landing/Rectangle 23805.png') }}" alt="Vans"
                                    class="img-fluid w-100 h-100 object-fit-cover position-absolute start-0 top-0">
                            </div>
                            <!-- Right Column -->
                            <div class="highlight-card w-50 d-flex flex-column justify-content-center bg-light ms-auto p-3">
                                <h2 class="fs-1">Unlimited</h2>
                                <p class="text-muted fs-6 fw-semibold mb-0">
                                    You get unlimited access to verified, experienced permanent drivers ready to join your
                                    team on a full-time basis.
                                </p>
                                <div class="progress mt-4">
                                    <div class="progress-bar bg-dark" role="progressbar" style="width: 65%"
                                        aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 py-2">
                        <div class="mt-lg-0 truck-image-card position-relative rounded-4 overflow-hidden">
                            <img src="{{ asset('assets/images/landing/Rectangle 23804.png') }}" alt="Trucks"
                                class="img-fluid w-100">

                            <div
                                class="text-lg-start d-flex flex-column justify-content-center align-items-start text-center text-white">
                                {{-- <div class="fw-normal fs-6 my-3">Drive More Traffic and Sales</div> --}}
                                {{-- <div class="display-6">Drive more traffic <br> and product sales</div> --}}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
<!--            <section class="bg-light py-5" >-->
<!--        <div class="container text-center">-->
<!--            <h3 class="fw-semibold py-4">Trusted By</h3>-->
<!--            <div class="logo-slider position-relative my-4 overflow-hidden">-->
<!--                <div class="logo-track d-flex align-items-center">-->
                    <!-- Logos (Repeat these twice for seamless effect) -->
<!--                    @foreach (range(1, 2) as $loop)-->
                        <!-- Repeat for infinite illusion -->
<!--                        <div class="logo-item mx-4"><img-->
<!--                                src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Google_2015_logo.svg" alt="Google">-->
<!--                        </div>-->
<!--                        <div class="logo-item mx-4"><img-->
<!--                                src="https://upload.wikimedia.org/wikipedia/commons/4/44/Microsoft_logo.svg" alt="Microsoft">-->
<!--                        </div>-->
<!--                        <div class="logo-item mx-4"><img-->
<!--                                src="https://upload.wikimedia.org/wikipedia/commons/a/a9/Amazon_logo.svg" alt="Amazon"></div>-->
<!--                        <div class="logo-item mx-4"><img-->
<!--                                src="https://upload.wikimedia.org/wikipedia/commons/0/05/Facebook_Logo_%282019%29.png"-->
<!--                                alt="Facebook"></div>-->
<!--                        <div class="logo-item mx-4"><img-->
<!--                                src="https://upload.wikimedia.org/wikipedia/commons/c/ca/LinkedIn_logo_initials.png"-->
<!--                                alt="LinkedIn"></div>-->
<!--                        <div class="logo-item mx-4"><img-->
<!--                                src="https://upload.wikimedia.org/wikipedia/commons/c/cc/Uber_logo_2018.png" alt="Uber"></div>-->
<!--                    @endforeach-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </section>-->

<!--<br><br><br>-->

    <!-- Display Section -->
    <!--<section class="section-pad">-->
    <!--    <div class="container">-->
    <!--        <div class="row align-items-center justify-content-between">-->
                <!-- Text block: Show after image on mobile, before on large -->
    <!--            <div class="col-lg-5 col-sm-12 order-lg-1 mb-lg-0 order-2 mb-4">-->
    <!--                <h2 class="fw-bold text-1 mb-2">Professional Drivers</h2>-->
    <!--                <p class="info-1 fw-normal lh-base py-2 text-justify">-->
    <!--                    Immerse yourself in a world of possibilities with our extensive range of vehicles. From sleek sedans-->
    <!--                    to rugged SUVs and luxurious convertibles, we have the-->
    <!--                    perfect wheels to match your style, preferences, and the demands of your adventure.-->
    <!--                </p>-->
    <!--                <a class="btn btn-mg bg-dark text-white">Get Started</a>-->
    <!--            </div>-->

                <!-- Image block: Show first on mobile, last on large -->
    <!--            <div class="col-lg-6 col-sm-12 order-lg-2 order-1">-->
    <!--                <img src="{{ asset('assets/images/landing/image 100.png') }}" class="d-block mx-lg-auto img-fluid"-->
    <!--                    alt="Drivers_Deck" width="450" height="450" loading="lazy">-->
    <!--            </div>-->
    <!--        </div>-->

            <div class="row align-items-center justify-content-between">
                <div class="col-lg-5 col-sm-12 mb-lg-0 mb-4">
                    <img src="{{ asset('assets/images/landing/image 101.png') }}" class="d-block mx-lg-auto img-fluid"
                        alt="Drivers_Deck" width="450" height="450" loading="lazy">
                </div>
                <div class="col-lg-6 col-sm-12">
                    <h2 class="fw-bold text-1 mb-3">Hire Skilled Logistics & Transport Drivers, Construction & Mining Driver
                        Recruitment Made Easy</h2>
                    <p class="info-1 fw-normal lh-base">
                        Immerse yourself in a world of possibilities with our extensive range of vehicles. From sleek sedans
                        to rugged SUVs and luxurious convertibles, we have the
                        perfect wheels to match your style, preferences, and the demands of your adventure.
                    </p>
                    <!--<a class="btn btn-mg bg-dark text-white">Get Started</a>-->
                </div>
            </div>

            {{-- <div class="row align-items-center justify-content-between" data-aos="fade-right">
                <div class="col-lg-5 col-sm-12 order-lg-1 order-2">
                    <h2 class="fw-bold text-1 mb-3"></h2>
                    <p class="info-1 fw-normal lh-base">
                        Immerse yourself in a world of possibilities with our extensive range of vehicles. From sleek sedans to rugged SUVs and luxurious convertibles, we have the
                        perfect wheels to match your style, preferences, and the demands of your adventure. </p>
                    <a class="btn btn-mg bg-dark text-white">Get Started</a>
                </div>
                <div class="col-lg-6 col-sm-12 order-lg-2 mb-lg-0 order-1">
                    <img src="{{ asset('assets/images/landing/image 102.png') }}" class="d-block mx-lg-auto img-fluid" alt="Drivers_Deck" width="450" height="450" loading="lazy">
                </div>
            </div> --}}
        </div>
    </section>

    <!-- about Section -->
    <section class="bg-light py-lg-5 py-4">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                 <!--justify-content-between-->
                <!--<div class="col-md-6 m-0">-->
                <!--    <h2 class="display-6 fw-semibold mb-lg-5 mb-4">Flexible Pricing Plans</h2>-->

                <!--    <div class="row">-->
                <!--        <div class="col-sm-6 mb-md-0 mb-3">-->
                <!--            <div class="card text-center-lg h-100 border text-start">-->
                <!--                <div class="card-body d-flex flex-column p-4">-->
                <!--                    <div class="mb-lg-2 mb-0">-->
                <!--                        <h1 class="display-6 fw-bold text-start">₹ 17,000</h1>-->
                <!--                    </div>-->
                <!--                    <ul class="list-unstyled text-start">-->
                <!--                        <li class="mb-lg-2 fw-semibold mb-0">-->
                <!--                            <i class="fa fa-check-circle fs-4 step-icon me-2 text-success"></i>-->
                <!--                            5 No of Permanent Drivers-->
                <!--                        </li>-->

                <!--                        <li class="mb-lg-2 fw-semibold mb-0">-->
                <!--                            <i class="fa fa-check-circle fs-4 step-icon me-2 text-success"></i>-->
                <!--                            1 Year-->
                <!--                        </li>-->
                                        <!--<li class="mb-lg-2 fw-semibold mb-0"><i-->
                                        <!--        class="fa-solid fa-circle-xmark fs-4 step-icon me-2 text-danger"></i>-->
                                        <!--    {{-- <i class="fa fa-check-circle"></i> --}}-->
                                        <!--    No acting drivers-->
                                        <!--</li>-->
                <!--                        {{-- <li class="mb-lg-2 fw-semibold mb-0">-->
                <!--                            <i class="fa fa-check-circle fs-4 step-icon me-2"></i>-->
                <!--                            6 Months --}}-->
                <!--                        </li>-->
                <!--                    </ul>-->
                <!--                    <div class="">-->
                <!--                        <a href="{{ route('auth.register_details') }}" class="btn btn-dark mt-lg-4 mt-0 px-5">-->
                <!--                            Get Start <i class="fas fa-arrow-right ms-4"></i>-->
                <!--                        </a>-->
                <!--                    </div>-->
                <!--                </div>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--        <div class="col-sm-6 mb-md-0 mb-3">-->
                <!--            <div class="card text-center-lg h-100 border text-start">-->
                <!--                <div class="card-body d-flex flex-column p-4">-->
                <!--                    <div class="mb-lg-2 mb-0">-->
                <!--                        <h1 class="display-6 fw-bold text-start">₹ 20,000</h1>-->
                <!--                    </div>-->
                <!--                    <ul class="list-unstyled text-start">-->
                <!--                        <li class="mb-lg-2 fw-semibold mb-0">-->
                <!--                            <i class="fa fa-check-circle fs-4 step-icon me-2 text-success"></i>-->
                <!--                            Unlimited Permanent Drivers-->
                <!--                        </li>-->
                                        <!--<li class="mb-lg-2 fw-semibold mb-0">-->
                                        <!--    <i class="fa fa-check-circle fs-4 step-icon me-2 text-success"></i>-->
                                        <!--    Unlimited Acting Drivers-->
                                        <!--</li>-->
                <!--                        <li class="mb-lg-2 fw-semibold mb-0">-->
                <!--                            <i class="fa fa-check-circle fs-4 step-icon me-2 text-success"></i>-->
                <!--                            1 year-->
                <!--                        </li>-->
                <!--                    </ul>-->
                <!--                    <div class="">-->
                <!--                        <a href="{{ route('auth.register_details') }}" class="btn btn-dark mt-lg-4 mt-0 px-5">-->
                <!--                            Get Start <i class="fas fa-arrow-right ms-4"></i>-->
                <!--                        </a>-->
                <!--                    </div>-->
                <!--                </div>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
                <div class="col-md-5 m-lg-0 mt-3">
                    <h3 class="fw-semibold fs-2 mb-1">What You Get</h3>

                    <ul class="list-unstyled step-list mb-0">
                        <li>
                            <h5 class="fs-5 fw-semibold mb-0">Unlimited Hiring</h5>
                            <p class="fs-6 fw-semibold text-theme">Hire as many permanent drivers as your business needs
                            </p>
                        </li>
                        <li>
                            <h5 class="fs-5 fw-semibold mb-0">Verified & Experienced</h5>
                            <p class="fs-6 fw-semibold text-theme">All drivers are background-checked, license-verified</p>
                        </li>
                        <li>
                            <h5 class="fs-5 fw-semibold mb-0">Industry-Specific Drivers</h5>
                            <p class="fs-6 fw-semibold text-theme">Whether you need drivers for trucks, buses, delivery
                                vans - we’ve got you covered.</p>
                        </li>
                        <li>
                            <h5 class="fs-5 fw-semibold mb-0">Full-Time Commitment</h5>
                            <p class="fs-6 fw-semibold text-theme">Our drivers are ready to work on a long-term basis with
                                fixed monthly salaries and contracts.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection
