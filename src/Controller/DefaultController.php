<?php

namespace App\Controller;

use App\Form\MainType;
use App\Model\CompanyOptionsInterface;
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
     */
    public function indexAction(Request $request, CompanyOptionsInterface $company): array
    {
        $form = $this->createForm(MainType::class, null, ['companyOptions' => $company]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            dump($formData);
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
