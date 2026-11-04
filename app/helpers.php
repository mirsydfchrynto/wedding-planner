<?php

declare(strict_types=1);

if (! function_exists('format_idr')) {
    /**
     * Format numeric value as Indonesian Rupiah (Rp 350.000.000).
     */
    function format_idr(int|float|null $amount): string
    {
        $numeric = (float) ($amount ?? 0);
        $prefix = $numeric < 0 ? '-Rp ' : 'Rp ';

        return $prefix . number_format(abs($numeric), 0, ',', '.');
    }
}
