<?= $this->extend('User/layout/master') ?>
<?= $this->section('content') ?>
  <!-- BEGIN PAGE CSS -->
        <link href="<?= base_url('assets/plugins/data-table/DataTables-1.10.16/css/jquery.dataTables.css') ?>" rel="stylesheet">
        <!-- END PAGE CSS -->
<div id="page-content">
    <div id="inner-content">
        <div class="row">

            <!-- BEGIN BREADCRUMB -->
            <div class="col-md-12">
                <div class="breadcrumb-box shadow">
                    <ul class="breadcrumb">
                        <li><a href="<?= site_url('users/dashboard') ?>">پیشخوان</a></li>
                        <li class="active">تیکت‌های پشتیبانی</li>
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
                                <i class="icon-frane"></i>
                                تیکت‌های پشتیبانی
                            </h3>
                        </div><!-- /.portlet-title -->

                        <!-- آیکن‌های بزرگ کردن و جمع شدن پاک شدند -->

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

                        <div class="top-buttons-box mb-2">
                            <a class="btn btn-success" href="<?= site_url('support/create') ?>">
                                <i class="icon-plus"></i>
                                افزودن تیکت جدید
                            </a>
                        </div><!-- /.top-buttons-box -->

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped" id="data-table">
                                <thead>
                                    <tr>
                                        <th>ردیف</th>
                                        <th>عنوان</th>
                                        <th>موضوع</th>
                                        <th>وضعیت</th>
                                        <th>تاریخ ارسال</th>
                                        <th>تاریخ آخرین بروزرسانی</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($tickets)): ?>
                                        <?php $i = 1; ?>
                                        <?php foreach ($tickets as $ticket): ?>
                                            <?php
                                            $status = (int) $ticket['status'];
                                            // کلاس رنگ پس‌زمینه برای وضعیت‌ها
                                            switch ($status) {
                                                case 0: // منتظر پاسخ
                                                    $statusClass = 'badge badge-warning'; // زرد
                                                    $statusText  = 'منتظر پاسخ پشتیبان';
                                                    break;
                                                case 1: // پاسخ داده شده
                                                    $statusClass = 'badge badge-primary'; // آبی
                                                    $statusText  = 'پاسخ داده شده';
                                                    break;
                                                case 2: // پاسخ کاربر
                                                    $statusClass = 'badge badge-info'; // آبی روشن
                                                    $statusText  = 'پاسخ کاربر';
                                                    break;
                                                case 3: // بسته شده
                                                    $statusClass = 'badge badge-secondary'; // خاکستری
                                                    $statusText  = 'بسته شده';
                                                    break;
                                                default:
                                                    $statusClass = 'badge badge-light';
                                                    $statusText  = 'نامشخص';
                                            }
                                            ?>
                                            <tr>
                                                <td><?= $i++ ?></td>

                                                <!-- عنوان با لینک به مشاهده تیکت -->
                                                <td>
                                                    <a href="<?= site_url('support/view/' . $ticket['id']) ?>">
                                                        <?= esc($ticket['subject']) ?>
                                                    </a>
                                                </td>

                                                <td><?= esc($ticket['category'] ?? '-') ?></td>

                                                <!-- وضعیت با پس‌زمینه رنگی -->
                                                <td>
                                                    <span class="<?= $statusClass ?>">
                                                        <?= esc($statusText) ?>
                                                    </span>
                                                </td>

                                                <td>
                                                    <?php if (function_exists('to_jalali')): ?>
                                                        <?= esc(to_jalali($ticket['created_at'])) ?>
                                                    <?php else: ?>
                                                        <?= esc($ticket['created_at']) ?>
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <?php if (function_exists('to_jalali')): ?>
                                                        <?= esc(to_jalali($ticket['updated_at'])) ?>
                                                    <?php else: ?>
                                                        <?= esc($ticket['updated_at']) ?>
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <a href="<?= site_url('support/view/' . $ticket['id']) ?>" class="btn btn-info btn-sm">
                                                        مشاهده تیکت
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                هیچ تیکتی ثبت نشده است.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div><!-- /.table-responsive -->
                    </div><!-- /.portlet-body -->
                </div><!-- /.portlet -->
            </div><!-- /.col-lg-12 -->

        </div><!-- /.row -->
    </div><!-- /#inner-content -->
</div><!-- /#page-content -->



 
		<?= $this->endSection() ?>
		