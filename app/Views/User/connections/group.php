<?= $this->extend('User/layout/master') ?>
<?= $this->section('content') ?>

<style>
    .connections-group-header {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 10px;
        align-items: center;
        margin-bottom: 14px;
    }

    .connections-group-title-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .connections-group-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 999px;
        background: rgba(79, 70, 229, 0.06);
        border: 1px solid rgba(129, 140, 248, 0.6);
        font-size: 12px;
        color: #4338ca;
        font-weight: 600;
    }

    .connections-group-badge i {
        font-size: 14px;
    }

    .connections-group-subtitle {
        font-size: 12px;
        color: #6b7280;
    }

    .connections-group-desc {
        font-size: 13px;
        color: #4b5563;
        background: #f9fafb;
        border-radius: 10px;
        padding: 8px 10px;
        border: 1px solid #e5e7eb;
        margin-bottom: 14px;
    }

    .connections-group-table {
        border-radius: 10px;
        overflow: hidden;
        background-color: #ffffff;
    }

    .connections-group-table > thead > tr > th {
        background: #eef2ff;
        border-bottom: 1px solid #e5e7eb !important;
        font-size: 12px;
        color: #4b5563;
        text-align: center;
        vertical-align: middle;
    }

    .connections-group-table > tbody > tr > td {
        font-size: 13px;
        vertical-align: middle;
        border-color: #e5e7eb;
    }

    .connections-group-table.table-striped > tbody > tr:nth-of-type(odd) {
        --bs-table-accent-bg: #f9fafb;
    }

    .connections-group-table.table-hover > tbody > tr:hover {
        background-color: #eef2ff;
    }

    .connections-group-index {
        text-align: center;
        font-size: 12px;
        color: #6b7280;
    }

    .connections-group-title-link a {
        font-weight: 600;
        color: #1f2937;
        text-decoration: none;
    }

    .connections-group-title-link a:hover {
        color: #4f46e5;
        text-decoration: underline;
    }

    .connections-group-short-desc {
        font-size: 12px;
        color: #6b7280;
        max-width: 420px;
    }

    .connections-group-short-desc.empty {
        font-style: italic;
        color: #9ca3af;
    }

    .connections-group-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        border-radius: 999px;
        padding-inline: 14px;
    }

    .connections-group-btn i {
        font-size: 13px;
    }

    .breadcrumb-box.shadow {
        border-radius: 12px;
    }
</style>

<div id="page-content">
    <div id="inner-content">
        <div class="row">

            <!-- BEGIN BREADCRUMB -->
            <div class="col-md-12">
                <div class="breadcrumb-box shadow">
                    <ul class="breadcrumb">
                        <li><a href="<?= site_url('users/dashboard') ?>">پیشخوان</a></li>
                        <li><a href="<?= site_url('connections') ?>">دانلود کانکشن‌ها</a></li>
                        <li class="active"><?= esc($group['title']) ?></li>
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
                                <?= esc($group['title']) ?> - آموزش‌ها
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

                        <div class="connections-group-header">
                            <div class="connections-group-title-wrap">
                                <span class="connections-group-badge">
                                    <i class="fa fa-plug"></i>
                                    زیرمجموعه انتخاب‌شده
                                </span>
                                <span class="connections-group-subtitle">
                                    در این بخش می‌توانید آموزش‌های مرتبط با این نوع کانکشن را مشاهده و دانلود کنید.
                                </span>
                            </div>
                        </div>

                        <?php if (! empty($group['description'])): ?>
                            <div class="connections-group-desc">
                                <?= esc($group['description']) ?>
                            </div>
                        <?php endif; ?>

                        <?php if (! empty($tutorials)): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped connections-group-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th>عنوان آموزش</th>
                                            <th>توضیح کوتاه</th>
                                            <th style="width: 160px;">عملیات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        <?php foreach ($tutorials as $tutorial): ?>
                                            <tr>
                                                <td class="connections-group-index"><?= $i++ ?></td>
                                                <td class="connections-group-title-link">
                                                    <a href="<?= site_url('connections/tutorial/' . esc($tutorial['slug'], 'url')) ?>">
                                                        <?= esc($tutorial['title']) ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <?php if (! empty($tutorial['short_description'])): ?>
                                                        <span class="connections-group-short-desc">
                                                            <?= esc($tutorial['short_description']) ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="connections-group-short-desc empty">
                                                            توضیحی برای این آموزش ثبت نشده است.
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <a class="btn btn-info btn-sm connections-group-btn"
                                                       href="<?= site_url('connections/tutorial/' . esc($tutorial['slug'], 'url')) ?>">
                                                        <i class="fa fa-book-open"></i>
                                                        مشاهده و دانلود
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                برای این زیرمجموعه هنوز آموزشی ثبت نشده است.
                            </div>
                        <?php endif; ?>

                    </div><!-- /.portlet-body -->
                </div><!-- /.portlet -->
            </div><!-- /.col-lg-12 -->

        </div><!-- /.row -->
    </div><!-- /#inner-content -->
</div><!-- /#page-content -->

<?= $this->endSection() ?>
