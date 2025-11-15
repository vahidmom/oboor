<?= $this->extend('User/layout/master_login') ?>

<style>
.otp-input {
    width: 60px;
    height: 56px;
    text-align: center;
    direction: ltr;
}
.countdown { font-variant-numeric: tabular-nums; }
.help-text { font-size: .9rem; color: #6c757d; }
</style>

<?= $this->section('content') ?>

<div class="fixed-modal-bg"></div>

<div class="modal-page shadow two-section">
  <div class="container-fluid">
    <div class="row">
      <div class="card shadow overflow-hidden">
        <div class="row">

          <!-- تصویر سمت راست -->
          <div class="col-lg-5 col-md-6 position-relative bg-cover" style="background-image:url(<?= base_url('assets/images/other/mobile-auth.jpg') ?>);">
            <div class="w-100 h-100 z-index-2 d-flex m-auto justify-content-center">
              <div class="mask bg-gradient-dark"></div>
              <div class="side-texts position-relative my-auto z-index-2">
                <div class="d-flex p-2 text-white my-3 bold">
                  تأیید شماره موبایل برای ادامه دسترسی به پنل لازم است
                </div>
              </div>
            </div>
          </div>

          <!-- سمت فرم -->
          <div class="col-lg-7 col-md-6 py-5">
            <div class="card-header px-4 py-2 text-center">
              <div class="logo-con mb-4"></div>
            </div>

            <div class="card-body pt-1">
              <!-- پیام‌های کلی (ارسال کد، خطای شماره و...) -->
              <div id="flashArea">
                <?php if (session()->getFlashdata('error')): ?>
                  <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('info')): ?>
                  <div class="alert alert-info"><?= session()->getFlashdata('info') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('success')): ?>
                  <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>
              </div>

              <div class="alert alert-success fill text-center bold">
                شماره خود را بررسی/ویرایش کنید و «ارسال کد» را بزنید.
              </div>

              <!-- موبایل -->
              <div class="mb-3">
                <label for="mobile" class="form-label">شماره موبایل</label>
                <input
                  type="text"
                  inputmode="numeric"
                  pattern="^09\d{9}$"
                  maxlength="11"
                  class="form-control text-center ltr"
                  id="mobile"
                  placeholder="مثال: 09123456789"
                  value="<?= esc($mobile ?? '') ?>"
                  required
                >
                <div class="help-text">شماره باید با 09 شروع شده و 11 رقم باشد.</div>
              </div>

              <!-- ارسال کد -->
              <div class="d-flex align-items-center gap-2 mb-4">
                <button id="sendBtn" class="btn btn-success">ارسال کد تأیید</button>
                <span class="text-muted small countdown" id="countdownText">
                  <?php if (!empty($remaining) && (int)$remaining > 0): ?>
                    تا ارسال مجدد: <span id="remainingSec"><?= (int)$remaining ?></span> ثانیه
                  <?php endif; ?>
                </span>
                <a href="<?= site_url('user/logout') ?>" class="btn btn-outline-secondary ms-auto">
                  ← بازگشت به صفحه اول
                </a>
              </div>

              <!-- بخش OTP: پیام مخصوص + ۴ باکس -->
              <div id="otpSection" class="<?= (!empty($remaining) && (int)$remaining>0) || !empty($otp_sent) ? '' : 'd-none' ?>">
                <hr class="my-4">

                <!-- پیام‌های مخصوص OTP -->
                <div id="otpFlashArea"></div>

                <label class="form-label">کد تأیید (۴ رقم)</label>
                <div class="d-flex justify-content-center gap-2 my-4" dir="ltr">
                  <input type="text" maxlength="1" class="otp-input form-control text-center fs-4" required>
                  <input type="text" maxlength="1" class="otp-input form-control text-center fs-4" required>
                  <input type="text" maxlength="1" class="otp-input form-control text-center fs-4" required>
                  <input type="text" maxlength="1" class="otp-input form-control text-center fs-4" required>
                  <input type="hidden" name="otp" id="otp">
                </div>

                <div class="d-grid gap-2">
                  <button id="verifyBtn" class="btn btn-primary">تأیید کد</button>
                  <a href="<?= site_url('user/logout') ?>" class="btn btn-outline-secondary">خروج از حساب</a>
                </div>
              </div>

            </div><!-- /.card-body -->
          </div><!-- /.col -->

        </div><!-- /.row -->
      </div><!-- /.card -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div><!-- /.modal-page -->

<script>
const sendBtn   = document.getElementById('sendBtn');
const mobileInp = document.getElementById('mobile');
const otpBox    = document.getElementById('otpSection');
const countdown = document.getElementById('countdownText');
const flashArea = document.getElementById('flashArea');

