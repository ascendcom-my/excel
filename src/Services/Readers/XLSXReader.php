<?php

namespace Bigmom\Excel\Services\Readers;

use Bigmom\Excel\Traits\ResolveConfig;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class XLSXReader
{
    use ResolveConfig;

    public function store($file)
    {
        $path = $file->store('excel/import');
        $filePath = Storage::path($path);
        if (!$this->checkFileTables($sheetNames = $this->readFile($filePath)->getSheetNames())) {
            Storage::delete($path);
            return false;
        };
        return compact('filePath', 'sheetNames');
    }

    public function import($spreadsheet)
    {
        $worksheetNames = $spreadsheet->getSheetNames();
        if (!$this->checkFileTables($worksheetNames)) {
            return false;
        }

        foreach ($worksheetNames as $worksheet_index => $tableName) {
            if ($this->checkIfTableIsAllowed($tableName)) {
                DB::table($tableName)->truncate();
                $columns = DB::getSchemaBuilder()->getColumnListing($tableName);

                // Remove columns that need ignoring.
                if ($ignore = $this->resolveTableConfig($tableName, 'ignore')) {
                    foreach ($columns as $column_index => $column) {
                        if (in_array($column, $ignore)) {
                            unset($columns[$column_index]);
                        }
                    }
                }

                $worksheet = $spreadsheet->getSheet($worksheet_index);

                // Map database columns to worksheet column indexes.
                $column_mapping = [];
                foreach ($columns as $column_index => $column) {
                    for ($i = 1; $i <= Coordinate::columnIndexFromString($worksheet->getHighestColumn()); $i++) {
                        if ($worksheet->getCellByColumnAndRow($i, 1)->getValue() === $column) {
                            $column_mapping[$column] = $i;
                        break;
                        }
                    }
                }

                // Insert into database.
                if ($modelString = $this->resolveTableConfig($tableName, 'model')) {
                    for ($i = 2; $i <= $worksheet->getHighestRow(); $i++) {
                        $model = new $modelString;
                        foreach ($column_mapping as $column => $index) {
                            if (array_key_exists($column, $mutator = $this->resolveTableConfig($tableName, 'mutator'))) {
                                $column = $mutator[$column];
                            }
                            $model->{$column} = $worksheet->getCellByColumnAndRow($index, $i)->getValue();
                        }
                        $model->save();
                    }
                } else {
                    $data = [];
                    for ($i = 2; $i <= $worksheet->getHighestRow(); $i++) {
                        $dataRow = [];
                        foreach ($column_mapping as $column => $index) {
                            $dataRow[$column] = $worksheet->getCellByColumnAndRow($index, $i)->getValue();
                        }
                        array_push($data, $dataRow);
                    }
                    DB::table($tableName)->insert($data);
                }
            }
        }

        return true;
    }

    public function readFile($filePath)
    {
        $reader = new Xlsx();
        return $reader->load($filePath);
    }

    protected function checkFileTables($worksheetNames)
    {
        foreach ($worksheetNames as $name) {
            if (!$this->checkIfTableIsAllowed($name)) return false;
        }
        return true;
    }
}