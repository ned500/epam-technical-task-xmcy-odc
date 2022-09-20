<?php

namespace App\Model;

interface NotifierInterface
{
    public function notify(FormData $form, array $history): void;
}
