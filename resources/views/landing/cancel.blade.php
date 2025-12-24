@extends('landing.layouts.app')
@section('title', 'Cancel')
@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="row align-items-center theme-bg term-pad">
            <div class="mb-lg-5 mb-0 text-center text-white">
                <h2 class="fs-2 fw-normal">Cancellation Policy</h2>
                <h3 class="fs-4 fw-normal">Last updated 31/05/2025</h3>
            </div>
        </div>
    </section>

    <!-- about Section -->
    <section class="container">
        <div class="row align-items-center justify-content-between" style="margin-top: -70px">
            <div class="card rounded-4">
                <div class="card-body">
                    <h3 class="fw-semibold fs-5 mb-2 mt-3">To ensure fair use of our services and driver scheduling, the
                        following cancellation terms apply:</h3>

                    <ul class="list-unstyled mb-0 pt-3">
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">1. Advance Notice: Users must cancel their booking
                                at least 5 hours before the scheduled trip time.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">2.Late Cancellations: If a cancellation is made with
                                less than 5 hours’ notice, the driver’s full payment is due.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">3. Driver’s Waiting Time: A driver will wait for a
                                maximum of 15 minutes before beginning the trip and billing time.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">4. Right to Cancel: Drivers Deck or drivers may
                                cancel or refuse service if the user misbehaves, violates rules, or creates unsafe
                                situations.</p>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
    </section>

@endsection