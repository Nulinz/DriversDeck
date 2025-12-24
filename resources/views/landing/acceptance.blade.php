@extends('landing.layouts.app')
@section('title', 'Terms')
@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="row align-items-center theme-bg term-pad">
            <div class="text-center text-white mb-0 mb-lg-5">
                <h2 class="fs-2 fw-normal">Acceptance of Terms</h2>
                <h3 class="fs-4 fw-normal">Last updated 31/05/2025</h3>
            </div>
        </div>
    </section>

    <!-- about Section -->
    <section class="container">
        <div class="row align-items-center justify-content-between" style="margin-top: -70px">
            <div class="card rounded-4">
                <div class="card-body">
                    <h3 class="fw-semibold fs-5 mt-3 mb-2">By using the Drivers Deck service via Call, Website, or App,
                        users agree to be legally bound by these terms and conditions.</h3>

                    <ul class="list-unstyled pt-3 mb-0">
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">1. User Data: Personal and vehicle information
                                shared during registration may be used for service delivery, and Drivers Deck ensures data
                                privacy with third-party service providers.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">2. Driver Conduct & Delays: Drivers aim to be
                                punctual, but delays are not compensable unless purposeful. Users must not encourage rash
                                driving.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">3. Dispute Resolution: Any issues with drivers
                                should be reported to the office immediately, rather than confronting the driver.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">4. Trip Cancellations: Cancellations must be made at
                                least 5 hours before the trip; otherwise, the driver’s full payment is due.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">5. Driver Waiting Time: Drivers will wait a maximum
                                of 15 minutes before starting the trip and billing time.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">6. Service Liability: Drivers Deck is not liable for
                                driver behaviour, vehicle-related accidents, malfunctions, or losses.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">7. Vehicle Responsibility: Users must ensure their
                                vehicle is legal, insured, and in good working condition; all liabilities related to the
                                vehicle rest with the user.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">8. Termination Rights: Drivers Deck may deny service
                                or terminate accounts due to misuse, technical issues, or legal reasons without prior
                                notice.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">9. Payment: Users pay drivers directly (cash or
                                supported methods); Drivers Deck charges no fees to users and is not liable for refunds.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">10. Trip Payments: Confirm payment methods and any
                                extra charges with the driver before starting your trip.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">11. Payment responsibility: Drivers Deck isn't
                                responsible for payment disputes or refunds between users and drivers.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">12. Direct Driver Contact: Contacting drivers
                                directly is at your own risk; Drivers Deck won't be liable for any issues.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">13. Test Drives: Don't take test drives during
                                booked trips. If you want driving lessons, contact our driving learning service.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">14. Driver's Role: Don't ask drivers to do
                                non-driving tasks like handling cash or personal work. Drivers Deck won't be responsible for
                                any issues arising from such requests.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">15. Driver Duty Limits: Outstation drivers can work
                                a maximum of 12 hours. Users must ensure rest or pay extra charges as per the driver’s
                                profile.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">16. Behaviour Guidelines: Misuse of driver services,
                                intoxicated behaviour, harassment, or illegal activities can lead to immediate cancellation
                                of service.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">17. Protect Your Belongings: Don't let the driver
                                use your personal belongings, such as your phone, pen drive, wallet, perfume, or any other
                                items</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">18. Valuables and Loss: Drivers Deck and its drivers
                                are not responsible for lost or damaged belongings or for theft during service.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">19. User Responsibilities: Required documents (RC,
                                PUC, insurance) must be in the car. Users must assist with navigation during outstation
                                trips if needed.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">20. Drivers or MY CHOICE ENTERPRISES reserve the
                                right to terminate or cancel a service if a user violates laws, misbehaves with the driver
                                or others, or causes a nuisance. This also applies if the user damages the driver's devices,
                                asks the driver to break traffic rules, or if there's a discrepancy in the vehicle or
                                insurance details. The driver can refuse service or discontinue it if their safety or
                                others' is endangered due to the user's actions.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">21. Jurisdiction: All legal matters will fall under
                                the jurisdiction of the courts in Tamil Nadu, India.</p>
                        </li>
                    </ul>
                    {{-- tami --}}


                    <h3 class="fw-semibold fs-5 lh-basic mt-3 mb-2">விதிமுறைகளை ஏற்றுக்கொள்வது: அழைப்பு, வலைத்தளம் அல்லது
                        செயலி வழியாக டிரைவர்ஸ் டெக் சேவையைப் பயன்படுத்துவதன் மூலம், பயனர்கள் இந்த விதிமுறைகள் மற்றும்
                        நிபந்தனைகளுக்கு சட்டப்பூர்வமாகக் கட்டுப்படுவதை ஒப்புக்கொள்கிறார்கள்.</h3>
                    <ul class="list-unstyled pt-3 mb-0">
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">1. பயனர் தரவு: பதிவின் போது பகிரப்படும் தனிப்பட்ட
                                மற்றும் வாகனத் தகவல்கள் சேவை வழங்கலுக்குப் பயன்படுத்தப்படலாம், மேலும் டிரைவர்ஸ் டெக்
                                மூன்றாம் தரப்பு சேவை வழங்குநர்களுடன் தரவு தனியுரிமையை உறுதி செய்கிறது.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">2. டிரைவர் நடத்தை மற்றும் தாமதங்கள்: டிரைவர்கள்
                                சரியான நேரத்தில் இருக்க வேண்டும், ஆனால் தாமதங்கள் வேண்டுமென்றே செய்யப்படாவிட்டால்
                                ஈடுசெய்யப்படாது. பயனர்கள் அவசரமாக வாகனம் ஓட்டுவதை ஊக்குவிக்கக்கூடாது.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">3. சர்ச்சை தீர்வு: டிரைவர்களுடனான ஏதேனும் சிக்கல்கள்
                                இருந்தால், டிரைவரை எதிர்கொள்வதற்குப் பதிலாக உடனடியாக அலுவலகத்தில் தெரிவிக்கப்பட வேண்டும்.
                            </p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">4. பயண ரத்துசெய்தல்கள்: பயணத்திற்கு குறைந்தது 5 மணி
                                நேரத்திற்கு முன்பே ரத்து செய்யப்பட வேண்டும்; இல்லையெனில், டிரைவரின் முழு கட்டணமும்
                                செலுத்தப்பட வேண்டும</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">5. டிரைவர் காத்திருப்பு நேரம்: பயணம் தொடங்குவதற்கு
                                முன் ஓட்டுநர்கள் அதிகபட்சமாக 15 நிமிடங்கள் காத்திருப்பார்கள் மற்றும் பில்லிங் நேரம்.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">6. சேவை பொறுப்பு: டிரைவர்ஸ் டெக் ஓட்டுநர் நடத்தை,
                                வாகனம் தொடர்பான விபத்துக்கள், செயலிழப்புகள் அல்லது இழப்புகளுக்கு பொறுப்பல்ல.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">7. வாகனப் பொறுப்பு: பயனர்கள் தங்கள் வாகனம்
                                சட்டப்பூர்வமாகவும், காப்பீடு செய்யப்பட்டதாகவும், நல்ல செயல்பாட்டு நிலையில் உள்ளதா என்பதையும்
                                உறுதி செய்ய வேண்டும்; வாகனம் தொடர்பான அனைத்து பொறுப்புகளும் பயனரைச் சார்ந்தது.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">8. பணிநீக்க உரிமைகள்: தவறான பயன்பாடு, தொழில்நுட்ப
                                சிக்கல்கள் அல்லது சட்டப்பூர்வ காரணங்களால் முன் அறிவிப்பு இல்லாமல் டிரைவர்ஸ் டெக் சேவையை
                                மறுக்கலாம் அல்லது கணக்குகளை நிறுத்தலாம்.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">9. கட்டணம்: பயனர்கள் ஓட்டுநர்களுக்கு நேரடியாக பணம்
                                செலுத்துகிறார்கள் (ரொக்கம் அல்லது ஆதரிக்கப்படும் முறைகள்); டிரைவர்ஸ் டெக் பயனர்களுக்கு எந்த
                                கட்டணமும் வசூலிக்காது மற்றும் பணத்தைத் திரும்பப் பெறுவதற்கு பொறுப்பல்ல.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">10. பயணக் கொடுப்பனவுகள்: உங்கள் பயணத்தைத்
                                தொடங்குவதற்கு முன் கட்டண முறைகள் மற்றும் ஏதேனும் கூடுதல் கட்டணங்களை ஓட்டுநரிடம்
                                உறுதிப்படுத்தவும்.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">11. கட்டணப் பொறுப்பு: பயனர்களுக்கும்
                                ஓட்டுநர்களுக்கும் இடையிலான கட்டண தகராறுகள் அல்லது பணத்தைத் திரும்பப் பெறுவதற்கு டிரைவர்ஸ்
                                டெக் பொறுப்பல்ல.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">12. நேரடி ஓட்டுநர் தொடர்பு: ஓட்டுநர்களை நேரடியாகத்
                                தொடர்புகொள்வது உங்கள் சொந்த முடிவு ஆகும்; டிரைவர்ஸ் டெக் எந்தப் பிரச்சினைகளுக்கும்
                                பொறுப்பேற்காது.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">13. டெஸ்ட் டிரைவ்கள்: முன்பதிவு செய்யப்பட்ட
                                பயணங்களின் போது டெஸ்ட் டிரைவ்களை எடுக்க வேண்டாம். ஓட்டுநர் பாடங்களை நீங்கள் விரும்பினால்,
                                எங்கள் ஓட்டுநர் கற்றல் சேவையைத் தொடர்பு கொள்ளவும்.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">14. ஓட்டுநரின் பங்கு: பணம் அல்லது தனிப்பட்ட
                                வேலைகளைக் கையாள்வது போன்ற ஓட்டுநர் அல்லாத பணிகளைச் செய்ய ஓட்டுநர்களைக் கேட்க வேண்டாம்.
                                அத்தகைய கோரிக்கைகளால் எழும் எந்தவொரு பிரச்சினைகளுக்கும் டிரைவர்ஸ் டெக் பொறுப்பேற்காது.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">15. ஓட்டுநர் கடமை வரம்புகள்: வெளியூர் ஓட்டுநர்கள்
                                அதிகபட்சமாக 12 மணிநேரம் வேலை செய்யலாம். ஓட்டுநர் சுயவிவரத்தின்படி பயனர்கள் ஓய்வை உறுதி செய்ய
                                வேண்டும் அல்லது கூடுதல் கட்டணங்களைச் செலுத்த வேண்டும்.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">16. நடத்தை வழிகாட்டுதல்கள்: ஓட்டுநர் சேவைகளை தவறாகப்
                                பயன்படுத்துதல், குடிபோதையில் நடத்தை, துன்புறுத்தல் அல்லது சட்டவிரோத நடவடிக்கைகள் சேவையை
                                உடனடியாக ரத்து செய்ய வழிவகுக்கும்.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">17. உங்கள் உடைமைகளைப் பாதுகாக்கவும்: உங்கள்
                                தொலைபேசி, பென் டிரைவ், பணப்பை, வாசனை திரவியம் அல்லது வேறு ஏதேனும் பொருட்கள் போன்ற உங்கள்
                                தனிப்பட்ட உடைமைகளை ஓட்டுநர் பயன்படுத்த அனுமதிக்காதீர்கள்.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">18. மதிப்புமிக்க பொருட்கள் மற்றும் இழப்பு: தொலைந்து
                                போன அல்லது சேதமடைந்த பொருட்கள் அல்லது சேவையின் போது திருட்டுக்கு டிரைவர்ஸ் டெக் மற்றும் அதன்
                                ஓட்டுநர்கள் பொறுப்பல்ல.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">19. பயனர் பொறுப்புகள்: தேவையான ஆவணங்கள் (RC, PUC,
                                காப்பீடு) காரில் இருக்க வேண்டும். தேவைப்பட்டால், வெளியூர் பயணங்களின் போது பயனர்கள்
                                வழிசெலுத்தலுக்கு உதவ வேண்டும்.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">20. ஒரு பயனர் சட்டங்களை மீறினால், ஓட்டுநர் அல்லது
                                மற்றவர்களுடன் தவறாக நடந்து கொண்டால், அல்லது தொந்தரவு ஏற்படுத்தினால், சேவையை நிறுத்த அல்லது
                                ரத்து செய்ய ஓட்டுநர்கள் அல்லது MY CHOICE ENTERPRISES உரிமையை கொண்டுள்ளது. பயனர் ஓட்டுநரின்
                                சாதனங்களை சேதப்படுத்தினால், போக்குவரத்து விதிகளை மீறுமாறு ஓட்டுநரிடம் கேட்டால், அல்லது
                                வாகனம் அல்லது காப்பீட்டு விவரங்களில் முரண்பாடு இருந்தால் இது பொருந்தும். பயனரின் செயல்களால்
                                அவர்களின் பாதுகாப்பு அல்லது மற்றவர்களின் பாதுகாப்பு ஆபத்தில் இருந்தால் ஓட்டுநர் சேவையை
                                மறுக்கலாம் அல்லது நிறுத்தலாம்.</p>
                        </li>
                        <li>
                            <p class="fs-6 fw-semibold text-muted mb-3">21. அதிகார வரம்பு: அனைத்து சட்ட விஷயங்களும்
                                இந்தியாவின் தமிழ்நாட்டில் உள்ள நீதிமன்றங்களின் அதிகார வரம்பிற்குள் வரும்..</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

@endsection