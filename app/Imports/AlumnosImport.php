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
    private $cursoId;

    public function __construct($cursoAcademicoId, $cursoId = null)
    {
        $this->cursoAcademicoId = $cursoAcademicoId;
        $this->cursoId = $cursoId;
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
            'input_encoding' => 'ISO-8859-1',
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

            // Ensure Name is present (Index 0)
            if (!isset($row[0]) || empty(trim($row[0]))) {
                Log::warning("Row #{$index} skipped: Name missing.");
                continue;
            }
            $nombreCompleto = trim($row[0]);

            // Ensure DNI is present (Index 1)
            if (!isset($row[1]) || empty(trim($row[1]))) {
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

            // DNI Encriptado Logic
            $dniEncriptado = $dni; // Default to DNI
            if (strlen($dni) >= 9) {
                $dniEncriptado = substr($dni, 0, 2) . '**' . substr($dni, 4, 2) . '**' . substr($dni, 8);
            }

            // Email Generation
            $email = null;
            $parts = explode(' ', strtolower($this->removeAccents($nombreCompleto)));
            if (count($parts) > 0) {
                 $baseEmail = $parts[0] . (isset($parts[1]) ? '.' . $parts[1] : '');
                 $baseEmail = preg_replace('/[^a-z0-9\.]/', '', $baseEmail);
                 $email = $baseEmail . '@alu.medac.es';
                 
                 $counter = 1;
                 while (Alumno::where('email', $email)->exists()) {
                     $email = $baseEmail . $counter . '@alu.medac.es';
                     $counter++;
                 }
            }
            
            if (!$email) {
                 Log::warning("Row #{$index} skipped: Could not generate email for '$nombreCompleto'.");
                 continue;
            }

            try {
                Alumno::create([
                    'nombre_completo'    => $nombreCompleto,
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
                    'empresa_id'         => $empresaId,
                    'curso_academico_id' => $this->cursoAcademicoId,
                    'curso_id'           => $this->cursoId,
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
