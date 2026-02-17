<?php

namespace App\Imports;

use App\Models\Alumno;
use App\Models\Empresa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Illuminate\Support\Facades\Log;

class AlumnosImport implements ToCollection, WithStartRow, WithCustomCsvSettings
{
    private $cursoAcademicoId;

    public function __construct($cursoAcademicoId)
    {
        $this->cursoAcademicoId = $cursoAcademicoId;
    }

    /**
     * Start reading from row 2 (skipping header).
     */
    public function startRow(): int
    {
        return 2;
    }

    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'UTF-8',
            'delimiter' => ';',
        ];
    }

    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        Log::info('Start importing collection. Total rows: ' . $rows->count());

        foreach ($rows as $index => $row) {
            // Log raw row to debug
            Log::info("Processing Row #{$index}: " . json_encode($row));

            // Fallback for single column delimiter issue
            if (count($row) === 1 && isset($row[0])) {
                 $split = explode(';', $row[0]);
                 if (count($split) > 1) {
                     $row = collect($split); // Convert back to collection to access via index
                     Log::info("Row #{$index} manually split by semicolon.", $row->toArray());
                 }
            }

            // Ensure we have enough columns for DNI (index 1)
            if (!isset($row[1]) || empty($row[1])) {
                Log::warning("Row #{$index} skipped: DNI missing.");
                continue;
            }

            $dni = trim($row[1]);

            // Skip duplicates
            if (Alumno::where('dni', $dni)->exists()) {
                Log::info("Row #{$index} skipped: Duplicate DNI ($dni).");
                continue;
            }

            // Find Empresa (Index 11)
            $empresaId = null;
            if (isset($row[11]) && !empty($row[11])) {
                $nombreEmpresa = trim($row[11]);
                $empresa = Empresa::where('nombre', $nombreEmpresa)->first();
                if ($empresa) {
                    $empresaId = $empresa->id;
                } else {
                    Log::info("Row #{$index}: Empresa '$nombreEmpresa' not found.");
                }
            }

            // Nota Media (Index 10) - Ignored, auto-calculated
            // $notaMedia = null;
            // if (isset($row[10])) { ... }

            // DNI Encriptado Logic
            $dniEncriptado = $dni;
            if (strlen($dni) >= 9) {
                $dniEncriptado = substr($dni, 0, 2) . '**' . substr($dni, 4, 2) . '**' . substr($dni, 8);
            }

            // Email Generation
            $email = null;
            if (isset($row[0])) { // nombre_completo
                 $parts = explode(' ', strtolower($this->removeAccents($row[0])));
                 $baseEmail = $parts[0] . (isset($parts[1]) ? '.' . $parts[1] : '');
                 
                 // Clean base email
                 $baseEmail = preg_replace('/[^a-z0-9\.]/', '', $baseEmail);

                 // Ensure uniqueness
                 $email = $baseEmail . '@alu.medac.es';
                 $counter = 1;
                 while (Alumno::where('email', $email)->exists()) {
                     $email = $baseEmail . $counter . '@alu.medac.es';
                     $counter++;
                 }
            }

            try {
                Alumno::create([
                    'nombre_completo'    => $row[0] ?? null,
                    'dni'                => $dni,
                    'dni_encriptado'     => $dniEncriptado,
                    'email'              => $email,
                    'nota_1'             => $this->parseGrade($row[2] ?? null),
                    'nota_2'             => $this->parseGrade($row[3] ?? null),
                    'nota_3'             => $this->parseGrade($row[4] ?? null),
                    'nota_4'             => $this->parseGrade($row[5] ?? null),
                    'nota_5'             => $this->parseGrade($row[6] ?? null),
                    'nota_6'             => $this->parseGrade($row[7] ?? null),
                    'nota_7'             => $this->parseGrade($row[8] ?? null),
                    'nota_8'             => $this->parseGrade($row[9] ?? null),
                    // 'nota_media'       => calculado en el modelo
                    'empresa_id'         => $empresaId,
                    'curso_academico_id' => $this->cursoAcademicoId,
                ]);
                Log::info("Row #{$index}: Student '$dni' created successfully.");
            } catch (\Exception $e) {
                Log::error("Row #{$index} Failed: " . $e->getMessage());
            }
        }
        
        Log::info('Import finished.');
    }
    /**
     * Parse grade string to float.
     */
    private function parseGrade($value)
    {
        if (is_null($value) || $value === '') {
            return null;
        }
        $value = str_replace([',', "'"], '.', $value);
        return is_numeric($value) ? (float) $value : null;
    }

    private function removeAccents($string) {
        $accents = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
            'ñ' => 'n', 'Ñ' => 'N'
        ];
        return strtr($string, $accents);
    }
}
