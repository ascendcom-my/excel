<?php

namespace Bigmom\Excel\Facades\Export;

use Illuminate\Support\Facades\Facade;

class XLSX extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'excel.export.xlsx';
    }
}
