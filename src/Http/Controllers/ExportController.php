<?php

namespace Bigmom\Excel\Http\Controllers;

use Bigmom\Auth\Facades\Permission;
use Bigmom\Excel\Facades\Export\XLSX;
use Bigmom\Excel\Traits\ResolveConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExportController extends Controller
{
    use ResolveConfig;

    public function getIndex()
    {
        $tables = DB::select('SHOW TABLES');
        if ($this->resolveLimitConfig('should-limit')) {
            foreach ($tables as $index => $table) {
                if (!in_array($table->{'Tables_in_'.config('excel.database')}, $this->resolveLimitConfig('allowed-tables'))) {
                    unset($tables[$index]);
                }
            }
            $tables = array_values($tables);
        }
        return view('bigmom-excel::excel.export.index', compact('tables'));
    }

    public function getAdminIndex()
    {
        $tables = DB::select('SHOW TABLES');

        return view('bigmom-excel::excel.export.index', compact('tables'));
    }

    public function download(Request $request)
    {
        if (!Permission::allows(Auth::guard('bigmom')->user(), 'excel-admin') && $this->resolveLimitConfig('should-limit')) {
            $allowedTables = implode(',', $this->resolveLimitConfig('allowed-tables'));
        } else {
            $allowedTables = DB::select('SHOW TABLES');
            foreach ($allowedTables as $index => $table) {
                $allowedTables[$index] = $table->{'Tables_in_'.config('excel.database')};
            }
            $allowedTables = implode(',', $allowedTables);
        }

        $request->validate([
            'table' => 'required|array',
            'table.*' => "required|string|max:191|in:$allowedTables",
        ]);
        
        $filePath = XLSX::write($request->input('table'));

        return Storage::download($filePath);
    }
}
