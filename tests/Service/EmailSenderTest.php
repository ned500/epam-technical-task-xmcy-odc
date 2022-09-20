<?php

namespace App\Tests\Service;

use App\Model\CompanyInfoInterface;
use App\Model\FormData;
use App\Service\EmailSender;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class EmailSenderTest extends TestCase
{
    /**
     * @dataProvider notifyTestCases
     *
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testNotify(FormData $formData, array $companyInfo): void
    {
        $companies = $this->createStub(CompanyInfoInterface::class);
        $companies->method('getInfo')->willReturn($companyInfo);

        $textBody = $formData->startDate->format(EmailSender::DATE_FORMAT).' to '.$formData->endDate->format(EmailSender::DATE_FORMAT);
        $mailer = $this->createMock(MailerInterface::class);
        $mailer
            ->expects($this->once())
            ->method('send')
            ->with(
                $this->callback(static fn (Email $email) => (
                    $email->getTo()[0]->getAddress() === $formData->email
                    && $email->getSubject() === $companyInfo['Company Name']
                    && $email->getTextBody() === $textBody
                )),
            );

        $service = new EmailSender($mailer, $companies, 'valid@email.address', '');

        $service->notify($formData, []);
    }

    public function notifyTestCases(): array
    {
        $formData = new FormData();
        $formData->symbol = 'b';
        $formData->email = 'foo@bar.baz';
        $formData->startDate = new DateTimeImmutable('2002-1-1');
        $formData->endDate = new DateTimeImmutable('2002-2-3');

        $companyInfo = [
            'Company Name' => 'Test company name',
        ];

        return [[$formData, $companyInfo]];
    }
}
