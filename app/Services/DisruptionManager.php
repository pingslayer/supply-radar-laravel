<?php

namespace App\Services;

use App\Contracts\DisruptionSource;

class DisruptionManager
{
    /** @var DisruptionSource[] */
    protected array $sources = [];

    /**
     * Add a source to the manager.
     */
    public function addSource(DisruptionSource $source): void
    {
        $this->sources[] = $source;
    }

    /**
     * Loop through all sources and fetch all disruptions.
     */
    public function fetchAll(): array
    {
        $allDisruptions = [];

        foreach ($this->sources as $source) {
            $results = $source->fetch();
            $allDisruptions = array_merge($allDisruptions, $results);
        }

        return $allDisruptions;
    }
}
