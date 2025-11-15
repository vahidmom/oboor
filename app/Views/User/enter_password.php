<?= $this->extend('User/layout/master_login') ?>

<style>
.toggle-password {
    cursor: pointer;
    color: #888;
    transition: color 0.2s ease;
}
.toggle-password:hover {
    color: #333;
}
.form-control {
    padding-right: 2.5rem !important;
}
</style>

<?= $this->section('content') ?>

<div class="fixed-modal-bg"></div>

<div class="modal-page shadow two-section">
    <div class="container-fluid">                
        <div class="row">
            <div class="card shadow overflow-hidden">
                <div class="row">

                    <!-- تصویر سمت راست -->
                    <div class="col-lg-5 col-md-6 position-relative bg-cover" style="background-image: url(<?= base_url('assets/images/other/mobile-auth.jpg') ?>);">
                        <div class="w-100 h-100 z-index-2 d-flex m-auto justify-content-center">
                            <div class="mask bg-gradient-dark"></div>
                            <div class="side-texts position-relative my-auto z-index-2">
                                <div class="d-flex p-2 text-white my-3 bold">
                                    <?= esc($login_message) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- فرم ورود با رمز -->
                    <div class="col-lg-7 col-md-6 py-5">
                        <form method="post" action="<?= site_url('user/check-password') ?>">
                            <?= csrf_field() ?>

                            <div class="card-header px-4 py-2 text-center">
                                <div class="logo-con mb-4"></div>
                            </div>

                            <div class="card-body pt-1">
                                <div class="alert alert-success fill text-center bold" id="mainAlert">
                                    لطفاً رمز عبور خود را وارد کنید
                                </div>

                                <?php if (session()->getFlashdata('error')): ?>
                                    <div class="alert alert-danger fill"><?= session()->getFlashdata('error') ?></div>
                                <?php endif; ?>
                                <?php if (session()->getFlashdata('info')): ?>
                                    <div class="alert alert-info fill"><?= session()->getFlashdata('info') ?></div>
                                <?php endif; ?>
                                <?php if (session()->getFlashdata('success')): ?>
                                    <div class="alert alert-success fill"><?= session()->getFlashdata('success') ?></div>
                                <?php endif; ?>

                                <div class="row form-group mt-3">
                                    <label for="password">رمز عبور :</label>
                                    <div class="position-relative">
                                        <input type="password" name="password" id="password" class="form-control text-center ltr font-lg bold pe-5" required placeholder="رمز عبور" />
                                        <i class="bi bi-eye-slash toggle-password position-absolute end-0 top-50 translate-middle-y me-3 fs-5" data-target="#password"></i>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-6 m-auto d-flex flex-column gap-2">
                                        <button type="submit" class="btn btn-success mt-3 mb-0 w-100">
                                            ورود به حساب
                                        </button>

                                        <a href="<?= site_url('user/login') ?>" class="btn btn-outline-secondary w-100">
                                            ← بازگشت به ورود
                                        </a>
 <a href="<?= site_url('user/login-otp') ?>" class="btn btn-outline-danger w-100">
                                           ورود با رمز یکبار مصرف
                                        </a>
                                        
                                    </div>
                                </div>
                            </div><!-- /.card-body -->
                        </form>
                    </div><!-- /.col -->

                </div><!-- /.row -->
            </div><!-- /.card -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div><!-- /.modal-page -->

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<script>
document.querySelectorAll('.toggle-password').forEach(icon => {
    icon.addEventListener('click', function() {
        const target = document.querySelector(this.getAttribute('data-target'));
        const type = target.getAttribute('type') === 'password' ? 'text' : 'password';
        target.setAttribute('type', type);
        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
    });
});
</script>

<?= $this->endSection() ?>
