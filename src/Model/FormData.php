<?php

namespace App\Model;

use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Email;

class FormData
{
    /**
     * @Assert\NotBlank()
     */
    public string $symbol;

    /**
     * @Assert\Range(maxPropertyPath="endDate", maxMessage="This value should be less or equal than End Date.")
     */
    public ?DateTimeInterface $startDate = null;

    /**
     * @Assert\Range(minPropertyPath="startDate", max="today", notInRangeMessage="This value should be between Start Date and the current date.")
     */
    public ?DateTimeInterface $endDate = null;

    /**
     * @Assert\Email(mode=Email::VALIDATION_MODE_HTML5)
     */
    public string $email;
}
