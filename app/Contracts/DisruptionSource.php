<?php

namespace App\Contracts;

interface DisruptionSource
{
    /**
     * Fetch disruption data from an external API or RSS feed.
     *
     * @return array Array of associative arrays representing disruption data.
     */
    public function fetch(): array;
}
