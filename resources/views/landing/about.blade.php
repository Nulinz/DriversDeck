@extends('landing.layouts.app')
@section('title', 'About Us')
@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="position-relative d-flex align-items-center banner-sec3 overflow-hidden">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <!-- Text Content -->
                    <div class="col-md-6 d-flex flex-column justify-content-center px-md-0 text-content3 px-3">
                        <h1 class="display-5 fw-semibold" style="line-height: 1.2;">
                            About Driver Deck
                        </h1>
                        <p class="text-dark fs-5 mb-2">we offer driving jobs that fit your <br> lifestyle.</p>
                    </div>

                    <!-- Image Content -->
                    <div class="col-md-6 position-relative p-0" style="overflow: hidden;">
                        <!-- White fade overlay -->
                        <div class="white-fade-left"></div>
                        <img src="{{ asset('assets/images/landing/image 95.png') }}" class="img-fluid w-100 landing-img3" alt="Hero Trucks">
                    </div>
                </div>
            </div>
        </div>
    </section>
      <!-- about Section -->
    <section class="section-pad features">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <article class="blog-post">
                        <h3 class="mb-1">
                            Our Mission
                        </h3>
                        <p class="fs-6 fw-normal text-theme lh-lg pb-3">
                            Our Mission
                            Our mission is to place experienced and skilled drivers in the right roles where their talents
                            are valued and fairly rewarded. We are dedicated to advancing the careers of professional
                            drivers by creating meaningful job opportunities and promoting financial stability through
                            dignified employment.
                        </p>
                    </article>
                    <article class="blog-post">
                        <h3 class="mb-1">
                            Our Vision
                        </h3>
                        <p class="fs-6 fw-normal text-theme lh-lg pb-3">
                            Our vision is to build a trusted and dynamic platform where skilled drivers are recognized,
                            empowered, and seamlessly connected with organizations that uphold professionalism and
                            reliability. We strive to eliminate transportation delays by offering a vast network of
                            experienced drivers. Guided by a strong commitment to service, our policy is to support vehicle
                            owners in genuine need by providing them with highly qualified, dependable
                            driving professionals.
                        </p>

    <!-- What We Do Section -->
    <section class="section-pad about">
        <div class="container text-start">
            <h2 class="display-6 fw-semibold mb-0" style="color: #2E343F">Why Choose Us?</h2>
            <div class="row my-lg-3 g-3 my-0">
                <div class="col-md-4 d-flex">
                    <div class="bg-light text-dark w-100 rounded-2 border p-4">
                        <h5 class="fw-normal fs-work-title">Verified Drivers Only</h5>
                        <p class="text-theme fs-work mb-0">All drivers undergo document verification and background checks. We ensure only genuine and qualified drivers are listed on
                            our
                            platform. For Document verification (License, Aadhaar, etc.)</p>
                    </div>
                </div>
                <div class="col-md-4 d-flex">
                    <div class="bg-light text-dark w-100 rounded-2 border p-4">
                        <h5 class="fw-normal fs-work-title">Acting & Permanent Driver Support</h5>
                        <p class="text-theme fs-work mb-0">Acting drivers are available for hourly, daily, or short-term contracts. Permanent drivers are matched for long-term
                            employment
                            or fleet roles Our platform supports both job types with equal focus and efficiency.</p>
                    </div>
                </div>
                <div class="col-md-4 d-flex">
                    <div class="bg-light text-dark w-100 rounded-2 border p-4">
                        <h5 class="fw-normal fs-work-title">Driver Ratings & Performance History</h5>
                        <p class="text-theme fs-work mb-0">Every driver builds a profile with ratings, reviews, and job history. Drivers can showcase their track record and get repeat
                            work, Helps maintain quality, trust, and accountability on the platform.</p>
                    </div>
                </div>
                <div class="col-md-4 d-flex">
                    <div class="bg-light text-dark w-100 rounded-2 border p-4">
                        <h5 class="fw-normal fs-work-title">Flexible Platform – Mobile & Web Access</h5>
                        <p class="text-theme fs-work mb-0">Vehicle owners and drivers use our mobile app for on-the-go job posting and applications. Corporate clients use a dedicated
                            web
                            dashboard for bulk hiring and candidate tracking. You can manage everything — from profile setup to hiring.</p>
                    </div>
                </div>
                <div class="col-md-4 d-flex">
                    <div class="bg-light text-dark w-100 rounded-2 border p-4">
                        <h5 class="fw-normal fs-work-title">End-to-End Support for Drivers & Corporate </h5>
                        <p class="text-theme fs-work mb-0">Our customer support team helps you every step of the way. <br> Are you facing issues with job status? <br> We’ve got your
                            back.
                            We
                            ensure smooth
                            hiring, clear communication, and timely resolution. <br> Do you need help in posting a job or filtering candidates? <br> We’re here.</p>
                    </div>
                </div>

                <div class="col-md-4 d-flex">
                    <div class="bg-light text-dark w-100 rounded-2 border p-4">
                        <h5 class="fw-normal fs-work-title">Built for the Transport Ecosystem</h5>
                        <p class="text-theme fs-work mb-0">We are not a general job board. We are built specifically for the transport, logistics, and mobility sector, from single
                            vehicle
                            owners to corporate fleet managers. We understand your needs deeply and have built tools that truly work for the industry.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Who We Serve -->
    <section class="section-pad about">
        <div class="container text-start">
            <h2 class="display-6 fw-semibold mb-0" style="color: #2E343F">Who We Serve</h2>
            <div class="row my-lg-3 g-3 my-0">
                <div class="col-md-3 d-flex">
                    <div class="text-dark w-100 p-4">
                        <img src="{{ asset('assets/images/landing/image 3.png') }}" alt="drivers-deck-landing">
                        <h5 class="fw-normal fs-work-title mb-2 mt-3">Permanent Drivers</h5>
                        <p class="text-theme fs-work mb-0">Access full-time job opportunities from trusted employers. Secure positions with corporate fleets or private owners. All jobs
                            are screened for fair terms and reliability.</p>
                    </div>
                </div>
                <div class="col-md-3 d-flex">
                    <div class="text-dark w-100 p-4">
                        <img src="{{ asset('assets/images/landing/image 2.png') }}" alt="drivers-deck-landing">
                        <h5 class="fw-normal fs-work-title mb-2 mt-3">Acting Drivers</h5>
                        <p class="text-theme fs-work mb-0">Looking for flexible, temporary driving Jobs. View active job listings from vehicle owners & corporates. Accept jobs
                            on-demand via our mobile app.</p>
                    </div>
                </div>
                <div class="col-md-3 d-flex">
                    <div class="text-dark w-100 p-4">
                        <img src="{{ asset('assets/images/landing/image 4.png') }}" alt="drivers-deck-landing">
                        <h5 class="fw-normal fs-work-title mb-2 mt-3">Corporate Clients</h5>
                        <p class="text-theme fs-work mb-0">Post bulk or individual job listings via web dashboard, Manage applications and driver screening online, Access a pool of
                            verified, professional drivers.</p>
                    </div>
                </div>
                <div class="col-md-3 d-flex">
                    <div class="text-dark w-100 p-4">
                        <img src="{{ asset('assets/images/landing/image 1.png') }}" alt="drivers-deck-landing">
                        <h5 class="fw-normal fs-work-title mb-2 mt-3">Car Owners</h5>
                        <p class="text-theme fs-work mb-0">Hire Acting Drivers for urgent needs, All drivers are verified & background-checked, Get real-time driver availability</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

  
                    </article>
                    <!--<article class="blog-post">-->
                    <!--    <h3 class="mb-1">-->
                    <!--        Why Choose Us?-->
                    <!--    </h3>-->
                    <!--    <ul class="fs-6 fw-normal text-theme lh-lg list-style pb-3">-->
                    <!--        <li>We offer driving jobs that fit your lifestyle.</li>-->
                    <!--        <li>Our app is simple, smart, and built with drivers in mind.</li>-->
                    <!--        <li>Quick Delivery: Your time is valuable—get your groceries delivered fast and on time.</li>-->
                    <!--        <li>User-Friendly Experience: Shop easily through our app or website, designed to give you a-->
                    <!--            smooth and hassle-free experience.</li>-->
                    <!--        <li>Secure Payments: Multiple payment options with secure checkout.</li>-->
                    <!--    </ul>-->
                    <!--</article>-->

                </div>
            </div>
        </div>
    </section>

@endsection
