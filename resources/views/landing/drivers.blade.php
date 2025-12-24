@extends('landing.layouts.app')
@section('title', 'Drivers')
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
            <div class="container-fluid ">
                <div class="row align-items-center">
                    <!-- Text Content -->
                    <div class="col-md-6 px-md-0 text-content2">
                        <h1 class="display-5 fw-semibold pb-2" style="line-height: 1.2;">
                            Join Drivers Deck Consultancy Pvt. Ltd - Drive Towards a Better Future

                        </h1>
                        <p class="text-dark fw-semibold fs-6 mb-2 lh-base w-600 text-justi">Are you a skilled and
                            responsible
                            driver
                            seeking a stable and rewarding career opportunity? Join Drivers Deck Consultancy Pvt. Ltd., a
                            trusted name in driver placement services. We offer job opportunities with verified car owners,
                            flexible working hours, and competitive salaries.<br>
                            At Drivers Deck, we value professionalism, punctuality, and safe driving. We provide full
                            support for job placement. Whether you're seeking full-time or part-time, we help match you with
                            the right clients.<br>
                            Start your journey with us today and build a successful driving career!

                            .</p>
                        <!--<a href="#" class="btn btn-dark mt-1 mt-lg-4 px-5">-->
                        <!--    Download <i class="ms-4 fas fa-arrow-right"></i>-->
                        <!--</a>-->
                    </div>

                    <!-- Image Content -->
                    <div class="col-md-6 p-0 position-relative" style="overflow: hidden;">
                        <!-- White fade overlay -->
                        <div class="white-fade-left"></div>
                        <img src="{{ asset('assets/images/landing/image 96.png') }}" class="img-fluid w-100 landing-img2"
                            alt="Hero Trucks">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- about Section -->
    {{-- <section class="bg-light py-4 py-lg-5">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-md-6 m-0">
                    <h2 class="display-6 fw-semibold mb-4 mb-lg-5">Flexible Pricing Plans</h2>

                    <div class="row">
                        <div class="col-sm-6 mb-3 mb-md-0">
                            <div class="card border text-start text-center-lg h-100">
                                <div class="card-body d-flex flex-column p-4">
                                    <div class="mb-0 mb-lg-2">
                                        <h1 class="display-6 fw-bold text-start">₹ 8,000</h1>
                                    </div>
                                    <ul class="text-start list-unstyled">
                                        <li class="mb-0 mb-lg-2 fw-semibold">
                                            <i class="fa fa-check-circle fs-4 me-2 step-icon"></i>
                                            Permanent Drivers
                                        </li>
                                        <li class="mb-0 mb-lg-2 fw-semibold">
                                            <i class="fa fa-check-circle fs-4 me-2 step-icon"></i>
                                            6 Months
                                        </li>
                                    </ul>
                                    <div class="">
                                        <a href="#" class="btn btn-dark mt-0 mt-lg-4 px-5">
                                            Get Start <i class="ms-4 fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3 mb-md-0">
                            <div class="card border text-start text-center-lg h-100">
                                <div class="card-body d-flex flex-column p-4">
                                    <div class="mb-0 mb-lg-2">
                                        <h1 class="display-6 fw-bold text-start">₹ 12,000</h1>
                                    </div>
                                    <ul class="text-start list-unstyled">
                                        <li class="mb-0 mb-lg-2 fw-semibold">
                                            <i class="fa fa-check-circle fs-4 me-2 step-icon"></i>
                                            Unlimited Permanent Drivers
                                        </li>
                                        <li class="mb-0 mb-lg-2 fw-semibold">
                                            <i class="fa fa-check-circle fs-4 me-2 step-icon"></i>
                                            1 year
                                        </li>
                                    </ul>
                                    <div class="">
                                        <a href="#" class="btn btn-dark mt-0 mt-lg-4 px-5">
                                            Get Start <i class="ms-4 fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 mt-3 m-lg-0">
                    <h3 class="fw-semibold fs-2 mb-1">What You Get</h3>

                    <ul class="list-unstyled step-list mb-0">
                        <li>
                            <h5 class="fs-5 fw-semibold mb-0">Unlimited Hiring</h5>
                            <p class="fs-6 fw-semibold text-theme">Hire as many permanent drivers as your business needs</p>
                        </li>
                        <li>
                            <h5 class="fs-5 fw-semibold mb-0">Verified & Experienced</h5>
                            <p class="fs-6 fw-semibold text-theme">All drivers are background-checked, license-verified</p>
                        </li>
                        <li>
                            <h5 class="fs-5 fw-semibold mb-0">Industry-Specific Drivers</h5>
                            <p class="fs-6 fw-semibold text-theme">Whether you need drivers for trucks, buses, delivery vans
                                - we’ve got you covered.</p>
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
    </section> --}}

    <!-- App Section -->
    <!--<section class="section_bg" style="background-image: url('assets/images/landing/Background.png');">-->
    <!--    <div class="container">-->
    <!--        <div class="row align-items-center g-4">-->
    <!--            <div class="col-md-4 text-center m-0">-->
    <!--                <img src="{{ asset('assets/images/landing/iPhone 13 Pro.png') }}" class="img-fluid phone-img"-->
    <!--                    alt="" style="width: 450px;">-->
    <!--            </div>-->
    <!--            <div class="col-md-5 me-auto">-->
    <!--                <h2>Download the Driver Deck App</h2>-->
    <!--                <p class="fw-bold fs-6 mt-4">Driver Deck is available on both Android and iOS devices. Simply visit the-->
    <!--                    Google Play Store or Apple App Store and search for Driver Deck.</p>-->

    <!--                <div class="d-grid align-items-center justify-content-between">-->
    <!--                    <img src="" alt="platstore_banner" class="img-fluid">-->
    <!--                    <img src="" alt="platstore_banner" class="img-fluid">-->
    <!--                </div>-->
    <!--            </div>-->
    <!--            <div class="col-md-3 text-center">-->
    <!--                <img src="{{ asset('assets/images/landing/qr.png') }}" alt="QR Code" class="img-fluid">-->
    <!--                <h5 class="mt-2 mb-4">Scan QR to download</h5>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</section>-->
@endsection