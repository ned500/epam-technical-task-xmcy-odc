<?php

namespace App\Controller;

use App\Exception\WebAccessException;
use App\Form\MainType;
use App\Model\CompanyOptionsInterface;
use App\Model\FormData;
use App\Model\NotifierInterface;
use App\Service\History;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="root")
     *
     * @Template
     *
     * @noinspection VirtualTypeCheckInspection
     */
    public function indexAction(Request $request, CompanyOptionsInterface $company, History $history, NotifierInterface $email): array
    {
        $form = $this->createForm(MainType::class, null, ['companyOptions' => $company]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var FormData $formData */
            $formData = $form->getData();

            // Fetch data
            try {
                $historyData = $history->getHistory($formData->symbol, $formData->startDate, $formData->endDate);
            } catch (WebAccessException $exception) {
                $form->addError(new FormError('Fetch data was unsuccessful: '.$exception->getMessage()));
            }

            // Send notification
            try {
                if (isset($historyData)) {
                    $email->notify($formData, $historyData);
                }
            } catch (Exception $exception) {
                $form->addError(new FormError('Email send was unsuccessful: '.$exception->getMessage()));
            }
        }

        return [
            'form' => $form->createView(),
            'history' => $historyData ?? null,
        ];
    }
}
