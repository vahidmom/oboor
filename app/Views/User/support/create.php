<?= $this->extend('User/layout/master') ?>
<?= $this->section('content') ?>
<div id="page-content">
    <div id="inner-content">
        <div class="row">

            <!-- BREADCRUMB -->
            <div class="col-md-12">
                <div class="breadcrumb-box shadow">
                    <ul class="breadcrumb">
                        <li><a href="<?= site_url('users/dashboard') ?>">پیشخوان</a></li>
                        <li><a href="<?= site_url('support') ?>">تیکت‌های پشتیبانی</a></li>
                        <li class="active">ایجاد تیکت جدید</li>
                    </ul>
                    <div class="breadcrumb-left">
                        <i class="icon-calendar"></i>
                        <?php if (function_exists('to_jalali')): ?>
                            <?= esc(to_jalali(date('Y-m-d H:i:s'), 'Y/m/d')) ?>
                        <?php else: ?>
                            <?= date('Y/m/d') ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- /BREADCRUMB -->

            <div class="col-lg-12">
                <div class="portlet box shadow">
                    <div class="portlet-heading">
                        <div class="portlet-title">
                            <h3 class="title">
                                <i class="icon-frane"></i>
                                ایجاد تیکت جدید
                            </h3>
                        </div>
                    </div>

                    <div class="portlet-body">

                        <?php if ($error = session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?= esc($error) ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($success = session()->getFlashdata('success')): ?>
                            <div class="alert alert-success">
                                <?= esc($success) ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?= site_url('support/store') ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>

                            <div class="form-group">
                                <label for="subject">عنوان تیکت <span style="color:red">*</span></label>
                                <input type="text"
                                       name="subject"
                                       id="subject"
                                       class="form-control"
                                       value="<?= old('subject') ?>"
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="category">موضوع / بخش (اختیاری)</label>
                                <select name="category" id="category" class="form-control">
                                    <option value="">انتخاب کنید...</option>
                                    <option value="عمومی" <?= old('category') === 'عمومی' ? 'selected' : '' ?>>عمومی</option>
                                    <option value="ورود / ثبت نام" <?= old('category') === 'ورود / ثبت نام' ? 'selected' : '' ?>>ورود / ثبت نام</option>
                                    <option value="پرداخت" <?= old('category') === 'پرداخت' ? 'selected' : '' ?>>پرداخت</option>
                                    <option value="سایر" <?= old('category') === 'سایر' ? 'selected' : '' ?>>سایر</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="message">توضیحات تیکت <span style="color:red">*</span></label>
                                <textarea name="message"
                                          id="message"
                                          rows="6"
                                          class="form-control"
                                          required><?= old('message') ?></textarea>
                            </div>

                            <div class="form-group" style="margin-top: 20px;">
                                <label for="attachment">فایل ضمیمه (اختیاری)</label>
                                <div style="border: 1px dashed #bbb; background-color: #f9f9f9; padding: 10px 12px; display: inline-block; border-radius: 4px;">
                                    <input
                                        type="file"
                                        name="attachment"
                                        id="attachment"
                                        class="form-control-file"
                                        style="position: static; width: auto; display: inline-block;"
                                    >
                                    <div style="font-size: 11px; color:#666; margin-top:5px;">
                                        حداکثر حجم ۲ مگابایت - فرمت‌های مجاز: JPG, PNG, PDF, ZIP
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success" style="margin-top: 20px;">
                                ثبت تیکت
                            </button>
                            <a href="<?= site_url('support') ?>" class="btn btn-default" style="margin-top: 20px;">
                                بازگشت به لیست تیکت‌ها
                            </a>
                        </form>

                    </div><!-- /.portlet-body -->
                </div><!-- /.portlet -->
            </div><!-- /.col-lg-12 -->

        </div><!-- /.row -->
    </div><!-- /#inner-content -->
</div><!-- /#page-content -->

		<?= $this->endSection() ?>