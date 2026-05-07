        </div> <!-- End container -->
    </div> <!-- End main-content -->

    <footer class="footer mt-auto py-4 border-top">
        <div class="container-fluid px-5">
            <div class="row align-items-center">
                <div class="col-md-4 text-center text-md-start">
                    <span class="text-muted small">
                        &copy; <?php echo date('Y'); ?> <strong><?php echo $_SESSION['branch_name'] ?? APP_NAME; ?></strong>. All rights reserved.
                    </span>
                </div>
                <div class="col-md-4 text-center">
                    <span class="text-muted small">
                        Powered by <a href="https://sandslab.com" target="_blank" class="text-primary text-decoration-none fw-bold">SaNDS Lab</a>
                    </span>
                </div>
                <div class="col-md-4 text-center text-md-end">
                    <span class="text-muted small">
                        <span class="badge bg-light text-dark border rounded-pill px-3">v2.1.0-STABLE</span>
                        <a href="#" class="ms-3 text-muted text-decoration-none" data-bs-toggle="modal" data-bs-target="#supportModal">
                            <i class="fas fa-headset me-1"></i> Support
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Support Modal -->
    <div class="modal fade" id="supportModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fw-bold"><i class="fas fa-headset me-2"></i> SaNDS Lab Support</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary border-bottom pb-2 uppercase small"><i class="fas fa-globe-africa me-2"></i> Middle East Office</h6>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fab fa-whatsapp text-success me-3 fs-5"></i>
                            <a href="https://wa.me/97335078079" target="_blank" class="text-dark text-decoration-none">+973 35 078 079</a>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-phone-alt text-muted me-3"></i>
                            <a href="tel:+97366600834" class="text-dark text-decoration-none">+973 6660 0834</a>
                        </div>
                    </div>

                    <div>
                        <h6 class="fw-bold text-primary border-bottom pb-2 uppercase small"><i class="fas fa-globe-asia me-2"></i> Asia / India Office</h6>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-phone-alt text-muted me-3"></i>
                            <a href="tel:+919895765626" class="text-dark text-decoration-none">+91 98957 65626</a>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fab fa-whatsapp text-success me-3 fs-5"></i>
                            <a href="https://wa.me/9180755011211" target="_blank" class="text-dark text-decoration-none">+91 807 550 11211</a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                    <a href="mailto:support@sandslab.com" class="btn btn-primary rounded-pill px-4">Email Support</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <!-- Custom JS -->
    <script>
        $(document).ready(function() {
            // Global AJAX setup if needed
        });
    </script>
</body>
</html>
