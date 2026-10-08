<?php

namespace App\Models\Concerns;

trait HasTranslations
{
    /** @return list<string> */
    abstract protected function translatableAttributes(): array;

    public function getAttribute($key)
    {
        if (is_string($key) && in_array($key, $this->translatableAttributes(), true)) {
            $locale = app()->getLocale();
            $translations = parent::getAttribute('translations') ?? [];

            if (filled($translations[$locale][$key] ?? null)) {
                return $translations[$locale][$key];
            }

            if (filled($translations['en'][$key] ?? null)) {
                return $translations['en'][$key];
            }
        }

        return parent::getAttribute($key);
    }

    public function translationFor(string $attribute, string $locale, bool $fallback = false): mixed
    {
        $translations = parent::getAttribute('translations') ?? [];

        if (filled($translations[$locale][$attribute] ?? null)) {
            return $translations[$locale][$attribute];
        }

        if ($fallback && filled($translations['en'][$attribute] ?? null)) {
            return $translations['en'][$attribute];
        }

        if ($fallback && $locale === 'en') {
            return parent::getAttribute($attribute);
        }

        return null;
    }
}
