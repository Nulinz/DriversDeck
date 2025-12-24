@extends('landing.layouts.app')
@section('title', 'Refund')
@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="row align-items-center theme-bg term-pad">
            <div class="mb-lg-5 mb-0 text-center text-white">
                <h2 class="fs-2 fw-normal">Refund Policy</h2>
                <h3 class="fs-4 fw-normal">Last updated 31/05/2025</h3>
            </div>
        </div>
    </section>

    <!-- about Section -->
    <section class="container">
        <div class="row align-items-center justify-content-between" style="margin-top: -70px">
            <div class="card rounded-4">
                <div class="card-body">
                    <h3 class="fw-semibold fs-5 mb-2 mt-3">Drivers Deck offers a platform for users to connect directly with
                        drivers. Hence:
                    </h3>

                    <ul class="list-unstyled mb-0 pt-3">
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">1. No Platform Charges: We do not charge users any
                                service or booking fees.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">2. Direct Payment: Payments are made directly to the
                                driver in cash or through mutually agreed payment methods.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">3. No Refund Liability: Since Drivers Deck does not
                                collect payments, we are not liable for any refund claims.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">4. Disputes: Any payment-related disputes must be
                                resolved between the user and the driver.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">4. Caution: Confirm charges and mode of payment with
                                the driver before the trip begins to avoid confusion.</p>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
    </section>

@endsection