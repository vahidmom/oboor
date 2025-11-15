<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\ConnectionPlatformModel;
use App\Models\ConnectionGroupModel;
use App\Models\ConnectionTutorialModel;
use App\Models\ConnectionFileModel;

class ConnectionController extends BaseController
{
    protected $platformModel;
    protected $groupModel;
    protected $tutorialModel;
    protected $fileModel;

    public function __construct()
    {
        $this->platformModel = new ConnectionPlatformModel();
        $this->groupModel    = new ConnectionGroupModel();
        $this->tutorialModel = new ConnectionTutorialModel();
        $this->fileModel     = new ConnectionFileModel();
    }

    public function index()
    {
        // فیلتر قبلاً اجازه نداده کسی بدون دسترسی بیاد اینجا
        $user = current_user(); // فقط برای نمایش اسم/چیزهای نمایشی

        $platforms = $this->platformModel
            ->where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->findAll();

        $groups = $this->groupModel
            ->where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->findAll();

        return view('user/connections/index', [
            'platforms' => $platforms,
            'groups'    => $groups,
            'user'      => $user,
			'title' => 'پنل کاربری',
        ]);
    }

    public function tutorial(string $slug)
    {
        $user = current_user();

        $tutorial = $this->tutorialModel->findBySlug($slug);

        if (! $tutorial) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // اگر سطح دسترسی بر اساس user_level داری، اینجا فقط همونو چک کن
        $userLevel = session('user_level') ?? 0;
        if ($userLevel < (int) $tutorial['min_user_level']) {
            return redirect()->to('/')->with('error', 'دسترسی به این آموزش برای سطح کاربری شما فعال نیست.');
        }

        $this->tutorialModel->incrementViewCount((int) $tutorial['id']);

        $files = $this->fileModel->findActiveByTutorial((int) $tutorial['id']);

        return view('user/connections/tutorial', [
            'tutorial' => $tutorial,
            'files'    => $files,
            'user'     => $user,
			'title' => 'پنل کاربری',
        ]);
    }

    public function platform(string $slug)
    {
        $user = current_user();

        $platform = $this->platformModel
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->first();

        if (! $platform) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $groups = $this->groupModel
            ->where('platform_id', $platform['id'])
            ->where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->findAll();

        return view('user/connections/platform', [
            'platform' => $platform,
            'groups'   => $groups,
            'user'     => $user,
			'title' => 'پنل کاربری',
        ]);
    }

    public function group(string $slug)
    {
        $user = current_user();

        $group = $this->groupModel
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->first();

        if (! $group) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $tutorials = $this->tutorialModel
            ->where('group_id', $group['id'])
            ->where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->findAll();

        return view('user/connections/group', [
            'group'     => $group,
            'tutorials' => $tutorials,
            'user'      => $user,
			'title' => 'پنل کاربری',
        ]);
    }
}
