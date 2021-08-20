<?php

namespace Prokl\WebProfilierBundle;

class NonceGenerator
{
    public function generate(): string
    {
        return bin2hex(random_bytes(16));
    }
}