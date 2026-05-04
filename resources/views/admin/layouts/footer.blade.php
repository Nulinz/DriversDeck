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

@if (session('success'))
    <div class="toast-container position-fixed end-0 top-0 p-3">
        <div id="successToast" class="toast align-items-center text-bg-success border-0 text-white" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white m-auto me-2" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
@endif

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let toastEl = document.getElementById('successToast');
        if (toastEl) {
            let toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    });
</script>

<script>
    (function() {
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
