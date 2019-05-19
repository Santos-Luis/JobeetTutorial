<?php

namespace App\Controller;

use App\Entity\Affiliate;
use App\Form\AffiliateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AffiliateController extends AbstractController
{
    /**
     * @Route("/affiliate/create", name="affiliate.create", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return RedirectResponse|Response
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $affiliate = new Affiliate();
        $form = $this->createForm(AffiliateType::class, $affiliate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $affiliate->setActive(false);

            $em->persist($affiliate);
            $em->flush();

            return $this->redirectToRoute('affiliate.wait');
        }

        return $this->render('affiliate/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/affiliate/wait", name="affiliate.wait",  methods={"GET"})
     *
     * @return Response
     */
    public function wait()
    {
        return $this->render('affiliate/wait.html.twig');
    }
}