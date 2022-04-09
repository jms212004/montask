<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use App\Entity\Tasks;
use App\Entity\User;

class DashboardController extends AbstractDashboardController
{
	#[Route('/admin', name: 'admin')]
	public function index(): Response
	{
	$routeBuilder = $this->container->get(AdminUrlGenerator::class);
	$url = $routeBuilder->setController(TasksCrudController::class)->generateUrl();

	return $this->redirect($url);
	}

	public function configureDashboard(): Dashboard
	{
	return Dashboard::new()
		->setTitle('Mon task');
	}

	public function configureMenuItems(): iterable
	{
	yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

	yield MenuItem::section('Tasks');

	yield MenuItem::subMenu('Actions', 'fas fa-bars')->setSubItems([
		MenuItem::linkToCrud('Create Tasks', 'fas fa-plus', Tasks::class)->setAction(Crud::PAGE_NEW),
		MenuItem::linkToCrud('Show Tasks', 'fas fa-eye', Tasks::class)
	]);

	yield MenuItem::section('Users');

	yield MenuItem::subMenu('Actions', 'fas fa-bars')->setSubItems([
		MenuItem::linkToCrud('Create Users', 'fas fa-plus', User::class)->setAction(Crud::PAGE_NEW),
		MenuItem::linkToCrud('Show Users', 'fas fa-eye', User::class)
	]);
	}
}
