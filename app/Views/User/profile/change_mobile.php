<?= $this->extend('User/layout/master') ?>

<?= $this->section('content') ?>

<div id="page-content">
    <div id="inner-content">
        <div class="row">

            <!-- BEGIN BREADCRUMB -->
            <div class="col-md-12">
                <div class="breadcrumb-box shadow">
                    <ul class="breadcrumb">
                        <li><a href="<?= site_url('users/dashboard') ?>">پیشخوان</a></li>
                        <li><a href="<?= site_url('users/profile') ?>">پروفایل کاربری</a></li>
                        <li class="active">تغییر شماره موبایل</li>
                    </ul>
                    <div class="breadcrumb-left">
                        <i class="icon-calendar"></i>
                        <?php if (function_exists('to_jalali')): ?>
                            <?= esc(to_jalali(date('Y-m-d H:i:s'), 'Y/m/d')) ?>
                        <?php else: ?>
                            <?= esc(date('Y/m/d')) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- END BREADCRUMB -->

            <div class="col-lg-12">
                <div class="portlet box shadow">
                    <div class="portlet-heading">
                        <div class="portlet-title">
                            <h3 class="title">
                                <i class="icon-phone"></i>
                                تغییر شماره موبایل
                            </h3>
                        </div>
                    </div>

                    <div class="portlet-body">

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?= esc(session()->getFlashdata('error')) ?>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success">
                                <?= esc(session()->getFlashdata('success')) ?>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-6">

                                <!-- باکس موبایل فعلی -->
                                <div class="panel panel-default shadow-sm" style="margin-bottom:20px;">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <i class="icon-info"></i>
                                            موبایل فعلی
                                        </h4>
                                    </div>
                                    <div class="panel-body">
                                        <p class="form-control-static">
                                            <strong><?= esc($user['mobile'] ?? '-') ?></strong>
                                            <?php if (!empty($user['mobile']) && (int)($user['phone_verified'] ?? 0) === 1): ?>
                                                <span class="label label-success" style="margin-right:5px;">تأیید شده</span>
                                            <?php else: ?>
                                                <span class="label label-danger" style="margin-right:5px;">تأیید نشده</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>

                                <!-- باکس وارد کردن موبایل جدید -->
                                <div class="panel panel-default shadow-sm" style="margin-bottom:20px;">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <i class="icon-pencil"></i>
                                            وارد کردن شماره موبایل جدید
                                        </h4>
                                    </div>
                                    <div class="panel-body">
                                        <form action="<?= site_url('users/change-mobile/send-otp') ?>" method="post" class="form-horizontal">
                                            <?= csrf_field() ?>

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="new_mobile">موبایل جدید</label>
                                                <div class="col-sm-8">
                                                    <input type="text"
                                                           name="new_mobile"
                                                           id="new_mobile"
                                                           class="form-control"
                                                           value="<?= esc(old('new_mobile', $targetMobile ?? '')) ?>"
                                                           placeholder="مثلاً 0912xxxxxxx">
                                                    <span class="help-block">
                                                        شماره موبایل جدید خود را وارد کنید. پس از ارسال کد، باید آن را تأیید کنید.
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-12 text-left">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="icon-paper-plane"></i>
                                                        ارسال کد تأیید
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div><!-- /.col-md-6 -->

                            <div class="col-md-6">

                                <!-- باکس وارد کردن کد OTP -->
                                <div class="panel panel-default shadow-sm">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <i class="icon-key"></i>
                                            تأیید شماره موبایل
                                        </h4>
                                    </div>
                                    <div class="panel-body">

                                        <?php if (!empty($otpSent) && $otpSent && !empty($targetMobile)): ?>
                                            <p>
                                                کد تأیید به شماره
                                                <strong><?= esc($targetMobile) ?></strong>
                                                ارسال شد.
                                            </p>
                                        <?php else: ?>
                                            <p class="text-muted">
                                                ابتدا شماره موبایل جدید را وارد کرده و دکمه «ارسال کد تأیید» را بزنید.
                                            </p>
                                        <?php endif; ?>

                                        <form action="<?= site_url('users/change-mobile/verify-otp') ?>" method="post" class="form-horizontal" id="otpForm">
                                            <?= csrf_field() ?>

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">کد ۴ رقمی</label>
                                                <div class="col-sm-12">

                                                    <div class="otp-inputs" style="direction:ltr; text-align:center;">
                                                        <input type="text" maxlength="1" class="form-control otp-digit" name="otp_1" inputmode="numeric" style="width:45px; display:inline-block; text-align:center; margin:0 3px;">
                                                        <input type="text" maxlength="1" class="form-control otp-digit" name="otp_2" inputmode="numeric" style="width:45px; display:inline-block; text-align:center; margin:0 3px;">
                                                        <input type="text" maxlength="1" class="form-control otp-digit" name="otp_3" inputmode="numeric" style="width:45px; display:inline-block; text-align:center; margin:0 3px;">
                                                        <input type="text" maxlength="1" class="form-control otp-digit" name="otp_4" inputmode="numeric" style="width:45px; display:inline-block; text-align:center; margin:0 3px;">
                                                    </div>

                                                    <span class="help-block">
                                                        کد ارسال‌شده را وارد کنید.
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- شمارش معکوس -->
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">زمان باقیمانده</label>
                                                <div class="col-sm-8">
                                                    <?php $remaining = (int)($remainingSeconds ?? 0); ?>
                                                    <p id="otpCountdown" data-remaining="<?= $remaining ?>">
                                                        <?php if ($remaining > 0): ?>
                                                            <span>۰۰:۰۰</span>
                                                        <?php else: ?>
                                                            <span class="text-muted">زمان کد به پایان رسیده است. لطفاً دوباره درخواست کد دهید.</span>
                                                        <?php endif; ?>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-12 text-left">
                                                    <button type="submit" class="btn btn-success" <?= ($remaining ?? 0) <= 0 ? 'disabled' : '' ?>>
                                                        <i class="icon-check"></i>
                                                        تأیید موبایل
                                                    </button>
                                                </div>
                                            </div>

                                        </form>

                                    </div><!-- /.panel-body -->
                                </div><!-- /.panel -->

                            </div><!-- /.col-md-6 -->
                        </div><!-- /.row -->

                        <div class="form-group" style="margin-top:20px;">
                            <div class="col-md-12 text-center">
                                <a href="<?= site_url('users/profile') ?>" class="btn btn-default">
                                    بازگشت به پروفایل
                                </a>
                            </div>
                        </div>

                    </div><!-- /.portlet-body -->
                </div><!-- /.portlet -->
            </div><!-- /.col-lg-12 -->

        </div><!-- /.row -->
    </div><!-- /#inner-content -->
