<?php

namespace App\Controller;

use App\Exception\WebAccessException;
use App\Form\MainType;
use App\Model\CompanyOptionsInterface;
use App\Model\FormData;
use App\Model\NotifierInterface;
use App\Service\History;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="root")
     *
     * @Template
     *
     * @throws WebAccessException
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
            $historyData = $history->getHistory($formData->symbol, $formData->startDate, $formData->endDate);
            $email->notify($formData, $historyData);
        }

        return [
            'form' => $form->createView(),
            'history' => $historyData ?? null,
        ];
    }
}
