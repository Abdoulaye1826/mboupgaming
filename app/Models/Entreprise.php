<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Entreprise extends Model
{
    protected $fillable = [
        'nom', 'slogan', 'logo_path', 'email', 'telephone', 'whatsapp',
        'adresse_ligne1', 'adresse_ligne2', 'ville', 'pays',
        'ninea', 'rccm', 'couleur_primaire', 'couleur_secondaire',
        'conditions_vente',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('entreprise.current'));
        static::deleted(fn () => Cache::forget('entreprise.current'));
    }

    /**
     * Point d'entrée unique : toujours la ligne id=1. Mise en cache
     * indéfinie — invalidée automatiquement à chaque save().
     */
    public static function current(): self
    {
        return Cache::rememberForever('entreprise.current', function () {
            $entreprise = static::query()->firstOrCreate(['id' => 1], [
                'nom' => config('company.name', 'Mon Entreprise'),
                'email' => config('company.email'),
                'telephone' => config('company.phone'),
                'whatsapp' => config('company.whatsapp_number'),
                'adresse_ligne1' => config('company.address_line1'),
                'adresse_ligne2' => config('company.address_line2'),
                'ninea' => config('company.ninea'),
                'rccm' => config('company.rc'),
            ]);

            // fresh() : à la création, les colonnes non passées explicitement
            // (couleur_primaire, couleur_secondaire, pays) reçoivent leur
            // valeur par défaut côté MySQL, que l'objet retourné par
            // firstOrCreate() ne reflète pas automatiquement — sans ça, ces
            // valeurs par défaut restaient vides une fois mises en cache.
            return $entreprise->wasRecentlyCreated ? $entreprise->fresh() : $entreprise;
        });
    }

    public function getLogoUrlAttribute(): string
    {
        if ($this->logo_path && Storage::disk('public')->exists($this->logo_path)) {
            return Storage::disk('public')->url($this->logo_path);
        }

        return asset('images/logo.jpeg');
    }

    /**
     * DomPDF ne charge pas les images distantes par défaut : on lui fournit
     * le logo encodé en base64 (comme c'était déjà fait pour l'ancien
     * images/logo.jpeg codé en dur).
     */
    public function getLogoBase64Attribute(): ?string
    {
        $path = $this->logo_path && Storage::disk('public')->exists($this->logo_path)
            ? Storage::disk('public')->path($this->logo_path)
            : public_path('images/logo.jpeg');

        if (! is_file($path)) {
            return null;
        }

        $mime = str_ends_with(strtolower($path), '.png') ? 'image/png' : 'image/jpeg';

        return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
    }

    public function getAdresseCompleteAttribute(): string
    {
        return collect([$this->adresse_ligne1, $this->adresse_ligne2, $this->ville])
            ->filter()
            ->implode(', ');
    }

    /**
     * Version assombrie (~20%) d'une couleur donnée, utilisée pour les
     * survols/dégradés sans avoir à stocker une troisième couleur.
     */
    public function darken(?string $hex, float $factor = 0.8): string
    {
        [$r, $g, $b] = $this->hexToRgb($hex);

        return sprintf('#%02X%02X%02X', $r * $factor, $g * $factor, $b * $factor);
    }

    public function rgb(?string $hex): string
    {
        return implode(', ', $this->hexToRgb($hex));
    }

    private function hexToRgb(?string $hex): array
    {
        $hex = ltrim($hex ?? '', '#');

        if (strlen($hex) !== 6) {
            $hex = '153BFF';
        }

        return array_map('hexdec', str_split($hex, 2));
    }
}
