@extends('landing.layouts.app')
@section('title', 'Welcome to Drivers Deck')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">
    <style>
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
    height: 120px;
    overflow: hidden;
    padding: 20px 0;
}

.logo-track {
    display: flex;
    align-items: center;
    animation: scroll-left 30s linear infinite;
    width: max-content;
}

.logo-item {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 40px;
    min-width: 180px;
}

.logo-item img {
    height: 70px;
    width: auto;
    max-width: 180px;
    object-fit: contain;
    filter: grayscale(100%);
    transition: all 0.3s ease;
    opacity: 0.7;
}

.logo-item img:hover {
    filter: grayscale(0%);
    transform: scale(1.1);
    opacity: 1;
}

/* Pause animation on hover */
.logo-slider:hover .logo-track {
    animation-play-state: paused;
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
    <!-- Hero Section -->
    <section class="hero">
        <div class="position-relative d-flex align-items-center banner-sec overflow-hidden">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <!-- Text Content -->
                    <div class="col-md-6 d-flex flex-column justify-content-center px-md-0 text-content px-3"
                        data-aos="fade-up">
                        <p class="text-primary fw-normal fs-4 mb-2">Shift Gears With Ease</p>
                        <h1 class="display-3 fw-semibold" style="line-height: 1.2;">
                            Hire Or Get Hired As<br>A Car Or Truck Driver<br>Today!
                        </h1>
                    </div>

                    <!-- Image Content -->
                    <div class="col-md-6 position-relative p-0" style="overflow: hidden;">
                        <div class="white-fade-left"></div>
                        <img src="{{ asset('assets/images/landing/image 99.png') }}" class="img-fluid w-100 landing-img"
                            alt="Hero Trucks">
                    </div>
                </div>

                <!-- Rotated Text -->
                <h2 class="position-absolute d-none d-md-block landing-text-rotate">
                    Drive With Us
                </h2>
            </div>
        </div>
    </section>

    <!-- about Section -->
    {{-- <section class="section-pad features" data-aos="fade-right">
        <div class="container"> --}}
            {{-- <div class="row align-items-center justify-content-between gx-sm-2 gy-sm-3">
                <div class="col-lg-7 col-sm-12 mb-lg-0 mb-4">
                    <img src="{{ asset('assets/images/landing/image 96.png') }}" class="d-block mx-lg-auto img-fluid"
                        alt="Bootstrap Themes" width="700" height="500" loading="lazy">
                </div>
                <div class="col-lg-5 col-sm-12">
                    <h2 class="display-5 fw-semibold">Need a driver?</h2>
                    <h1 class="display-6 fw-bold text-body-emphasis fw-semibold mb-3">We’ve got you covered <br> -Anytime,
                        Anywhere!</h1>
                    <p class="info-1 text-theme fw-normal lh-basic py-3 text-justify">We connect skilled drivers with the
                        right opportunities. Whether full-time or part-time, our
                        platform supports flexible, rewarding jobs for drivers and provides reliable talent for businesses.
                    </p>
                    <a href="#" class="btn btn-theme theme-bg btn-secondary fw-semibold rounded-2-0 py-2">Read More<i
                            class="fa fa-chevron-right ms-2"></i></a>
                </div>
            </div> --}}
            {{-- <div class="container py-5">

                <h3 class="mb-4 text-center">Connecting Drivers with the Right Jobs, Seamlessly.</h3>

                <!-- Tabs -->
                <div class="d-flex justify-content-center tab-btns">
                    <button class="btn btn-outline-primary me-2" data-bs-toggle="tab" data-bs-target="#ownersTab">For
                        Owners</button>
                    <button class="btn btn-outline-success" data-bs-toggle="tab" data-bs-target="#driversTab">For
                        Drivers</button>
                </div>

                <!-- Tab Content -->
                <div class="tab-content">

                    <!-- For Owners -->
                    <div class="tab-pane fade show active" id="ownersTab">
                        <div id="ownersCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#ownersCarousel" data-bs-slide-to="0"
                                    class="active"></button>
                                <button type="button" data-bs-target="#ownersCarousel" data-bs-slide-to="1"></button>
                                <button type="button" data-bs-target="#ownersCarousel" data-bs-slide-to="2"></button>
                                <button type="button" data-bs-target="#ownersCarousel" data-bs-slide-to="3"></button>
                            </div>
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="{{ asset('') }}" class="d-block w-100" alt="Owner 1">
                                </div>
                                <div class="carousel-item">
                                    <img src="owner2.jpg" class="d-block w-100" alt="Owner 2">
                                </div>
                                <div class="carousel-item">
                                    <img src="owner3.jpg" class="d-block w-100" alt="Owner 3">
                                </div>
                                <div class="carousel-item">
                                    <img src="owner4.jpg" class="d-block w-100" alt="Owner 4">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#ownersCarousel"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#ownersCarousel"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>
                    </div>

                    <!-- For Drivers -->
                    <div class="tab-pane fade" id="driversTab">
                        <div id="driversCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#driversCarousel" data-bs-slide-to="0"
                                    class="active"></button>
                                <button type="button" data-bs-target="#driversCarousel" data-bs-slide-to="1"></button>
                                <button type="button" data-bs-target="#driversCarousel" data-bs-slide-to="2"></button>
                                <button type="button" data-bs-target="#driversCarousel" data-bs-slide-to="3"></button>
                            </div>
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="driver1.jpg" class="d-block w-100" alt="Driver 1">
                                </div>
                                <div class="carousel-item">
                                    <img src="driver2.jpg" class="d-block w-100" alt="Driver 2">
                                </div>
                                <div class="carousel-item">
                                    <img src="driver3.jpg" class="d-block w-100" alt="Driver 3">
                                </div>
                                <div class="carousel-item">
                                    <img src="driver4.jpg" class="d-block w-100" alt="Driver 4">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#driversCarousel"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#driversCarousel"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>
                    </div>

                </div>
            </div> --}}
            {{-- </div>
    </section> --}}
    {{-- news section --}}
<section class="section-pad">
    <div class="bootstrap snippets bootdey container">
        <div class="row mb-4">
            <h2 class="display-6 fw-semibold mb-0" style="color: #2E343F">Trending Topics</h2>
        </div>
        <div class="row" style="max-height:400px;">
            <!-- posts -->
            <div class="col-md-12">
                <div class="panel blog-container">
                    <div class="panel-body">
                        @if($vacancies->count() > 0)
                            <div id="topicscarousel" class="splide" aria-label="Beautiful Images">
                                <div class="splide__track">
                                    <ul class="splide__list">
                                        @foreach($vacancies as $vacancy)
                                            <li class="splide__slide">
                                                <h4 class="mt-3">{{ $vacancy->location }}</h4>
                                                <small class="text-muted">
                                                    Posted on {{ \Carbon\Carbon::parse($vacancy->created_at)->format('M d, Y') }}
                                                </small>

                                                <p class="m-top-sm m-bottom-sm">
                                                    {{ Str::limit($vacancy->description, 200) }}
                                                </p>

                                                @if($vacancy->contact_number)
                                                    <p class="text-muted">
                                                        <i class="fa fa-phone"></i> Contact: {{ $vacancy->contact_number }}
                                                    </p>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <p class="text-muted">No active vacancies available at the moment.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section-pad features" data-aos="fade-up">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <!--<div class="four col-md-3">-->
            <!--    <div class="counter-box counter-box3"> -->
            <!--        <i class="fa-solid fa-face-smile-beam"></i> -->
            <!--        <span class="counter">{{ $happyCustomers }}</span>-->
            <!--        <p>Happy Customers</p>-->
            <!--    </div>-->
            <!--</div>-->
            <div class="four col-md-3">
                <div class="counter-box counter-box1"> 
                    <i class="fa-solid fa-users"></i> 
                    <span class="counter">{{ $totalDrivers }}</span>
                    <p>Total Registered Drivers</p>
                </div>
            </div>
            <div class="four col-md-3">
                <div class="counter-box counter-box2"> 
                    <i class="fa-solid fa-handshake"></i> 
                    <span class="counter">{{ $totalCorporates }}</span>
                    <p>Total Corporates</p>
                </div>
            </div>
            <div class="four col-md-3">
                <div class="counter-box counter-box4"> 
                    <i class="fa-solid fa-building"></i> 
                    <span class="counter">{{ $totalOwners }}</span>
                    <p>Total Owners</p>
                </div>
            </div>
        </div>
    </div>
</section>

    {{-- client --}}
{{-- client --}}
<section class="bg-light py-5" data-aos="fade-up">
    <div class="container text-center">
        <h3 class="fw-semibold mb-4" style="font-size: 2rem;">Trusted By</h3>
        
        @if(isset($trustedCompanies) && $trustedCompanies->count() > 0)
            <div class="logo-slider position-relative my-4">
                <div class="logo-track d-flex align-items-center">
                    @if($trustedCompanies->count() >= 3)
                        <!-- Display logos twice for seamless infinite scroll (only if 3+ logos) -->
                        @foreach (range(1, 2) as $loop)
                            @foreach ($trustedCompanies as $company)
                                <div class="logo-item">
                                    <img src="{{ asset($company->logo) }}" 
                                        alt="{{ $company->name }}"
                                        title="{{ $company->name }}"
                                        onerror="this.parentElement.style.display='none'">
                                </div>
                            @endforeach
                        @endforeach
                    @else
                        <!-- Display logos only once if less than 3 -->
                        @foreach ($trustedCompanies as $company)
                            <div class="logo-item">
                                <img src="{{ asset($company->logo) }}" 
                                    alt="{{ $company->name }}"
                                    title="{{ $company->name }}"
                                    onerror="this.parentElement.style.display='none'">
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        @else
            <div class="py-4">
                <p class="text-muted mb-0">No partner companies to display yet.</p>
            </div>
        @endif
    </div>
</section>

    <!-- CTA Section -->
    <section class="section-pad cta pt-0" data-aos="fade-up">
        <div class="bg-dark d-flex align-items-center justify-content-center py-5" style="min-height: 70vh;">
            <div class="container">
                <div class="row align-items-center gy-5">
                    <div class="col-12 col-md-4 mb-lg-4 text-md-start mb-2 text-center">
                        <h2 class="fw-bold mb-3 text-white">
                            Sit back and relax— <br class="d-none d-md-block"> Drivers Deck takes the wheel.
                        </h2>
                        <p class="fw-nromal lh-md fs-5 feature text-theme">Get verified acting drivers, real-time tracking,
                            exclusive refer-and-earn rewards, and dedicated support
                            for
                            vehicle owners.</p>
                    </div>
                    <div class="col-12 col-md-8 m-0">
                        <div class="row align-items-center">
                            <div class="col-12 col-md-4 p-0" data-aos="fade-up" data-aos-delay="100">
                                <div
                                    class="side-card bg-card d-flex flex-column justify-content-center align-items-start px-3 py-4 text-white">
                                    <h5 class="fw-bold mb-3">Verified Acting Drivers</h5>
                                    <p class="fs-6 feature-text mb-0 text-start">Hire trusted and professionally verified
                                        acting drivers for your short-term or one-time driving
                                        needs.
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 p-0" data-aos="fade-up" data-aos-delay="200">
                                <div
                                    class="middle-card bg-warning d-flex flex-column justify-content-center align-items-start my-2 px-3 py-4 text-white">
                                    <h5 class="fw-bold mb-3">Live Tracking for Your Safety</h5>
                                    <p class="feature-text mb-0 text-start">Track your driver in real-time and stay informed
                                        throughout the journey—your safety is our priority.</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 p-0" data-aos="fade-up" data-aos-delay="300">
                                <div
                                    class="side-card bg-card d-flex flex-column justify-content-center align-items-start px-3 py-4 text-white">
                                    <h5 class="fw-bold mb-3">Refer and Earn</h5>
                                    <p class="feature-text mb-0 text-start">Invite friends to use Drivers Deck and earn
                                        exciting rewards for every successful referral.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- App Section -->
    <!--<section class="section_bg" data-aos="zoom-in" style="background-image: url('assets/images/landing/Background.png');">-->
    <!--    <div class="container">-->
    <!--        <div class="row align-items-center g-4">-->
    <!--            <div class="col-md-4 text-center" style="margin-top: -135px">-->
    <!--                <img src="{{ asset('assets/images/landing/Nothing Phone 1.png') }}" class="img-fluid phone-img" alt="">-->
    <!--            </div>-->
    <!--            <div class="col-md-5 me-auto">-->
    <!--                <h2>Download the Driver Deck App</h2>-->
    <!--                <p class="fw-bold fs-6 mt-4">Driver Deck is available on both Android and iOS devices. Simply visit the Google Play Store or Apple App Store and search for-->
    <!--                    Driver-->
    <!--                    Deck.</p>-->

    <!--                <div class="d-flex align-items-center justify-content-between">-->
    <!--                    <a href=""><img src="{{ asset('assets/images/landing/app store.png') }}" alt="platstore_banner" width="250px" class="img-fluid"></a>-->
    <!--                    <a href=""><img src="{{ asset('assets/images/landing/play store.png') }}" alt="platstore_banner" width="250px" class="img-fluid"></a>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--            <div class="col-md-3 text-center">-->
    <!--                <img src="{{ asset('assets/images/landing/qr.png') }}" alt="QR Code" class="img-fluid">-->
    <!--                <h5 class="mb-4 mt-2">Scan QR to download</h5>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</section>-->

    <!-- AOS JS -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        AOS.init({
            duration: 1200, // Animation duration in milliseconds
            easing: 'ease-in-out', // Smooth easing function
            once: true, // Whether animation should happen only once
            mirror: true, // Animate out while scrolling past
            anchorPlacement: 'top-bottom' // Defines which position of the element triggers animation
        });
        // counter
        $(document).ready(function () {

            $('.counter').each(function () {
                $(this).prop('Counter', 0).animate({
                    Counter: $(this).text()
                }, {
                    duration: 4000,
                    easing: 'swing',
                    step: function (now) {
                        $(this).text(Math.ceil(now));
                    }
                });
            });

        });
        // slider
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new Splide('#topicscarousel', {
                type: 'fade',
                perPage: 1,
                rewind: true,
                autoplay: true,
                interval: 4000,
                arrows: false,
                pagination: false,
            }).mount();
        });
    </script>

@endsection