<?php

namespace App\Http\Validation;

use App\Helpers\EncryptHelper;
use App\Helpers\ResponseHelper;
use App\Models\Cidade;
use App\Models\Estado;
use Illuminate\Support\Facades\Log;

class CronogramaArquivoValidation {

    public function __construct() {

    }

    public function validateFormat($file, $tipo) {
        $data = $file;
        $parts = explode(';', $data);
        $mime = str_replace('data:', '', $parts[0]);

        if ($mime !== 'text/csv') {
            return ResponseHelper::responseError([], 'Formato do arquivo inválido, por favor forneça um arquivo no formato .CSV', false);
        }

        if (strpos($file, 'data:text/csv;base64,') === 0) {
            $base64 = substr($file, strlen('data:text/csv;base64,'));
            $csvData = base64_decode($base64);
            if ($csvData == false || !$this->isValidCsv($csvData)) {
                return ResponseHelper::responseError([], 'Formato do arquivo inválido, ou arquivo corrompido!', false);
            }

            $lines = array_filter(str_getcsv($csvData, "\n"), function($line) {
                $fields = str_getcsv($line);
                $nonEmptyFields = array_filter($fields, 'strlen');
                return count($nonEmptyFields) > 0;
            });

            if (sizeof($lines) < 2) {
                return ResponseHelper::responseError([], 'Arquivo inválido, por favor forneça um arquivo com dados válidos!', false);
            }

            $validSize = $tipo == 'estadual' ? 2 : 4;

            $loopCount = 1;
            $separator = ',';
            $header = null;
            $duplicates = [];

            foreach ($lines as $line) {
                if ($loopCount == 1) {
                    $commaCount = substr_count($line, ',');
                    $semicolonCount = substr_count($line, ';');
                    $separator = $commaCount > $semicolonCount ? ',' : ';';
                }

                $bom = "\xEF\xBB\xBF";
                $line = ltrim($line, $bom);

                if (trim($line) == '' && $loopCount == 1) {
                    return ResponseHelper::responseError([], 'Arquivo inválido, por favor forneça um arquivo com dados válidos!', false);
                }

                $fields = array_filter(str_getcsv($line, $separator), 'strlen');

                if ($loopCount == 1) {
                    $header = array_filter($fields);
                    
                    if (count($header) < $validSize) {
                        return ResponseHelper::responseError([], 'Arquivo com cabeçalho inválido, por favor forneça um arquivo com dados válidos!', false);
                    }

                    $loopCount++;
                    continue;
                }

                if (!count($fields) && ($loopCount == 1 || $loopCount == 2)) {
                    return ResponseHelper::responseError([], 'Arquivo inválido, por favor forneça um arquivo com dados válidos!', false);
                }

                if (!count($fields)) {
                    $loopCount++;
                    continue;
                }

                if (count($fields) < $validSize) {
                    return ResponseHelper::responseError([], "Linha $loopCount está faltando dados, ajuste-a e tente novamente!", false);
                }

                if (!isset($fields[0])) {
                    return ResponseHelper::responseError([], "Linha $loopCount está faltando UF válida, ajuste-a e tente novamente!", false);
                }

                $uf = Estado::where('sigla', EncryptHelper::encrypt($fields[0]))->first();
                if (!isset($fields[0]) || strlen($fields[0]) !== 2 || !$uf) {
                    return ResponseHelper::responseError([], "Linha $loopCount contém UF inválida, ajuste-a e tente novamente!", false);
                }

                if ($tipo !== 'estadual') {
                    if (!isset($fields[2])) {
                        return ResponseHelper::responseError([], "Linha $loopCount está faltando código IBGE válido, ajuste-a e tente novamente!", false);
                    }

                    $cidade = Cidade::where('codigo_ibge', EncryptHelper::encrypt($fields[2]))->first();
                    if (strlen($fields[2]) !== 7 || !$cidade) {
                        return ResponseHelper::responseError([], "Linha $loopCount contém código IBGE inválido, ajuste-a e tente novamente!", false);
                    }

                    if (in_array($fields[2], $duplicates)) {
                        return ResponseHelper::responseError([], "Linha $loopCount contém código IBGE duplicado, ajuste-a e tente novamente!", false);
                    }

                    $duplicates[] = $fields[2];
                } else {
                    if (in_array($fields[0], $duplicates)) {
                        return ResponseHelper::responseError([], "Linha $loopCount contém UF duplicada, ajuste-a e tente novamente!", false);
                    }

                    $duplicates[] = $fields[0];
                }

                for ($i = ($validSize - 1); $i < count($fields); $i++) {
                    if (!isset($fields[$i]) && ($i <= (sizeof($header) + 1))) {
                        return ResponseHelper::responseError([], "Linha $loopCount contém campo vazio na posição ". ($i + 1) .", ajuste-a e tente novamente!", false);
                    }

                    $value = str_replace(',', '.', $fields[$i]);

                    if (!preg_match('/^-?\d+(\.\d+)?$/', $value)) {
                        return ResponseHelper::responseError([], "Linha $loopCount contém valor não numérico na posição ".($i + 1).", ajuste-a e tente novamente!", false);
                    }
                }

                $loopCount++;
            }
        } else {
            return ResponseHelper::responseError([], 'Formato do arquivo inválido, por favor forneça um arquivo no formato .CSV', false);
        }
    
        return ResponseHelper::responseSuccess([], '', false);
    }
    
    private function isValidCsv($data) {
        // Try to parse the data as CSV
        $csv = str_getcsv($data);
    
        // Check if the parsing was successful
        return $csv !== false;
    }

}
