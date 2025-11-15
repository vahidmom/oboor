<?= $this->extend('User/layout/master_login') ?>

<style>
.otp-input {
    width: 60px;
    height: 60px;
    text-align: center;
    font-size: 1.5rem;
    font-weight: bold;
    border: 2px solid #ccc;
    border-radius: 10px;
    transition: border-color 0.2s ease;
}
.otp-input:focus {
    border-color: #28a745;
    outline: none;
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
                    <div class="col-lg-5 col-md-6 col-12 position-relative bg-cover" 
                         style="background-image: url(<?= base_url('assets/images/other/mobile-auth.jpg') ?>);">
                        <div class="w-100 h-100 z-index-2 d-flex m-auto justify-content-center">
                            <div class="mask bg-gradient-dark"></div>
                            <div class="side-texts position-relative my-auto z-index-2">
                                <div class="d-flex p-2 text-white my-3 bold text-center">
                                 
                            <p class="small"> کد یکبار مصرف برای ورود  به <?= esc($user['mobile']) ?> و <?= esc($user['email']) ?>ارسال شد</p> 
                        
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- فرم ورود با OTP -->
                    <div class="col-lg-7 col-md-6 py-5">
                        <form id="otpForm" method="post" action="<?= site_url('user/verify-otp') ?>">
                            <?= csrf_field() ?>

                            <div class="card-header px-4 py-2 text-center">
                                <div class="logo-con mb-4"></div>
                            </div>

                            <div class="card-body pt-1 text-center">
                                <div class="alert alert-success fill text-center bold" id="mainAlert">
                                    لطفاً کد ۴ رقمی ارسال‌شده را وارد کنید
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

                                 <div class="d-flex justify-content-center gap-2 my-4" dir="ltr">
                                <input type="text" maxlength="1" class="otp-input form-control text-center fs-4" required>
                                <input type="text" maxlength="1" class="otp-input form-control text-center fs-4" required>
                                <input type="text" maxlength="1" class="otp-input form-control text-center fs-4" required>
                                <input type="text" maxlength="1" class="otp-input form-control text-center fs-4" required>
                                <input type="hidden" name="otp" id="otp">
                            </div>


                                <div class="col-md-6 m-auto d-flex flex-column gap-2">
                                    <button type="submit" class="btn btn-success mt-3 mb-0 w-100">
                                        تأیید کد
                                    </button>

                                    <button id="resendBtn" type="button" class="btn btn-outline-primary w-100 mt-2" disabled>
                                        ارسال مجدد (<span id="timer"></span>)
                                    </button>

                                    <a href="<?= site_url('user/enter-password') ?>" 
                                       class="btn btn-outline-secondary w-100 mt-2">
                                        ← بازگشت به مرحله قبل
                                    </a>
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
let remaining = <?= $remaining ?? 120 ?>;
const timerEl = document.getElementById('timer');
const resendBtn = document.getElementById('resendBtn');
const otpInputs = document.querySelectorAll('.otp-input');
const otpHidden = document.getElementById('otp');

function updateTimer() {
    if (remaining <= 0) {
        resendBtn.disabled = false;
        timerEl.textContent = '0:00';
        localStorage.removeItem('otp_timer');
        return;
    }
    const mins = Math.floor(remaining / 60);
    const secs = remaining % 60;
    timerEl.textContent = `${mins}:${secs.toString().padStart(2, '0')}`;
    remaining--;
    localStorage.setItem('otp_timer', remaining);
    setTimeout(updateTimer, 1000);
}

if (localStorage.getItem('otp_timer')) {
    remaining = parseInt(localStorage.getItem('otp_timer'));
}
updateTimer();

// فقط عدد فارسی یا انگلیسی مجاز
otpInputs.forEach((input, index) => {
    input.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9۰-۹]/g, '');
        if (this.value && index < otpInputs.length - 1) otpInputs[index + 1].focus();

        const all = Array.from(otpInputs).map(i => i.value).join('');
        if (all.length === 4) {
            otpHidden.value = all;
            document.getElementById('otpForm').submit();
        }
    });
});

resendBtn.addEventListener('click', async (e) => {
    e.preventDefault();
    resendBtn.disabled = true;

    try {
        const res = await fetch('<?= site_url('user/resend-otp') ?>');
        const data = await res.json();

        if (data.status === 'ok') {
            // شروع تایمر دقیق سمت کلاینت
            remaining = 120;
            localStorage.setItem('otp_timer', remaining);
            updateTimer();
        } 
        else if (data.status === 'wait') {
            // هنوز در زمان محدودیت
            remaining = data.remaining;
            localStorage.setItem('otp_timer', remaining);
            updateTimer();
        }
    } catch (error) {
        console.error('خطا در ارسال مجدد OTP:', error);
        resendBtn.disabled = false;
    }
});

</script>

<?= $this->endSection() ?>
