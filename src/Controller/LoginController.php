<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends AbstractController
{
    public function __invoke(Request $request)
    {
        return new JsonResponse([

        ]);
    }
}