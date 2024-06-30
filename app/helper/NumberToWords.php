<?php

namespace App\Helper;

use NumberFormatter;

class NumberToWords
{
    function toWords( $number )

    {
        $nf = new NumberFormatter('fr', NumberFormatter::SPELLOUT);
        return strtoupper($nf->format($number) . ' Dirhams');
    }
}
