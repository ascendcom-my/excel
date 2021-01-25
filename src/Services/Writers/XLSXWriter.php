<?php

namespace Bigmom\Excel\Services\Writers;

use Bigmom\Excel\Traits\ResolveConfig;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class XLSXWriter
{
    use ResolveConfig;

    public function write($tables)
    {
        $spreadsheet = new Spreadsheet();
        
        foreach ($tables as $table_index => $tableName) {
            if ($table_index != 0) {
                $spreadsheet->createSheet();
            } 
            $worksheet = $spreadsheet->getSheet($table_index);
            $worksheet->setTitle($tableName);
            $headers = DB::getSchemaBuilder()->getColumnListing($tableName);

            // Remove headers that need ignoring.
            if ($ignore = $this->resolveTableConfig($tableName, 'ignore')) {
                foreach ($headers as $header_index => $header) {
                    if (in_array($header, $ignore)) {
                        unset($headers[$header_index]);
                    }
                }
                $headers = array_values($headers); // Renumber headers
            }

            // Check if need models.
            $data = '';
            if ($model = $this->resolveTableConfig($tableName, 'model')) {
                $data = $model::get();
                foreach ($headers as $header_index => $header) {
                    if (array_key_exists($header, $accessor = $this->resolveTableConfig($tableName, 'accessor', []))) {
                        array_splice($headers, $header_index, 1, $accessor[$header]);
                    }
                }
            } else {
                $data = DB::table($tableName)->get();
            }

            // Write header row.
            foreach ($data as $item_index => $item) {
                foreach ($headers as $header_index => $header) {
                    $worksheet->setCellValueByColumnAndRow($header_index + 1, $item_index + 2, $item->{$header});
                }
            }

            // Resize columns.
            for ($i = 1; $i <= count($headers); $i++) {
                $worksheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
            }

            // Rename columns from accessor to actual column
            if ($model) {
                foreach ($headers as $header_index => $header) {
                    $flipped = array_flip($accessor);
                    if (array_key_exists($header, $flipped)) {
                        array_splice($headers, $header_index, 1, $flipped[$header]);
                    }
                }
            }

            // Write data.
            foreach ($headers as $header_index => $header) {
                $worksheet->setCellValueByColumnAndRow($header_index + 1, 1, $header);
            }
        }

        $writer = new Xlsx($spreadsheet);
        $writer->setOffice2003Compatibility(true);
        if (!file_exists(base_path('storage/app/excel/'))) {
            mkdir(base_path('storage/app/excel/'));
        }
        if (!file_exists(base_path('storage/app/excel/export'))) {
            mkdir(base_path('storage/app/excel/export'));
        }
        $filePath = 'excel/export/'.config('excel.export.title').'_'.\Carbon\Carbon::now()->format('Y-m-d_his').'.xlsx';
        $writer->save(base_path("storage/app/$filePath"));

        return $filePath;
    }
}