function showAlert(msg, type='info', area='flashArea') {
  const el = document.getElementById(area);
  if (el) el.innerHTML = `<div class="alert alert-${type}">${msg}</div>`;
}

function startTimer(seconds) {
  const btn = sendBtn;
  const el  = countdown;
  let rem   = parseInt(seconds, 10) || 0;

  if (rem <= 0) return;

  btn.disabled = true;

  // اگر span داخلی داریم (از سرور آمده)
  let within = document.getElementById('remainingSec');
  if (!within) {
    el.innerHTML = `تا ارسال مجدد: <span id="remainingSec">${rem}</span> ثانیه`;
    within = document.getElementById('remainingSec');
  } else {
    within.textContent = rem;
  }

  const t = setInterval(()=>{
    rem--;
    within.textContent = rem;
    if (rem <= 0) {
      clearInterval(t);
      el.textContent = '';
      btn.disabled = false;
      btn.textContent = 'ارسال مجدد کد';
    }
  }, 1000);
}

// اگر از سرور remaining داشتیم (ریفرش صفحه)، تایمر را شروع کن
<?php if (!empty($remaining) && (int)$remaining > 0): ?>
  startTimer(<?= (int)$remaining ?>);
  mobileInp.readOnly = true; mobileInp.disabled = true;
<?php endif; ?>

// ===== مدیریت ۴ باکس OTP =====
(function(){
  const fields = Array.from(document.querySelectorAll('.otp-input'));
  const hidden = document.getElementById('otp');

  function collect() {
    hidden.value = fields.map(i => (i.value || '')).join('');
  }

  fields.forEach((inp, idx) => {
    inp.addEventListener('input', () => {
      // فقط یک رقم
      inp.value = inp.value.replace(/\D/g,'').slice(0,1);
      if (inp.value && idx < fields.length - 1) fields[idx + 1].focus();
      collect();
    });
    inp.addEventListener('keydown', (e) => {
      if (e.key === 'Backspace' && !inp.value && idx > 0) fields[idx - 1].focus();
    });
    inp.addEventListener('paste', (e) => {
      const text = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g,'').slice(0,4);
      if (!text) return;
      e.preventDefault();
      for (let i=0; i<fields.length; i++) fields[i].value = text[i] || '';
      collect();
      fields[Math.min(text.length, fields.length) - 1]?.focus();
    });
  });

  // دکمه تأیید کد (Ajax)
  document.getElementById('verifyBtn')?.addEventListener('click', async (e)=>{
    e.preventDefault();
    collect();
    const otp = (hidden.value || '').trim();
    if (!/^\d{4}$/.test(otp)) {
      showAlert('کد باید ۴ رقم باشد.','danger','otpFlashArea');
      return;
    }

    showAlert('در حال بررسی کد...','info','otpFlashArea');
    try {
      const res = await fetch('<?= site_url('user/check-phone-otp') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ otp })
      });
      const data = await res.json();
      if (data.status === 'ok') {
        showAlert('شماره با موفقیت تأیید شد. در حال انتقال...','success','otpFlashArea');
        setTimeout(() => window.location.href = '<?= site_url('users/dashboard') ?>', 900);
      } else {
        showAlert(data.message || 'کد نادرست است.','danger','otpFlashArea');
      }
    } catch (err) {
      console.error(err);
      showAlert('خطا در ارتباط با سرور.','danger','otpFlashArea');
    }
  });
})();

// ===== ارسال کد (Ajax) =====
sendBtn.addEventListener('click', async (e)=>{
  e.preventDefault();
  const mobile = (mobileInp.value || '').trim();
  if (!/^09\d{9}$/.test(mobile)) {
    showAlert('شماره موبایل معتبر نیست.','danger');
    return;
  }

  showAlert('در حال ارسال کد...','info');
  try {
    const res = await fetch('<?= site_url('user/send-phone-otp') ?>', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      body: JSON.stringify({ mobile })
    });
    const data = await res.json();

    if (data.status === 'ok') {
      showAlert('کد تأیید ارسال شد.','success');
      mobileInp.readOnly = true; mobileInp.disabled = true;
      otpBox.classList.remove('d-none');
      startTimer(data.remaining || 120);
    } else if (data.status === 'wait') {
      showAlert(`لطفاً ${data.remaining} ثانیه دیگر دوباره تلاش کنید.`,'warning');
      mobileInp.readOnly = true; mobileInp.disabled = true;
      otpBox.classList.remove('d-none');
      startTimer(data.remaining);
    } else {
      showAlert(data.message || 'خطا در ارسال کد.','danger');
    }
  } catch (err) {
    console.error(err);
    showAlert('خطا در ارتباط با سرور.','danger');
  }
});
</script>

<?= $this->endSection() ?>
