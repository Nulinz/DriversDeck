<footer class="footer">
				<div class="container-fluid">
					<div class="row text-muted">
						<div class="col-md-12 text-center">
							<p class="mb-0">
								<a href="https://adminkit.io/" target="_blank" class="text-muted"><strong>Drivers Deck</strong></a>&copy;
								<script>
									document.write(new Date().getFullYear());
								</script>

							</p>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>

	<script src="{{ asset('assets/js/app.js') }}"></script>
	<script src="{{ asset('assets/js/datatables.js') }}"></script>
	{{-- <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script> --}}
@php
    use Illuminate\Support\Facades\DB;

    $corporateId = auth('corporate')->id();
    $subscription = DB::table('subscription')
        ->where('f_id', $corporateId)
        ->orderByDesc('id')
        ->first();

    $expiry = $subscription->exp_date ?? null;

    \Illuminate\Support\Facades\Log::info('Corporate subscription expiry check', [
        'corporate_id' => $corporateId,
        'expiry_date' => $expiry,
    ]);
@endphp

<!-- <script>
    var subscriptionExpiry = @json($expiry);

    if (!subscriptionExpiry) {
        // No subscription → treat as expired
        showExpiredPopup();
    } else {
        var expDate = new Date(subscriptionExpiry.replace(" ", "T"));
        var today = new Date();

        if (today > expDate) {
            showExpiredPopup();
        }
    }

    function showExpiredPopup() {
        var modal = document.createElement('div');
        modal.style.position = 'fixed';
        modal.style.top = '0';
        modal.style.left = '0';
        modal.style.width = '100%';
        modal.style.height = '100%';
        modal.style.backgroundColor = 'rgba(0,0,0,0.7)';
        modal.style.display = 'flex';
        modal.style.justifyContent = 'center';
        modal.style.alignItems = 'center';
        modal.style.zIndex = '9999';

        var box = document.createElement('div');
        box.style.background = '#fff';
        box.style.padding = '40px';
        box.style.borderRadius = '8px';
        box.style.textAlign = 'center';
        box.style.maxWidth = '400px';
        box.style.width = '90%';
        box.innerHTML = `
            <h3 style="color:#e74c3c;">Subscription Expired</h3>
            <p>Your subscription has ended. Please renew to continue using Drivers Deck.</p>
            <a href="{{ route('auth.register_subscription') }}"
               style="display:inline-block; margin-top:20px; background:#007bff; color:#fff;
                      padding:10px 20px; border-radius:5px; text-decoration:none; font-weight:bold;">
               Renew Subscription
            </a>
        `;

        modal.appendChild(box);
        document.body.appendChild(modal);

        // prevent background scrolling
        document.body.style.overflow = 'hidden';
    }
</script> -->

</body>
</html>