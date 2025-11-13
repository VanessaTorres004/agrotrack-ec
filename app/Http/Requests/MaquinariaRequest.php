<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MaquinariaRequest extends FormRequest
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
            'identificador' => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-Z0-9\-]+$/',
                Rule::unique('maquinaria', 'identificador')
                    ->where('finca_id', $this->finca_id)
                    ->ignore($this->route('maquinaria')),
            ],
            'tipo' => [
                'required',
                'string',
                Rule::in(['tractor', 'cosechadora', 'sembradora', 'fumigadora', 'arado', 'rastra', 'otro']),
            ],
            'marca' => [
                'required',
                'string',
                'max:100',
            ],
            'modelo' => [
                'required',
                'string',
                'max:100',
            ],
            'anio_fabricacion' => [
                'nullable',
                'integer',
                'min:1950',
                'max:' . (date('Y') + 1),
            ],
            'horas_uso' => [
                'required',
                'integer',
                'min:0',
                'max:999999',
            ],
            'estado' => [
                'required',
                'string',
                Rule::in(['operativa', 'mantenimiento', 'fuera_servicio', 'reparacion']),
            ],
            'fecha_ultimo_servicio' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],
            'fecha_proximo_servicio' => [
                'nullable',
                'date',
                'after:fecha_ultimo_servicio',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'finca_id.required' => 'Debe seleccionar una finca',
            'finca_id.exists' => 'La finca seleccionada no existe',
            'identificador.required' => 'El identificador es obligatorio',
            'identificador.unique' => 'Ya existe una maquinaria con este identificador en la finca',
            'identificador.regex' => 'El identificador solo puede contener letras mayúsculas, números y guiones',
            'tipo.required' => 'El tipo de maquinaria es obligatorio',
            'tipo.in' => 'El tipo de maquinaria seleccionado no es válido',
            'horas_uso.required' => 'Las horas de uso son obligatorias',
            'horas_uso.min' => 'Las horas de uso no pueden ser negativas',
            'estado.in' => 'El estado seleccionado no es válido',
            'fecha_proximo_servicio.after' => 'La fecha del próximo servicio debe ser posterior al último servicio',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->identificador) {
            $this->merge([
                'identificador' => strtoupper(strip_tags(trim($this->identificador))),
            ]);
        }

        foreach (['marca', 'modelo'] as $field) {
            if ($this->$field) {
                $this->merge([
                    $field => strip_tags(trim($this->$field)),
                ]);
            }
        }
    }
}

