<footer class="footer">
	<div class="container-fluid">
		<div class="row text-muted">
			<div class="col-md-12 text-center">
				<p class="mb-0">
					<a class="text-muted"><strong>Drivers Deck</strong></a>&copy;
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
{{--
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script> --}}

<script>
	(function () {
		function forceRemoveBackdrop() {
			document.body.classList.remove('modal-open');
			document.body.style.removeProperty('padding-right');

			document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
			document.querySelectorAll('.modal.show').forEach(m => m.classList.remove('show'));
		}

		// Run immediately
		forceRemoveBackdrop();

		// Run after Bootstrap JS finishes
		setTimeout(forceRemoveBackdrop, 100);
		setTimeout(forceRemoveBackdrop, 300);
		setTimeout(forceRemoveBackdrop, 600);

		// Run on history navigation
		window.addEventListener('pageshow', forceRemoveBackdrop);
	})();
</script>

</body>

</html>