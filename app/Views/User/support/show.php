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
                        <li class="active"><?= esc($ticket['subject']) ?></li>
                    </ul>
                    <div class="breadcrumb-left">
                        <i class="icon-calendar"></i>
                        <?php if (function_exists('to_jalali')): ?>
                            <?= esc(to_jalali($ticket['created_at'], 'Y/m/d')) ?>
                        <?php else: ?>
                            <?= esc($ticket['created_at']) ?>
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
                                تیکت: <?= esc($ticket['subject']) ?>
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

                        <!-- اطلاعات کلی تیکت -->
                        <div class="panel panel-default mb-3">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>عنوان:</strong>
                                        <div><?= esc($ticket['subject']) ?></div>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>موضوع:</strong>
                                        <div><?= esc($ticket['category'] ?? '-') ?></div>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>وضعیت:</strong>
                                        <div>
                                            <?php
                                            $status = (int) $ticket['status'];
                                            switch ($status) {
                                                case 0:
                                                    $statusClass = 'badge badge-warning';
                                                    $statusText  = 'منتظر پاسخ پشتیبان';
                                                    break;
                                                case 1:
                                                    $statusClass = 'badge badge-primary';
                                                    $statusText  = 'پاسخ داده شده';
                                                    break;
                                                case 2:
                                                    $statusClass = 'badge badge-info';
                                                    $statusText  = 'پاسخ کاربر';
                                                    break;
                                                case 3:
                                                    $statusClass = 'badge badge-secondary';
                                                    $statusText  = 'بسته شده';
                                                    break;
                                                default:
                                                    $statusClass = 'badge badge-light';
                                                    $statusText  = 'نامشخص';
                                            }
                                            ?>
                                            <span class="<?= $statusClass ?>">
                                                <?= esc($statusText) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>تاریخ ایجاد:</strong>
                                        <div>
                                            <?php if (function_exists('to_jalali')): ?>
                                                <?= esc(to_jalali($ticket['created_at'])) ?>
                                            <?php else: ?>
                                                <?= esc($ticket['created_at']) ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>آخرین بروزرسانی:</strong>
                                        <div>
                                            <?php if (function_exists('to_jalali')): ?>
                                                <?= esc(to_jalali($ticket['updated_at'])) ?>
                                            <?php else: ?>
                                                <?= esc($ticket['updated_at']) ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- تاریخچه پیام‌ها -->
                     <h4 style="margin-top: 25px; margin-bottom: 15px;">تاریخچه گفتگو</h4>

<?php if (!empty($messages)): ?>
    <div class="timeline" style="margin-bottom: 25px;">
        <?php foreach ($messages as $message): ?>
            <?php
            $isUser   = ($message['sender_type'] === 'user');
            $whoLabel = $isUser ? 'شما' : 'پشتیبان';
            $whoClass = $isUser ? 'label label-success' : 'label label-danger';

            // کلاس پنل و رنگ‌ها بر اساس فرستنده
            if ($isUser) {
                $panelClass   = 'panel panel-success mb-2';
                $headerStyle  = 'background-color:#e6f9e9;';  // سبز روشن
                $bodyStyle    = 'background-color:#f9fffa;';
            } else {
                $panelClass   = 'panel panel-danger mb-2';
                $headerStyle  = 'background-color:#fde4e4;';  // قرمز روشن
                $bodyStyle    = 'background-color:#fff5f5;';
            }
            ?>
            <div class="<?= $panelClass ?>" style="border-radius: 6px;">
                <div class="panel-heading" style="<?= $headerStyle ?> border-radius:6px 6px 0 0;">
                    <span class="<?= $whoClass ?>"><?= $whoLabel ?></span>
                    <span class="pull-left" style="font-size: 12px;">
                        <?php if (function_exists('to_jalali')): ?>
                            <?= esc(to_jalali($message['created_at'])) ?>
                        <?php else: ?>
                            <?= esc($message['created_at']) ?>
                        <?php endif; ?>
                    </span>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body" style="<?= $bodyStyle ?>">
                    <p style="margin-bottom: 0;"><?= nl2br(esc($message['message'])) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>هنوز هیچ پیامی برای این تیکت ثبت نشده است.</p>
<?php endif; ?>


                        <!-- فایل‌های ضمیمه -->
                        <?php if (!empty($attachments)): ?>
                            <hr>
                            <h4>فایل‌های ضمیمه</h4>
                            <ul>
                                <?php foreach ($attachments as $att): ?>
                                    <li>
                                        <a href="<?= site_url('support/attachment/' . $att['id']) ?>">
                                            <?= esc($att['original_name']) ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                    <hr style="margin-top: 30px; margin-bottom: 20px;">

<!-- فرم پاسخ کاربر -->
<?php if ((int) $ticket['status'] === 3): ?>
    <div class="alert alert-info" style="margin-top: 10px;">
        این تیکت <strong>بسته شده</strong> است و امکان ارسال پاسخ جدید وجود ندارد.
    </div>
<?php else: ?>
    <h4 style="margin-bottom: 15px; margin-top: 5px;">ارسال پاسخ جدید</h4>

    <form action="<?= site_url('support/reply/' . $ticket['id']) ?>" method="post" enctype="multipart/form-data" style="margin-top: 10px;">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="message">متن پاسخ شما</label>
            <textarea name="message" id="message" rows="5" class="form-control" required><?= old('message') ?></textarea>
        </div>

        <!-- اینجا همون باکس آپلود جدید -->
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

        <button type="submit" class="btn btn-primary" style="margin-top: 15px;">
            ارسال پاسخ
        </button>
    </form>
<?php endif; ?>


                    </div><!-- /.portlet-body -->
                </div><!-- /.portlet -->
            </div><!-- /.col-lg-12 -->

        </div><!-- /.row -->
    </div><!-- /#inner-content -->
</div><!-- /#page-content -->


		<?= $this->endSection() ?>