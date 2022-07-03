<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Entity\Feed;
use App\Entity\FeedCategory;
use App\Entity\Recipe;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('My Keto Project');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Admin', 'fas fa-lock', Admin::class);
        yield MenuItem::linkToCrud('Feed categories', 'fas fa-certificate', FeedCategory::class);
        yield MenuItem::linkToCrud('Feeds', 'fas fa-rss', Feed::class);
        yield MenuItem::linkToUrl('Generate Feeds', 'fa-solid fa-download', '/feeds/generate');
        yield MenuItem::linkToCrud('Recipes', 'fas fa-newspaper', Recipe::class);
        yield MenuItem::linkToLogout('Logout', 'fa-solid fa-right-from-bracket');
        yield MenuItem::linkToUrl('Go back to website', 'fa-solid fa-person-walking-arrow-right', '/');
    }
}
