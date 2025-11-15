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
                        <li class="active">پروفایل کاربری</li>
                    </ul>
                    <div class="breadcrumb-left">
                        <i class="icon-calendar"></i>
                        <?php if (function_exists('to_jalali')): ?>
                            <?= esc(to_jalali(date('Y-m-d H:i:s'), 'Y/m/d')) ?>
                        <?php else: ?>
                            <?= esc(date('Y/m/d')) ?>
                        <?php endif; ?>
                    </div><!-- /.breadcrumb-left -->
                </div><!-- /.breadcrumb-box -->
            </div>
            <!-- END BREADCRUMB -->

            <div class="col-lg-12">
                <div class="portlet box shadow">
                    <div class="portlet-heading">
                        <div class="portlet-title">
                            <h3 class="title">
                                <i class="icon-user"></i>
                                پروفایل کاربری
                            </h3>
                        </div>
                    </div><!-- /.portlet-heading -->

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

                        <form action="<?= site_url('users/save-profile') ?>" method="post" class="form-horizontal" enctype="multipart/form-data">
                            <?= csrf_field() ?>

                            <div class="row">
                                <!-- ستون راست: آواتار + اطلاعات تماس -->
                                <div class="col-md-6">

                                    <!-- باکس آواتار -->
                                    <div class="panel panel-default shadow-sm" style="margin-bottom:20px;">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <i class="icon-picture"></i>
                                                تصویر پروفایل
                                            </h4>
                                        </div>
                                       <div class="panel-body">

    <div class="text-center" style="margin-bottom:15px;">
        <img src="<?= esc($avatarUrl ?? $defaultAvatarUrl) ?>"
             alt="آواتار"
             class="img-circle"
             style="width:90px;height:90px;object-fit:cover;border:2px solid #ddd;">
    </div>

    <div class="form-group" style="margin-bottom:10px;">
        <label class="control-label">انتخاب تصویر جدید</label>

        <input type="file"
               name="avatar"
               id="avatarInput"
               class="form-control"
               style="padding:6px 8px;">

        <span class="help-block">
            فرمت‌های مجاز: JPG, PNG, GIF, WEBP — حداکثر ۲ مگابایت
        </span>

        <small id="avatarFilename" class="text-muted"></small>
    </div>

</div><!-- /.panel-body -->

                                    </div><!-- /.panel -->
<?php if (! empty($user['avatar'])): ?>
    <div class="panel panel-default shadow-sm" style="margin-bottom:20px;">
        <div class="panel-heading">
            <h4 class="panel-title">
                <i class="icon-picture"></i>
                تنظیمات تصویر پروفایل
            </h4>
        </div>
        <div class="panel-body">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="remove_avatar" value="1">
                    حذف آواتار و استفاده از تصویر پیش‌فرض
                </label>
            </div>
        </div>
    </div>
<?php endif; ?>

                                    <!-- باکس ایمیل و موبایل -->
                                    <div class="panel panel-default shadow-sm" style="margin-bottom:20px;">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <i class="icon-mail"></i>
                                                اطلاعات تماس
                                            </h4>
                                        </div>
                                        <div class="panel-body">

                     <!-- ایمیل -->
<div class="form-group">
    <label class="col-sm-4 control-label">ایمیل</label>
    <div class="col-sm-8">
        <p class="form-control-static" style="margin-bottom:5px;">

            <?= esc($user['email'] ?? '-') ?>

            <?php if (!empty($user['email']) && (int)($user['email_verified'] ?? 0) === 1): ?>
                <span class="label label-success" style="margin-right:5px;">تأیید شده</span>
            <?php else: ?>
                <span class="label label-danger" style="margin-right:5px;">تأیید نشده</span>
            <?php endif; ?>

            <a href="<?= site_url('users/change-email') ?>"
               class="btn btn-xs btn-info"
               style="margin-right:5px; vertical-align:middle;">
                <i class="icon-pencil"></i>
                تغییر ایمیل
            </a>

        </p>
    </div>
</div>



  <!-- موبایل -->
<div class="form-group">
    <label class="col-sm-4 control-label">موبایل</label>
    <div class="col-sm-8">
        <p class="form-control-static" style="margin-bottom:5px;">

            <?= esc($user['mobile'] ?? '-') ?>

            <?php if (!empty($user['mobile']) && (int)($user['phone_verified'] ?? 0) === 1): ?>
                <span class="label label-success" style="margin-right:5px;">تأیید شده</span>
            <?php else: ?>
                <span class="label label-danger" style="margin-right:5px;">تأیید نشده</span>
            <?php endif; ?>

            <a href="<?= site_url('users/change-mobile') ?>"
               class="btn btn-xs btn-info"
               style="margin-right:5px; vertical-align:middle;">
                <i class="icon-pencil"></i>
                تغییر شماره موبایل
            </a>

        </p>
    </div>
