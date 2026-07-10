<?php

if (! function_exists('localizedRoute')) {
    /**
     * Generate a URL for a site-facing named route, honoring the current
     * (or given) locale. The site's configured default locale (Setting::default_locale)
     * uses the canonical unprefixed route names; every other locale uses the
     * mirrored 'loc.'-prefixed names registered in routes/web.php.
     */
    function localizedRoute(string $name, mixed $parameters = [], ?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();

        if ($locale === \App\Models\Setting::current()->default_locale) {
            return route($name, $parameters);
        }

        $parameters = is_array($parameters) ? $parameters : [$parameters];

        return route('loc.'.$name, array_merge(['locale' => $locale], $parameters));
    }
}

if (! function_exists('translatableRules')) {
    /**
     * Build ka/en/de validation rules for a translatable field. The site's
     * currently configured default locale (Setting::default_locale) is the
     * only one that can be marked required — it's the language admins are
     * guaranteed to fill in, not necessarily ka.
     */
    function translatableRules(string $field, array $rules, bool $requiredInDefaultLocale = false): array
    {
        $defaultLocale = \App\Models\Setting::current()->default_locale;
        $output = [];

        foreach (array_keys(\App\Models\Setting::LOCALES) as $locale) {
            $output["{$field}.{$locale}"] = $requiredInDefaultLocale && $locale === $defaultLocale
                ? array_merge(['required'], $rules)
                : array_merge(['nullable'], $rules);
        }

        return $output;
    }
}

if (! function_exists('productSummary')) {
    /**
     * A short plain-text preview for a product card: its excerpt if set,
     * otherwise a stripped/truncated version of its rich-text body. Block
     * tags are turned into spaces before stripping so paragraph breaks
     * don't glue adjacent sentences together (e.g. "...excessive.Take care").
     */
    function productSummary(\App\Models\Product $product, int $length = 160): string
    {
        if ($product->excerpt) {
            return $product->excerpt;
        }

        $body = html_entity_decode($product->body ?? '');
        $body = str_replace(['</p>', '</div>', '<br>', '<br/>', '<br />'], ' ', $body);
        $body = preg_replace('/\s+/', ' ', strip_tags($body));
        $body = trim($body);

        if (mb_strlen($body) <= $length) {
            return $body;
        }

        // Str::limit() cuts at the exact character, mid-word ("...Packi") — back up to the
        // last full word first so the preview reads as a clean, if incomplete, sentence.
        $truncated = mb_substr($body, 0, $length);
        $truncated = preg_replace('/\s+\S*$/u', '', $truncated) ?: $truncated;

        return $truncated.'…';
    }
}
