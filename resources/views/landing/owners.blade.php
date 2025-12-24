@extends('landing.layouts.app')
@section('title', 'Owners')
@section('content')
    <style>
        .text-justi {
            text-align: justify;
            text-justify: inter-word;
        }
    </style>
    <!-- Hero Section -->
    <section class="hero">
        <div class="position-relative banner-sec2">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <!-- Text Content -->
                    <div class="col-md-6 px-md-0 text-content2">
                        <h1 class="display-5 fw-semibold pb-2" style="line-height: 1.2;">
                            Hire a Car driver today <br>
                            <span class="fs-4"> Hire Trusted Drivers with Us...</span>

                        </h1>
                        <p class="text-dark fw-semibold fs-6 lh-base w-600 mb-2 text-justi">Car owners, drive stress-free
                            with
                            professional drivers from Drivers Deck Consultancy Pvt. Ltd. We provide verified, experienced,
                            and reliable drivers for your personal or professional needs. Whether for daily use, office
                            travel, or long-distance trips, our drivers are trained in safe driving and vehicle care. We
                            handle background checks, license verification, and skill assessments, ensuring complete peace
                            of mind. Flexible hiring options are available, including full-time and part-time. Enjoy timely,
                            courteous service and prompt support if replacements are needed. Trust Drivers Deck for safe,
                            comfortable, and dependable driving solutions. Contact us today to hire the right driver!.</p>
                        <!--<a href="#" class="btn btn-dark mt-lg-4 mt-1 px-5">-->
                        <!--    Download <i class="fas fa-arrow-right ms-4"></i>-->
                        <!--</a>-->
                    </div>

                    <!-- Image Content -->
                    <div class="col-md-6 position-relative p-0" style="overflow: hidden;">
                        <!-- White fade overlay -->
                        <div class="white-fade-left"></div>
                        <img src="{{ asset('assets/images/landing/image_100_dup.png') }}"
                            class="img-fluid w-100 landing-img2" alt="Hero Trucks">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Display Section -->
    <section class="section-pad">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <!-- Text block: Show after image on mobile, before on large -->
                <div class="col-lg-5 col-sm-12 order-lg-1 mb-lg-0 order-2 mb-4">
                    <h2 class="fw-bold text-1 mb-2">Professional Drivers</h2>
                    <p class="info-1 fw-normal lh-base py-2 text-justify">
                        Immerse yourself in a world of possibilities with our extensive range of vehicles. From sleek sedans
                        to rugged SUVs and luxurious convertibles, we have the
                        perfect wheels to match your style, preferences, and the demands of your adventure.
                    </p>
                    {{-- <a class="btn btn-mg bg-dark text-white">Get Started</a> --}}
                </div>

                <!-- Image block: Show first on mobile, last on large -->
                <div class="col-lg-6 col-sm-12 order-lg-2 order-1">
                    <img src="{{ asset('assets/images/landing/image_95_dup.png') }}"
                        class="d-block mx-lg-auto img-fluid rounded-3" alt="Drivers_Deck" width="500" height="500"
                        loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- about Section -->
    <section class="bg-light py-lg-5 py-4">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-lg-5 col-sm-12 order-lg-1 order-2">
                    <!--<img src="{{ asset('assets/images/landing/image 101.png') }}" class="d-block mx-lg-auto img-fluid"-->
                    <!--    alt="Drivers_Deck" width="450" height="450" loading="lazy">-->

                </div>
                <div class="col-lg-6 col-sm-12 order-lg-2 mb-lg-0 order-1 mb-4">
                    <h3 class="fw-semibold fs-2 mb-1">What You Get</h3>

                    <ul class="list-unstyled step-list mb-0">
                        {{-- <li>
                            <h5 class="fs-5 fw-semibold mb-0">Unlimited Hiring</h5>
                            <p class="fs-6 fw-semibold text-theme">Hire as many permanent drivers as your business needs</p>
                        </li> --}}
                        <li>
                            <h5 class="fs-5 fw-semibold mb-0">Verified & Experienced</h5>
                            <p class="fs-6 fw-semibold text-theme">All drivers are background-checked, license-verified</p>
                        </li>
                        {{-- <li>
                            <h5 class="fs-5 fw-semibold mb-0">Industry-Specific Drivers</h5>
                            <p class="fs-6 fw-semibold text-theme">Whether you need drivers for trucks, buses, delivery vans
                                - we’ve got you covered.</p>
                        </li> --}}
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

    <!-- App Section -->
    <!--<section class="section_bg" style="background-image: url('assets/images/landing/Background.png');">-->
    <!--    <div class="container">-->
    <!--        <div class="row align-items-center g-4">-->
    <!--            <div class="col-md-4 m-0 text-center">-->
    <!--                <img src="{{ asset('assets/images/landing/Nothing Phone 1.png') }}" class="img-fluid phone-img"-->
    <!--                    alt="" style="width: 350px;">-->
    <!--            </div>-->
    <!--            <div class="col-md-5 me-auto">-->
    <!--                <h2>Download the Driver Deck App</h2>-->
    <!--                <p class="fw-bold fs-6 mt-4">Driver Deck is available on both Android and iOS devices. Simply visit the-->
    <!--                    Google Play Store or Apple App Store and search for Driver-->
    <!--                    Deck.</p>-->

    <!--                <div class="d-grid align-items-center justify-content-between">-->
    <!--                    <img src="" alt="platstore_banner" class="img-fluid">-->
    <!--                    <img src="" alt="platstore_banner" class="img-fluid">-->
    <!--                </div>-->
    <!--            </div>-->
    <!--            <div class="col-md-3 text-center">-->
    <!--                <img src="{{ asset('assets/images/landing/qr.png') }}" alt="QR Code" class="img-fluid">-->
    <!--                <h5 class="mb-4 mt-2">Scan QR to download</h5>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</section>-->
@endsection