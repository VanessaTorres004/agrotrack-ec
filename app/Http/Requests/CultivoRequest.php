<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Finca;

class CultivoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'finca_id' => [
                'required',
                'integer',
                Rule::exists('fincas', 'id')->where(function ($query) {
                    $query->where('user_id', auth()->id());
                }),
            ],
            'nombre' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\sáéíóúÁÉÍÓÚñÑ\-]+$/',
            ],
            'variedad' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\sáéíóúÁÉÍÓÚñÑ\-]+$/',
            ],
            'hectareas' => [
                'required',
                'numeric',
                'min:0.01',
                'max:999999.99',
            ],
            'fecha_siembra' => [
                'required',
                'date',
                'before_or_equal:today',
                'after:' . now()->subYears(10)->format('Y-m-d'),
            ],
            'fecha_cosecha_estimada' => [
                'nullable',
                'date',
                'after:fecha_siembra',
            ],
            'notas' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['estado'] = [
                'required',
                'string',
                Rule::in(['activo', 'cosechado', 'inactivo']),
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'finca_id.required' => 'Debe seleccionar una finca',
            'finca_id.exists' => 'La finca seleccionada no existe o no le pertenece',
            'nombre.required' => 'El nombre del cultivo es obligatorio',
            'nombre.regex' => 'El nombre solo puede contener letras, números, espacios y guiones',
            'hectareas.required' => 'El área en hectáreas es obligatoria',
            'hectareas.min' => 'El área debe ser mayor a 0.01 hectáreas',
            'hectareas.max' => 'El área excede el límite permitido',
            'fecha_siembra.required' => 'La fecha de siembra es obligatoria',
            'fecha_siembra.before_or_equal' => 'La fecha de siembra no puede ser futura',
            'fecha_cosecha_estimada.after' => 'La fecha de cosecha debe ser posterior a la fecha de siembra',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Sanitizar inputs
        if ($this->nombre) {
            $this->merge([
                'nombre' => strip_tags(trim($this->nombre)),
            ]);
        }

        if ($this->variedad) {
            $this->merge([
                'variedad' => strip_tags(trim($this->variedad)),
            ]);
        }

        if ($this->notas) {
            $this->merge([
                'notas' => strip_tags(trim($this->notas)),
            ]);
        }
    }
}
