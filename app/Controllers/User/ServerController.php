<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\ServiceModel;
use App\Models\ServerModel;
use App\Models\ServerMetaModel;

class ServerController extends BaseController
{
    protected ServiceModel $serviceModel;
    protected ServerModel $serverModel;
    protected ServerMetaModel $serverMetaModel;

    public function __construct()
    {
        $this->serviceModel    = new ServiceModel();
        $this->serverModel     = new ServerModel();
        $this->serverMetaModel = new ServerMetaModel();
    }

    /**
     * نمایش همه سرورها در یک صفحه
     * هر سرویس پنل خودش را دارد
     */
    public function index()
    {
        // همه سرویس‌های فعال
       $services = $this->serviceModel
    ->where('is_active', 1)
    ->orderBy('order_no', 'ASC')
    ->findAll();

        // همه سرورهای فعال
        $servers = $this->serverModel
            ->where('is_active', 1)
            ->orderBy('service_id', 'ASC')
            ->orderBy('order_no', 'ASC')
            ->findAll();

        // گروه‌بندی: برای هر سرویس، لیست سرورها + متا
        $serversByService = [];

        foreach ($servers as $server) {
            $meta = $this->serverMetaModel->getMetaForServer((int) $server['id']);

            $serversByService[$server['service_id']][] = [
                'data' => $server,
                'meta' => $meta,
            ];
        }

      $data = [
            'services'         => $services,
            'serversByService' => $serversByService,
            'title' => "دانلود کانکشن",
        ];

        return view('User/servers/index', $data);
    }
	
	
	public function downloadOpenvpn(int $id)
    {
        // پیدا کردن سرور
        $server = $this->serverModel->getActiveById($id);

        if (! $server) {
            return redirect()->back()->with('error', 'سرور مورد نظر یافت نشد.');
        }

        // چک کردن اینکه سرویسش OpenVPN است
        $service = $this->serviceModel->find($server['service_id']);
        if (! $service || $service['slug'] !== 'openvpn') {
            return redirect()->back()->with('error', 'دانلود فقط برای سرورهای OpenVPN مجاز است.');
        }

        // گرفتن متا
        $meta = $this->serverMetaModel->getMetaForServer((int) $server['id']);
        $fileName = $meta['config_file_name'] ?? null;

        if (! $fileName) {
            return redirect()->back()->with('error', 'فایل کانفیگ برای این سرور تنظیم نشده است.');
        }

        // مسیر کامل فایل در writable
        $filePath = WRITEPATH . 'uploads/openvpn/' . $fileName;

        if (! is_file($filePath)) {
            return redirect()->back()->with('error', 'فایل کانفیگ در سرور یافت نشد.');
        }

        // ارسال فایل برای دانلود
        return $this->response->download($filePath, null)->setFileName(
            $service['slug'] . '_' . $server['name'] . '.ovpn'
        );
    }
}
