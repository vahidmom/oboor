<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseFilters
{
	
  
public array $aliases = [
    'csrf'           => \CodeIgniter\Filters\CSRF::class,
    'toolbar'        => \CodeIgniter\Filters\DebugToolbar::class,
    'honeypot'       => \CodeIgniter\Filters\Honeypot::class,
    'invalidchars'   => \CodeIgniter\Filters\InvalidChars::class,
    'secureheaders'  => \CodeIgniter\Filters\SecureHeaders::class,
    'cors'           => \CodeIgniter\Filters\Cors::class,
    'forcehttps'     => \CodeIgniter\Filters\ForceHTTPS::class,
    'pagecache'      => \CodeIgniter\Filters\PageCache::class,
    'performance'    => \CodeIgniter\Filters\PerformanceMetrics::class,
    'auth_user'      => \App\Filters\AuthFilter::class,
    'phone_verified' => \App\Filters\PhoneVerifiedFilter::class,
];

public array $globals = [
    'before' => [
        'csrf',
    ],
    'after'  => [
        'toolbar',
    ],
];


public array $required = [
    'before' => [
        // 'forcehttps',
        // 'pagecache',
    ],
    'after' => [
        // 'pagecache',
        // 'performance',
        // 'toolbar',
    ],
];

public array $filters = [
    'auth_user' => [
        'before' => ['users/*'],
    ],
    'phone_verified' => [                 // 👈 بیرون از auth_user
        'before' => ['users/*'],
    ],
];


}
