<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontendController extends AbstractController
{
    /**
     * @Route("/{frontend}", name="app_frontend", defaults={"frontend"=""},
     *     requirements={"frontend"="(?!(api|auth|callback|_profiler|_wdt)).+"})
     */
    public function frontend(): Response
    {
        return $this->render('base.html.twig');
    }
}
