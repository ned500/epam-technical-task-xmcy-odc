<?php

namespace App\Model;

interface CompanyInfoInterface
{
    public function getInfo(string $symbol): ?array;
}
