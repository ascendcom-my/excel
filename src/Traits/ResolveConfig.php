<?php

namespace Bigmom\Excel\Traits;

trait ResolveConfig
{
    public function resolveTableConfig($tableName, $config)
    {
        return config("excel.tables.$tableName.$config");
    }

    public function resolveLimitConfig($config)
    {
        return config("excel.limit-tables.$config");
    }

    public function checkIfTableIsAllowed($tableName)
    {
        if (!\Gate::forUser(\Auth::guard('excel')->user())->allows('excel-admin') && $this->resolveLimitConfig('should-limit')) {
            return in_array($tableName, $this->resolveLimitConfig('allowed-tables'));
        } else {
            return true;
        }
    }
}
