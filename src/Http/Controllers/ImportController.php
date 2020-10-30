<?php

namespace Bigmom\Excel\Http\Controllers;

use Bigmom\Excel\Facades\Import\XLSX;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function getIndex()
    {
        return view('excel::excel.import.index');
    }

    public function postImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx',
        ]);

        $data = XLSX::store($request->file('file'));

        if (!$data) {
            return redirect()
                ->back()
                ->with('error', 'error')
                ->with('message', 'File contains invalid tables.');
        }

        $data = collect($data);

        return view('excel::excel.import.confirm', compact('data'));
    }

    public function postConfirmImport(Request $request)
    {
        $request->validate([
            'file' => 'required|string|max:191',
        ]);

        $spreadsheet = XLSX::readFile($request->input('file'));

        $result = XLSX::import($spreadsheet);

        if ($result) {
            return redirect()
                ->route('excel.import.getIndex')
                ->with('success', 'success')
                ->with('message', 'Import successful.');
        } else {
            return redirect()
                ->route('excel.import.getIndex')
                ->with('error', 'error')
                ->with('message', 'File contains invalid tables.');
        }
    }
}
