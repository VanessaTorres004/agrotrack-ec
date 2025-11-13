<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VacunaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ganado_id' => [
                'required',
                'integer',
                'exists:ganado,id',
            ],
            'nombre_vacuna' => [
                'required',
                'string',
                'max:255',
            ],
            'fecha_aplicacion' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'proxima_dosis' => [
                'nullable',
                'date',
                'after:fecha_aplicacion',
            ],
            'veterinario' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.]+$/',
            ],
            'lote_vacuna' => [
                'nullable',
                'string',
                'max:100',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'ganado_id.required' => 'Debe seleccionar un animal',
            'ganado_id.exists' => 'El animal seleccionado no existe',
            'nombre_vacuna.required' => 'El nombre de la vacuna es obligatorio',
            'fecha_aplicacion.required' => 'La fecha de aplicación es obligatoria',
            'fecha_aplicacion.before_or_equal' => 'La fecha de aplicación no puede ser futura',
            'proxima_dosis.after' => 'La próxima dosis debe ser posterior a la aplicación',
            'veterinario.regex' => 'El nombre del veterinario solo puede contener letras',
        ];
    }

    protected function prepareForValidation(): void
    {
        foreach (['nombre_vacuna', 'veterinario', 'lote_vacuna'] as $field) {
            if ($this->$field) {
                $this->merge([
                    $field => strip_tags(trim($this->$field)),
                ]);
            }
        }
    }
}

