@extends('landing.layouts.app')
@section('title', 'Contact Us')
@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="position-relative overflow-hidden d-flex align-items-center banner-sec3 ">
            <div class="container-fluid ">
                <div class="row align-items-center">
                    <!-- Text Content -->
                    <div class="col-md-6 d-flex flex-column justify-content-center px-3 px-md-0 text-content3">
                        <h1 class="display-5 fw-semibold" style="line-height: 1.2;">
                            Contact Us
                        </h1>
                        <p class="text-dark fw-normal fs-5 mb-2">we offer driving jobs that fit your lifestyle.</p>
                    </div>

                    <!-- Image Content -->
                    <div class="col-md-6 p-0 position-relative" style="overflow: hidden;">
                        <!-- White fade overlay -->
                        <div class="white-fade-left"></div>
                        <img src="{{ asset('assets/images/landing/image 95.png') }}" class="img-fluid w-100 landing-img3"
                            alt="Hero Trucks">
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
                        <h3 class="display-5 fw-semibold mb-1">
                            Get in touch with us. We're here to assist you.
                        </h3>

                        <form action="" class="my-3 my-lg-5">
                            <div class="row gx-3 gy-4">
                                <div class="col-lg-4 col-sm-12">
                                    <label class="form-label">First name</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="col-lg-4 col-sm-12">
                                    <label class="form-label">Email Address</label>
                                    <input type="mail" class="form-control">
                                </div>
                                <div class="col-lg-4 col-sm-12">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" minlength="10" maxlength="10">
                                </div>

                                <div class="col-lg-12 col-sm-12">
                                    <label class="form-label">Message</label>
                                    <textarea class="form-control" name="" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-3">
                                    <button class="btn btn-theme theme-bg btn-secondary py-2 fw-semibold rounded-0">
                                        Leave us a Message<i class="ms-2 fa fa-chevron-right"></i>
                                        </buttom>
                                </div>
                        </form>
                    </article>
                </div>
            </div>
        </div>
    </section>


@endsection