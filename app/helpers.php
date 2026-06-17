<?php

if (! function_exists('format_pkr')) {
    /**
     * Format an amount in Pakistani Rupees.
     */
    function format_pkr($amount, int $decimals = 0): string
    {
        if ($amount === null || $amount === '') {
            return '—';
        }

        return 'Rs. '.number_format((float) $amount, $decimals);
    }
}
