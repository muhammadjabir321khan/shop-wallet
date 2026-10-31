<?php

namespace App\Traits\System;

use Illuminate\Support\Str;

trait ActivationTrait
{
    public function isLocal(): bool
    {
        return true;
    }

    public function getDomain(): string
    {
        return str_replace(["http://", "https://", "www."], "", url('/'));
    }

    public function getSystemAddonCacheKey(string|null $app = 'default'): string
    {
        $appName = env('APP_NAME').'_cache';
        return str_replace('-', '_', Str::slug($appName.'cache_system_addons_for_' . $app . '_' . $this->getDomain()));
    }

    public function getAddonsConfig(): array
    {
        if (file_exists(base_path('config/system-addons.php'))) {
            return include(base_path('config/system-addons.php'));
        }

        $apps = ['admin_panel', 'vendor_app', 'deliveryman_app', 'react_web'];
        $appConfig = [];
        foreach ($apps as $app) {
            $appConfig[$app] = [
                "active"        => "1",
                "name"          => "",
                "email"         => "",
                "username"      => "",
                "purchase_key"  => "",
                "software_id"   => "",
                "domain"        => "",
                "software_type" => $app === 'admin_panel' ? "product" : "addon",
            ];
        }
        return $appConfig;
    }

    public function getCacheTimeoutByDays(int $days = 3): int
    {
        return 999999;
    }

    public function getRequestConfig(string|null $name = null, string|null $email = null, string|null $username = null, string|null $purchaseKey = null, string|null $softwareId = null, string|null $softwareType = null): array
    {
        return [
            "active"        => 1,
            "name"          => trim($name ?? ''),
            "email"         => trim($email ?? ''),
            "username"      => trim($username ?? 'ACTIVE'),
            "purchase_key"  => $purchaseKey ?? 'ACTIVE',
            "software_id"   => $softwareId ?? SOFTWARE_ID,
            "domain"        => $this->getDomain(),
            "software_type" => $softwareType,
        ];
    }

    public function checkActivationCache(string|null $app): bool
    {
        return true;
    }

    public function updateActivationConfig($app, $response): void
    {
        if ('admin.business-settings.addon-activation.index' === \Illuminate\Support\Facades\Route::currentRouteName()) {
            return;
        }
        $config = $this->getAddonsConfig();
        $config[$app] = $response;
        file_put_contents(base_path('config/system-addons.php'), "<?php return " . var_export($config, true) . ";");
    }
}