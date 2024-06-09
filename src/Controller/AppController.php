<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppController extends AbstractController
{
    #[Route('/')]
    public function home(): Response
    {
        $number = random_int(0, 100);

        return $this->render('page.html.twig', [
            'number' => $number,
        ]);
    }
}
