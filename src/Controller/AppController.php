<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use App\Services\Cockpit;
use App\Services\CockpitItemNotFoundException;
use App\Entity\Page;
use Exception;
use Throwable;

class AppController extends AbstractController
{
    #[Route('/', methods: ['GET'], name: 'home')]
    public function home(Cockpit $cockpit): Response
    {
        return $this->page('home', $cockpit);
    }

    #[Route('/{pageName}', methods: ['GET'], name: 'page')]
    public function page(string $pageName, Cockpit $cockpit): Response
    {
        try {
            $page = Page::createFromArray($cockpit->findOne('pages', ['slug' => $pageName]));
        } catch (CockpitItemNotFoundException $e) {
            throw $this->createNotFoundException('The requested page was not found.');
        }

        try {
            $templatesDir = $this->getParameter('kernel.project_dir') . '/templates';
            $overrideName = "page--{$pageName}.html.twig";

            return $this->render(
                is_readable("{$templatesDir}/{$overrideName}") ? $overrideName : 'page.html.twig',
                get_object_vars($page)
            );
        } catch (Throwable $error) {
            if (($_ENV['APP_DEBUG'] ?? '') === '1') {
                throw $error;
            }
            throw new Exception(
                "There has been an error processing the request. Please try again later.",
                $error->getCode(),
                $error
            );
        }
    }
}
