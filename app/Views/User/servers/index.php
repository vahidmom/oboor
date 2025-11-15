<?= $this->extend('User/layout/master') ?>
<?= $this->section('content') ?>

<style>
    .connections-platform .panel-heading {
        text-align: center;
        padding-top: 14px;
        padding-bottom: 12px;
        border-bottom: none;
        border-radius: 12px 12px 0 0;
        background: linear-gradient(135deg, #4f46e5, #6366f1);
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

    .connections-platform .panel-body {
        padding: 14px 16px 16px;
        background: radial-gradient(circle at top left, #f9fafb 0, #f3f4ff 28%, #ffffff 65%);
    }

    .connections-platform .service-meta {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: flex-start;
        gap: 8px;
        margin-bottom: 10px;
    }

    .connections-platform .service-meta .meta-text {
        font-size: 12px;
        color: #4b5563;
    }

    .connections-platform .service-meta strong {
        color: #111827;
        font-weight: 600;
    }

    .connections-platform .service-badge {
        font-size: 11px;
        border-radius: 999px;
        padding: 3px 9px;
        border: 1px dashed rgba(79, 70, 229, 0.33);
        background: rgba(79, 70, 229, 0.05);
        color: #4338ca;
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

    .server-address-text {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        padding: 3px 8px;
        border-radius: 999px;
        background: #f3f4ff;
        border: 1px solid #e0e7ff;
        font-size: 12px;
        display: inline-block;
        direction: ltr;
        unicode-bidi: bidi-override;
    }

    .server-country-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border-radius: 999px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        padding: 2px 9px;
        font-size: 11px;
        color: #4b5563;
    }

    .server-country-pill i {
        font-size: 13px;
        opacity: 0.7;
    }

    .copy-address-btn {
        margin-right: 4px;
        padding: 3px 7px;
        font-size: 11px;
        border-radius: 999px;
        border: 1px dashed #c7d2fe;
        background-color: #eef2ff;
        color: #3730a3;
        transition: background-color 0.15s ease, transform 0.1s ease, box-shadow 0.1s ease;
    }

    .copy-address-btn:hover {
        background-color: #e0e7ff;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.18);
    }

    .copy-address-btn i {
        pointer-events: none;
    }

    /* Modal-like copy alert */
    .copy-alert-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(2px);
    }

    .copy-alert-box {
        min-width: 220px;
        max-width: 320px;
        background: #ffffff;
        border-radius: 12px;
        padding: 12px 18px;
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.3);
        text-align: center;
        direction: rtl;
    }

    .copy-alert-box .copy-alert-title {
        font-weight: 700;
        margin-bottom: 4px;
        font-size: 14px;
    }

    .copy-alert-box .copy-alert-message {
        font-size: 13px;
        color: #4b5563;
    }

    .copy-alert-box.copy-success {
        border-top: 4px solid #22c55e;
    }

    .copy-alert-box.copy-danger {
        border-top: 4px solid #ef4444;
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
                        <li class="active">آدرس سرورها</li>
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
                                آدرس سرورها
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
                            در این صفحه می‌توانید آدرس سرورهای هر سرویس را مشاهده و به‌سادگی کپی کنید.
                        </p>

                        <?php
                        $services         = $services ?? [];
                        $serversByService = $serversByService ?? [];
                        ?>

                        <?php if (! empty($services)): ?>
                            <?php foreach ($services as $service): ?>
                                <div class="panel panel-default connections-platform">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <span class="platform-label">
                                                <i class="fa fa-server"></i>
                                                <?= esc($service['name']) ?>
                                            </span>
                                        </h4>
                                    </div>

                                    <div class="panel-body">

                                        <?php if (! empty($service['last_update_at']) || ! empty($service['last_update_reason'])): ?>
                                            <div class="service-meta">
                                                <div class="meta-text">
                                                    <strong>آخرین بروزرسانی این سرویس:</strong><br>
                                                    <?php if (! empty($service['last_update_at'])): ?>
                                                        <?php $lastUpdate = $service['last_update_at']; ?>
                                                        تاریخ:
                                                        <?php if (function_exists('to_jalali')): ?>
                                                            <?= esc(to_jalali($lastUpdate, 'Y/m/d H:i')) ?>
                                                        <?php else: ?>
                                                            <?= esc(date('Y/m/d H:i', strtotime($lastUpdate))) ?>
                                                        <?php endif; ?>
                                                        <br>
                                                    <?php endif; ?>

                                                    <?php if (! empty($service['last_update_reason'])): ?>
                                                        دلیل: <?= esc($service['last_update_reason']) ?>
                                                    <?php endif; ?>
                                                </div>

                                                <div>
                                                    <span class="service-badge">
                                                        شناسه سرویس:
                                                        <?= esc($service['slug'] ?? '-') ?>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (! empty($serversByService[$service['id']])): ?>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 50px;">#</th>
                                                            <th>نام سرور</th>

                                                            <?php if ($service['slug'] === 'openvpn'): ?>
                                                                <th style="width: 200px;">دانلود</th>
                                                            <?php else: ?>
                                                                <th>آدرس سرور</th>
                                                                <?php if ($service['slug'] === 'l2tp'): ?>
                                                                    <th>Secret Key</th>
                                                                <?php endif; ?>
                                                                <th style="width: 140px;">کشور</th>
                                                            <?php endif; ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $i = 1; ?>
                                                        <?php foreach ($serversByService[$service['id']] as $item): ?>
                                                            <?php
                                                            $server = $item['data'] ?? [];
                                                            $meta   = $item['meta'] ?? [];
                                                            ?>
                                                            <tr>
                                                                <td class="text-center"><?= $i++ ?></td>
                                                                <td><?= esc($server['name'] ?? '-') ?></td>

                                                                <?php if ($service['slug'] === 'openvpn'): ?>
                                                                    <td class="text-center">
                                                                        <?php if (! empty($meta['config_file_name'])): ?>
                                                                            <a class="btn btn-primary btn-sm"
                                                                               href="<?= site_url('users/servers/openvpn/download/' . $server['id']) ?>">
                                                                                <i class="fa fa-download"></i>
                                                                                دانلود فایل OpenVPN
                                                                            </a>
                                                                        <?php else: ?>
                                                                            <span class="text-muted">فایل تنظیم نشده</span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                <?php else: ?>
                                                                    <?php
                                                                    $hostname   = $server['hostname'] ?? '';
                                                                    $port       = $server['port'] ?? '';
                                                                    $addressStr = trim($hostname . ($port ? ':' . $port : ''));
                                                                    ?>
                                                                    <td>
                                                                        <?php if ($addressStr !== ''): ?>
                                                                            <span class="server-address-text">
                                                                                <?= esc($addressStr) ?>
                                                                            </span>
                                                                            <button type="button"
                                                                                    class="btn btn-default btn-xs copy-address-btn"
                                                                                    data-address="<?= esc($addressStr) ?>"
                                                                                    title="کپی آدرس">
                                                                                <i class="fa fa-copy"></i>
                                                                            </button>
                                                                        <?php else: ?>
                                                                            <span class="text-muted">-</span>
                                                                        <?php endif; ?>
                                                                    </td>

                                                                    <?php if ($service['slug'] === 'l2tp'): ?>
                                                                        <td>
                                                                            <?php if (! empty($meta['secret_key'])): ?>
                                                                                <code><?= esc($meta['secret_key']) ?></code>
                                                                            <?php else: ?>
                                                                                <span class="text-muted">تعریف نشده</span>
                                                                            <?php endif; ?>
                                                                        </td>
                                                                    <?php endif; ?>

                                                                    <td>
                                                                        <?php if (! empty($server['country'])): ?>
                                                                            <span class="server-country-pill">
                                                                                <i class="fa fa-globe"></i>
                                                                                <?= esc($server['country']) ?>
                                                                            </span>
                                                                        <?php else: ?>
                                                                            <span class="text-muted">-</span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                <?php endif; ?>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php else: ?>
                                            <p class="text-muted mb-0">
                                                برای این سرویس هنوز سروری ثبت نشده است.
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-info">
                                هنوز هیچ سرویسی ثبت نشده است.
                            </div>
                        <?php endif; ?>

                    </div><!-- /.portlet-body -->
                </div><!-- /.portlet -->
            </div><!-- /.col-lg-12 -->

        </div><!-- /.row -->
    </div><!-- /#inner-content -->
</div><!-- /#page-content -->

<script>
    function showCopyAlert(message, type = 'success') {
        var existing = document.querySelector('.copy-alert-overlay');
        if (existing) {
            existing.parentNode.removeChild(existing);
        }

        var overlay = document.createElement('div');
        overlay.className = 'copy-alert-overlay';

        var box = document.createElement('div');
        box.className = 'copy-alert-box ' + (type === 'success' ? 'copy-success' : 'copy-danger');

        var title = document.createElement('div');
        title.className = 'copy-alert-title';
        title.textContent = (type === 'success') ? 'موفقیت' : 'خطا';

        var msg = document.createElement('div');
        msg.className = 'copy-alert-message';
        msg.textContent = message;

        box.appendChild(title);
        box.appendChild(msg);
        overlay.appendChild(box);
        document.body.appendChild(overlay);

        setTimeout(function () {
            if (!overlay.parentNode) return;
            overlay.style.transition = 'opacity 0.25s ease';
            overlay.style.opacity = '0';
            setTimeout(function () {
                if (overlay.parentNode) {
                    overlay.parentNode.removeChild(overlay);
                }
            }, 250);
        }, 2000);
    }

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.copy-address-btn');
        if (!btn) {
            return;
        }

        const text = btn.getAttribute('data-address');
        if (!text) {
            return;
        }

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function () {
                showCopyAlert('آدرس سرور کپی شد ✅', 'success');
            }).catch(function () {
                fallbackCopyText(text);
            });
        } else {
            fallbackCopyText(text);
        }
    });

    function fallbackCopyText(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.left = '-9999px';
        document.body.appendChild(textarea);
        textarea.focus();
        textarea.select();

        try {
            if (document.execCommand('copy')) {
                showCopyAlert('آدرس سرور کپی شد ✅', 'success');
            } else {
                showCopyAlert('کپی آدرس انجام نشد ❌', 'danger');
            }
        } catch (err) {
            console.error('Copy failed', err);
            showCopyAlert('خطا در کپی آدرس ❌', 'danger');
        }

        document.body.removeChild(textarea);
    }
</script>

<?= $this->endSection() ?>
