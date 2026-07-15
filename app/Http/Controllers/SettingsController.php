<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateEntrepriseRequest;
use App\Models\Entreprise;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        return view('settings.entreprise', [
            'entreprise' => Entreprise::current(),
        ]);
    }

    public function update(UpdateEntrepriseRequest $request): RedirectResponse
    {
        $entreprise = Entreprise::current();
        $data = $request->safe()->except('logo');

        if ($request->hasFile('logo')) {
            if ($entreprise->logo_path) {
                Storage::disk('public')->delete($entreprise->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('entreprise', 'public');
        }

        $entreprise->update($data);

        return back()->with('success', "Paramètres de l'entreprise mis à jour.");
    }
}
