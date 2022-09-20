<?php

namespace App\Service;

use App\Model\CompanyInfoInterface;
use App\Model\FormData;
use App\Model\NotifierInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

final class EmailSender implements NotifierInterface
{
    public const DATE_FORMAT = 'Y-m-d';

    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly CompanyInfoInterface $companies,
        private readonly string $fromAddress,
        private readonly string $fromName,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function notify(FormData $form, array $history): void
    {
        $subject = $this->companies->getInfo($form->symbol)['Company Name'];
        $text = $form->startDate->format(self::DATE_FORMAT).' to '.$form->endDate->format(self::DATE_FORMAT);

        $email = (new Email())
            ->from(new Address($this->fromAddress, $this->fromName))
            ->to($form->email)
            ->subject($subject)
            ->text($text)
        ;
        $this->mailer->send($email);
    }
}
