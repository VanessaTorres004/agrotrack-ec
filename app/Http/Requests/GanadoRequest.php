<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GanadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'finca_id' => [
                'required',
                'integer',
                Rule::exists('fincas', 'id')->where(function ($query) {
                    $query->where('user_id', auth()->id());
                }),
            ],
            'identificacion' => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-Z0-9\-]+$/',
                Rule::unique('ganado', 'identificacion')
                    ->where('finca_id', $this->finca_id)
                    ->ignore($this->route('ganado')),
            ],
            'tipo' => [
                'required',
                'string',
                Rule::in(['vacuno', 'porcino', 'ovino', 'caprino', 'aviar', 'equino']),
            ],
            'raza' => [
                'nullable',
                'string',
                'max:100',
            ],
            'fecha_nacimiento' => [
                'required',
                'date',
                'before_or_equal:today',
                'after:' . now()->subYears(30)->format('Y-m-d'),
            ],
            'peso_actual' => [
                'required',
                'numeric',
                'min:0.1',
                'max:10000',
            ],
            'estado_salud' => [
                'required',
                'string',
                Rule::in(['saludable', 'enfermo', 'en_tratamiento', 'cuarentena']),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'finca_id.required' => 'Debe seleccionar una finca',
            'finca_id.exists' => 'La finca seleccionada no existe',
            'identificacion.required' => 'La identificación es obligatoria',
            'identificacion.unique' => 'Ya existe un animal con esta identificación en la finca',
            'identificacion.regex' => 'La identificación solo puede contener letras mayúsculas, números y guiones',
            'tipo.required' => 'El tipo de ganado es obligatorio',
            'tipo.in' => 'El tipo de ganado seleccionado no es válido',
            'fecha_nacimiento.before_or_equal' => 'La fecha de nacimiento no puede ser futura',
            'peso_actual.min' => 'El peso debe ser mayor a 0',
            'estado_salud.in' => 'El estado de salud seleccionado no es válido',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->identificacion) {
            $this->merge([
                'identificacion' => strtoupper(strip_tags(trim($this->identificacion))),
            ]);
        }
    }
}

