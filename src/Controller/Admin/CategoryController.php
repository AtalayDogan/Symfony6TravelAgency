<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/admin/category', name: 'app_admin_category')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categorylist = $categoryRepository->findAll();
        return $this->render('admin/category/index.html.twig', [
            'categorylist' => $categorylist,
        ]);
    }
    #[Route('/admin/category/new', name: 'app_admin_category_new')]
    public function new(): Response
    {
        return $this->render('admin/category/new.html.twig', [

        ]);
    }
    #[Route('/admin/category/save', name: 'app_admin_category_save',methods: 'POST')]
    public function save(Request $request,ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $category = new Category();
        $category->setParentId($request->get('parent_id'));
        $category->setTitle($request->get('title'));
        $category->setKeywords($request->get('keywords'));
        $category->setDescription($request->get('description'));
        $category->setSlug($request->get('slug'));
        $category->setStatus($request->get('status'));
        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($category);
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();


        return $this->redirectToRoute('app_admin_category') ;
    }
    #[Route('/admin/category/edit/{id}', name: 'app_admin_category_edit')]
    public function edit(CategoryRepository $categoryRepository,$id): Response
    {
        $rs= $categoryRepository->find($id);
        return $this->render('admin/category/edit.html.twig', [
            'rs'=>$rs

        ]);
    }

    #[Route('/admin/category/update/{id}', name: 'app_admin_category_update',methods: 'POST')]
    public function update(Request $request,ManagerRegistry $doctrine,$id): Response
    {
        $entityManager = $doctrine->getManager();
        $category = $entityManager->getRepository(Category::class)->find($id);
        $category->setParentId($request->get('parent_id'));
        $category->setTitle($request->get('title'));
        $category->setKeywords($request->get('keywords'));
        $category->setDescription($request->get('description'));
        $category->setSlug($request->get('slug'));
        $category->setStatus($request->get('status'));
        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($category);
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();


        return $this->redirectToRoute('app_admin_category') ;
    }
    #[Route('/admin/category/delete/{id}', name: 'app_admin_category_delete')]
    public function delete(ManagerRegistry $doctrine,$id): Response
    {
        $entityManager = $doctrine->getManager();
        $category = $entityManager->getRepository(Category::class)->find($id);
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_category');


    }
}
