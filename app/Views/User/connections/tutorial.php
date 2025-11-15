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
                        <li><a href="<?= site_url('connections') ?>">دانلود کانکشن‌ها</a></li>
                        <?php if (isset($group) && ! empty($group)): ?>
                            <li>
                                <a href="<?= site_url('connections/group/' . esc($group['slug'], 'url')) ?>">
                                    <?= esc($group['title']) ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="active"><?= esc($tutorial['title']) ?></li>
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
                                <?= esc($tutorial['title']) ?>
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

                        <?php if (! empty($tutorial['short_description'])): ?>
                            <p class="mb-3">
                                <?= esc($tutorial['short_description']) ?>
                            </p>
                        <?php endif; ?>

                        <!-- ویدیو (در صورت وجود) -->
                        <?php if (! empty($tutorial['video_embed'])): ?>
                            <div class="mb-3">
                                <?= $tutorial['video_embed'] ?>
                            </div>
                        <?php elseif (! empty($tutorial['video_path'])): ?>
                            <div class="mb-3">
                                <video controls width="100%">
                                    <source src="<?= site_url('video/' . $tutorial['id']) ?>" type="video/mp4">
                                    مرورگر شما از پخش ویدیو پشتیبانی نمی‌کند.
                                </video>
                            </div>
                        <?php endif; ?>

                        <!-- محتوای آموزش -->
                        <div class="well">
                            <?= $tutorial['content'] ?>
                            <!-- عمداً esc نکردم چون محتوا رو خودت کنترل می‌کنی و HTML می‌نویسی -->
                        </div>

                        <!-- فایل‌های دانلود -->
                        <h4 class="mt-3">فایل‌های دانلود</h4>

                        <?php if (! empty($files)): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th>عنوان فایل</th>
                                            <th>نسخه / توضیحات</th>
                                            <th style="width: 150px;">دانلود</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        <?php foreach ($files as $file): ?>
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td><?= esc($file['title']) ?></td>
                                                <td>
                                                    <?php if (! empty($file['version'])): ?>
                                                        نسخه: <?= esc($file['version']) ?>
                                                        <br>
                                                    <?php endif; ?>
                                                    <?php if (! empty($file['change_log'])): ?>
                                                        <?= esc($file['change_log']) ?>
                                                        <br>
                                                    <?php endif; ?>
                                                    تعداد دانلود: <?= (int) $file['download_count'] ?>
                                                </td>
                                                <td>
                                                    <a class="btn btn-success btn-sm"
                                                       href="<?= site_url('download/' . $file['id']) ?>">
                                                        دانلود
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                برای این آموزش هنوز فایلی ثبت نشده است.
                            </div>
                        <?php endif; ?>

                    </div><!-- /.portlet-body -->
                </div><!-- /.portlet -->
            </div><!-- /.col-lg-12 -->

        </div><!-- /.row -->
    </div><!-- /#inner-content -->
</div><!-- /#page-content -->

<?= $this->endSection() ?>
