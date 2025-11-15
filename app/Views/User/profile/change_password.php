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
                        <li class="active">تغییر رمز عبور</li>
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
            </div><!-- /.col-md-12 -->
            <!-- END BREADCRUMB -->

            <div class="col-lg-12">
                <div class="portlet box shadow">
                    <div class="portlet-heading">
                        <div class="portlet-title">
                            <h3 class="title">
                                <i class="icon-lock"></i>
                                تغییر رمز عبور
                            </h3>
                        </div><!-- /.portlet-title -->
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

                        <?php if (session()->getFlashdata('errors')): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                                        <li><?= esc($err) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="<?= site_url('users/save-password') ?>" method="post" class="form-horizontal">
                            <?= csrf_field() ?>

                            <!-- رمز عبور فعلی -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="current_password">رمز عبور فعلی</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input
                                            type="password"
                                            name="current_password"
                                            id="current_password"
                                            class="form-control"
                                            required
                                        >
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" onclick="togglePassword('current_password', this)">
                                                <i class="icon-eye"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- رمز عبور جدید -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="new_password">رمز عبور جدید</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input
                                            type="password"
                                            name="new_password"
                                            id="new_password"
                                            class="form-control"
                                            required
                                        >
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" onclick="togglePassword('new_password', this)">
                                                <i class="icon-eye"></i>
                                            </button>
                                        </span>
                                    </div>
                                    <span class="help-block">
                                        حداقل ۸ کاراکتر، فقط حروف انگلیسی و اعداد لاتین (A-Z, a-z, 0-9)
                                    </span>
                                </div>
                            </div>

                            <!-- تکرار رمز عبور جدید -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="confirm_password">تکرار رمز عبور جدید</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input
                                            type="password"
                                            name="confirm_password"
                                            id="confirm_password"
                                            class="form-control"
                                            required
                                        >
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" onclick="togglePassword('confirm_password', this)">
                                                <i class="icon-eye"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- دکمه‌ها -->
                            <div class="form-group">
                                <div class="col-md-9 col-md-offset-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="icon-check"></i>
                                        ذخیره رمز عبور
                                    </button>
                                    <a href="<?= site_url('users/dashboard') ?>" class="btn btn-default">
                                        بازگشت
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
    function togglePassword(inputId, btn) {
        var input = document.getElementById(inputId);
        if (!input) return;

        if (input.type === 'password') {
            input.type = 'text';
            // اگر آیکن خاصی داری می‌تونی اینجا عوضش کنی
            // btn.querySelector('i')?.classList.add('active');
        } else {
            input.type = 'password';
            // btn.querySelector('i')?.classList.remove('active');
        }
    }
</script>

<?= $this->endSection() ?>
