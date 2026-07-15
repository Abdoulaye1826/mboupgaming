<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEntrepriseRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Déjà filtré par le middleware role:admin sur la route.
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'slogan' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'email' => ['nullable', 'email', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'adresse_ligne1' => ['nullable', 'string', 'max:255'],
            'adresse_ligne2' => ['nullable', 'string', 'max:255'],
            'ville' => ['nullable', 'string', 'max:120'],
            'pays' => ['nullable', 'string', 'max:120'],
            'ninea' => ['nullable', 'string', 'max:60'],
            'rccm' => ['nullable', 'string', 'max:60'],
            'couleur_primaire' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'couleur_secondaire' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'conditions_vente' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
