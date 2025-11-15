<?= $this->extend('User/layout/master') ?>
<?= $this->section('content') ?>

<style>
    .connections-platform .panel {
        border-radius: 14px;
        border: none;
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.08);
        overflow: hidden;
        margin-bottom: 18px;
        background-color: #ffffff;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .connections-platform .panel:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.13);
    }

    .connections-platform .panel-heading {
        text-align: center;
        padding-top: 14px;
        padding-bottom: 12px;
        border-bottom: none;
        border-radius: 12px 12px 0 0;
        background: linear-gradient(135deg, #ec4899, #6366f1);
        color: #fff;
        position: relative;
        overflow: hidden;
    }

    .connections-platform .panel-heading::before {
        content: "";
        position: absolute;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
        top: -40px;
        inset-inline-start: -20px;
    }

    .connections-platform .panel-heading::after {
        content: "";
        position: absolute;
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.06);
        bottom: -35px;
        inset-inline-end: -10px;
    }

    .connections-platform .panel-title {
        position: relative;
        z-index: 1;
        margin: 0;
    }

    .connections-platform .platform-label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 18px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.16);
        color: #f9fafb;
        font-weight: 600;
        font-size: 13px;
        border: 1px solid rgba(255, 255, 255, 0.35);
        margin-bottom: 2px;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
    }

    .connections-platform .platform-label i {
        font-size: 14px;
        opacity: 0.9;
    }

    .connections-platform .panel-body {
        padding: 14px 16px 16px;
        background: radial-gradient(circle at top left, #fdf2ff 0, #f3f4ff 28%, #ffffff 65%);
    }

    .connections-platform .table {
        margin-bottom: 0;
        border-radius: 10px;
        overflow: hidden;
        background-color: #ffffff;
    }

    .connections-platform .table > thead > tr > th {
        background: #eef2ff;
        border-bottom: 1px solid #e5e7eb !important;
        font-size: 12px;
        color: #4b5563;
        vertical-align: middle;
        text-align: center;
    }

    .connections-platform .table > tbody > tr > td {
        vertical-align: middle;
        font-size: 13px;
        border-color: #e5e7eb;
    }

    .connections-platform .table-striped > tbody > tr:nth-of-type(odd) {
        --bs-table-accent-bg: #f9fafb;
    }

    .connections-platform .table-hover > tbody > tr:hover {
        background-color: #eef2ff;
    }

    .connection-group-title {
        font-weight: 600;
        color: #111827;
    }

    .connection-description {
        font-size: 12px;
        color: #6b7280;
        max-width: 420px;
    }

    .connection-description.empty {
        font-style: italic;
        color: #9ca3af;
    }

    .connection-index {
        text-align: center;
        font-size: 12px;
        color: #6b7280;
    }

    .connection-tutorial-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        border-radius: 999px;
        padding-inline: 14px;
    }

    .connection-tutorial-btn i {
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
                        <li class="active">دانلود کانکشن‌ها</li>
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
                                دانلود کانکشن‌ها
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

                        <p class="mb-3 text-muted">
                            ابتدا سیستم‌عامل خود را انتخاب کرده و سپس نوع کانکشن مورد نیاز را از زیرمجموعه‌های مربوط به آن انتخاب کنید.
                        </p>

                        <?php
                        // گروه‌ها را بر اساس پلتفرم دسته‌بندی می‌کنیم
                        $groupsByPlatform = [];
                        if (! empty($groups)) {
                            foreach ($groups as $g) {
                                $groupsByPlatform[$g['platform_id']][] = $g;
                            }
                        }
                        ?>

                        <?php if (! empty($platforms)): ?>
                            <?php foreach ($platforms as $platform): ?>
                                <div class="panel panel-default connections-platform">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <span class="platform-label">
                                                <i class="fa fa-desktop"></i>
                                                <?= esc($platform['name']) ?>
                                            </span>
                                        </h4>
                                    </div>

                                    <div class="panel-body">
                                        <?php if (! empty($groupsByPlatform[$platform['id']])): ?>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 50px;">#</th>
                                                            <th>زیرمجموعه</th>
                                                            <th>توضیحات</th>
                                                            <th style="width: 170px;">آموزش‌ها</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $i = 1; ?>
                                                        <?php foreach ($groupsByPlatform[$platform['id']] as $group): ?>
                                                            <tr>
                                                                <td class="connection-index"><?= $i++ ?></td>
                                                                <td>
                                                                    <span class="connection-group-title">
                                                                        <?= esc($group['title']) ?>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <?php if (! empty($group['description'])): ?>
                                                                        <span class="connection-description">
                                                                            <?= esc($group['description']) ?>
                                                                        </span>
                                                                    <?php else: ?>
                                                                        <span class="connection-description empty">
                                                                            توضیحی ثبت نشده است.
                                                                        </span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <a class="btn btn-info btn-sm connection-tutorial-btn"
                                                                       href="<?= site_url('connections/group/' . esc($group['slug'], 'url')) ?>">
                                                                        <i class="fa fa-book-open"></i>
                                                                        مشاهده آموزش‌ها
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php else: ?>
                                            <p class="text-muted mb-0">
                                                برای این پلتفرم هنوز زیرمجموعه‌ای ثبت نشده است.
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-info">
                                هنوز هیچ پلتفرمی ثبت نشده است.
                            </div>
                        <?php endif; ?>

                    </div><!-- /.portlet-body -->
                </div><!-- /.portlet -->
            </div><!-- /.col-lg-12 -->

        </div><!-- /.row -->
    </div><!-- /#inner-content -->
</div><!-- /#page-content -->

<?= $this->endSection() ?>
