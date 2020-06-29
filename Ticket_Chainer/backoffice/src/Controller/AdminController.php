<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function admin()
    {
        return $this->render('robust-admin/html/ltr/vertical-menu-template/login-with-bg-image.html');
    }

}