</div>



                                        </div><!-- /.panel-body -->
                                    </div><!-- /.panel -->

                                </div><!-- /.col-md-6 -->

                                <!-- ستون چپ: آمار خرید + تنظیمات اعلان + آخرین ورود -->
                                <div class="col-md-6">

                                    <!-- باکس آمار خرید -->
                                    <div class="panel panel-default shadow-sm" style="margin-bottom:20px;">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <i class="icon-basket"></i>
                                                آمار خرید
                                            </h4>
                                        </div>
                                        <div class="panel-body">
                                            <table class="table table-bordered table-striped mb-0">
                                                <tbody>
                                                    <tr>
                                                        <th style="width:40%;">تعداد خرید</th>
                                                        <td><?= esc($user['total_orders'] ?? 0) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>مجموع مبلغ خرید</th>
                                                        <td>
                                                            <?= esc(number_format((int)($user['total_spent'] ?? 0))) ?>
                                                            <span>ریال</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>درصد تخفیف فعلی</th>
                                                        <td>
                                                            <span class="label label-info">
                                                                <?= esc($user['discount_percent'] ?? 0) ?>%
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div><!-- /.panel-body -->
                                    </div><!-- /.panel -->

                                    <!-- باکس آخرین ورود -->
                                    <div class="panel panel-default shadow-sm" style="margin-bottom:20px;">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <i class="icon-login"></i>
                                                آخرین ورود
                                            </h4>
                                        </div>
                                        <div class="panel-body">
                                            <?php if (! empty($user['last_login_at'])): ?>
                                                <p class="form-control-static">
                                                    <?php if (function_exists('to_jalali')): ?>
                                                        تاریخ: <?= esc(to_jalali($user['last_login_at'])) ?><br>
                                                    <?php else: ?>
                                                        تاریخ: <?= esc($user['last_login_at']) ?><br>
                                                    <?php endif; ?>
                                                    IP: <strong><?= esc($user['last_login_ip'] ?? '-') ?></strong>
                                                </p>
                                            <?php else: ?>
                                                <p class="text-muted">هنوز اطلاعات ورود ثبت نشده است.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- باکس تنظیمات اعلان -->
                                    <div class="panel panel-default shadow-sm">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <i class="icon-bell"></i>
                                                تنظیمات اعلان
                                            </h4>
                                        </div>
                                        <div class="panel-body">

                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="notify_email" value="1"
                                                        <?= (int)($user['notify_email'] ?? 1) === 1 ? 'checked' : '' ?>>
                                                    دریافت ایمیل برای <strong>پشتیبانی / خرید / تمدید</strong>
                                                </label>
                                            </div>

                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="notify_sms" value="1"
                                                        <?= (int)($user['notify_sms'] ?? 1) === 1 ? 'checked' : '' ?>>
                                                    دریافت پیامک برای <strong>پشتیبانی / خرید / تمدید</strong>
                                                </label>
                                            </div>

                                            <hr style="margin:10px 0;">

                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="notify_email_newsletter" value="1"
                                                        <?= (int)($user['notify_email_newsletter'] ?? 1) === 1 ? 'checked' : '' ?>>
                                                    دریافت <strong>خبرنامه ایمیلی</strong>
                                                </label>
                                            </div>

                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="notify_sms_newsletter" value="1"
                                                        <?= (int)($user['notify_sms_newsletter'] ?? 1) === 1 ? 'checked' : '' ?>>
                                                    دریافت <strong>خبرنامه پیامکی</strong>
                                                </label>
                                            </div>

                                        </div><!-- /.panel-body -->
                                    </div><!-- /.panel -->

                                </div><!-- /.col-md-6 -->
                            </div><!-- /.row -->

                            <!-- دکمه‌ها -->
                            <div class="form-group" style="margin-top:20px;">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="icon-check"></i>
                                        ذخیره تغییرات پروفایل
                                    </button>

                                    <a href="<?= site_url('users/change-password') ?>" class="btn btn-warning">
                                        <i class="icon-lock"></i>
                                        تغییر رمز عبور
                                    </a>
                                </div>
                            </div>

                        </form>

                    </div><!-- /.portlet-body -->
                </div><!-- /.portlet -->
            </div><!-- /.col-lg-12 -->

        </div><!-- /.row -->
    </div><!-- /#inner-content -->
</div><!-- /#page-content -->
<script>
    (function() {
        // نمایش نام فایل انتخاب شده
        var input = document.getElementById('avatarInput');
        var label = document.getElementById('avatarFilename');

        if (input && label) {
            input.addEventListener('change', function () {
                if (this.files && this.files.length > 0) {
                    label.textContent = 'فایل انتخاب شده: ' + this.files[0].name;
                } else {
                    label.textContent = '';
                }
            });
        }

        // جلوگیری از باز شدن دیالوگ آپلود هنگام کلیک روی "حذف آواتار"
        // اینجا روی خود document و در فاز capture گوش می‌دیم
        document.addEventListener('click', function (e) {
            // اگر کلیک هر جایی داخل .avatar-remove بود
            var removeBox = e.target.closest('.avatar-remove');
            if (removeBox) {
                e.stopPropagation();      // نذار به handlerهای بعدی برسه
                // e.preventDefault();    // اگر حس کردی تیک نمی‌خوره، این خط رو کامنت‌نگه دار
            }
        }, true); // 🔴 فاز capture
    })();
</script>


<?= $this->endSection() ?>
