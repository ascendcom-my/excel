<?php

namespace Bigmom\Excel\Traits;

use Bigmom\Auth\Facades\Permission;
use Illuminate\Support\Facades\Auth;

trait ResolveConfig
{
    public function resolveTableConfig($tableName, $config, $default = null)
    {
        return config("excel.tables.$tableName.$config", $default);
    }

    public function resolveLimitConfig($config)
    {
        return config("excel.limit-tables.$config");
    }

    public function checkIfTableIsAllowed($tableName)
    {
        if (!Permission::allows(Auth::guard('bigmom')->user(), 'excel-admin') && $this->resolveLimitConfig('should-limit')) {
            return in_array($tableName, $this->resolveLimitConfig('allowed-tables'));
        } else {
            return true;
        }
    }
}