</div><!-- /#page-content -->

<script>
    (function() {
        // 1) OTP چهار رقمی – مدیریت فوکوس بین فیلدها
        var inputs = document.querySelectorAll('.otp-digit');
        if (inputs.length) {
            inputs.forEach(function (input, index) {
                input.addEventListener('input', function (e) {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    if (this.value.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                });

                input.addEventListener('keydown', function (e) {
                    if (e.key === 'Backspace' && !this.value && index > 0) {
                        inputs[index - 1].focus();
                    }
                    if (e.key === 'ArrowLeft' && index > 0) {
                        inputs[index - 1].focus();
                        e.preventDefault();
                    }
                    if (e.key === 'ArrowRight' && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                        e.preventDefault();
                    }
                });
            });
        }

        // 2) شمارش معکوس – بر اساس remainingSeconds از سرور
        var countdownEl = document.getElementById('otpCountdown');
        if (countdownEl) {
            var remaining = parseInt(countdownEl.getAttribute('data-remaining'), 10) || 0;
            var btn = document.querySelector('#otpForm button[type="submit"]');

            function renderTime(sec) {
                if (sec < 0) sec = 0;
                var m = Math.floor(sec / 60);
                var s = sec % 60;
                var mm = (m < 10 ? '0' : '') + m;
                var ss = (s < 10 ? '0' : '') + s;
                countdownEl.innerHTML = '<span>' + mm + ':' + ss + '</span>';
            }

            if (remaining > 0) {
                renderTime(remaining);

                var interval = setInterval(function () {
                    remaining--;
                    if (remaining <= 0) {
                        clearInterval(interval);
                        countdownEl.innerHTML = '<span class="text-muted">زمان کد به پایان رسیده است. لطفاً دوباره درخواست کد دهید.</span>';
                        if (btn) btn.disabled = true;
                    } else {
                        renderTime(remaining);
                    }
                }, 1000);
            } else {
                if (btn) btn.disabled = true;
            }
        }
    })();
</script>

<?= $this->endSection() ?>
