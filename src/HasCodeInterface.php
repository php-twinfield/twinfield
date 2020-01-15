<?php

namespace PhpTwinfield;

/**
 * Provides an interface for Objects with getCode functionality
 *
 */
interface HasCodeInterface
{
    /**
     * Gets the code from an object
     */
    public function getCode(): ?string;
    
}