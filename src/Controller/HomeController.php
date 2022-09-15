<?php

namespace App\Controller;

use App\Entity\Message;

use App\Entity\ShopCart;
use App\Form\MessageType;
use App\Form\ShopCartType;
use App\Repository\ImageRepository;
use App\Repository\MessageRepository;
use App\Repository\ProductRepository;
use App\Repository\SettingRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(SettingRepository $settingRepository,ProductRepository $productRepository,ManagerRegistry $doctrine): Response
    {
        $setting=$settingRepository->find(1);
        $sliderdata = $productRepository->findAll(2);


        $entityManager = $doctrine->getManager();
        $query = $entityManager->createQuery(
           'SELECT p
            FROM App\Entity\Product p
            WHERE p.price > :price
            ORDER BY p.price ASC'
        )->setParameter('price', 1);

        //raw
        $conn = $doctrine->getManager()->getConnection();
        $sql ='SELECT * FROM product p
            WHERE p.price > :price
            ORDER BY p.price ASC
            ';
        $stmt =$conn->prepare( $sql);
        $resultSet = $stmt->executeQuery(['price' => 1]);

        // returns an array of Product objects
        $dailyproducts = $resultSet->fetchAllAssociative();


        return $this->render('home/index.html.twig', [
            'page'=>'home',
            'setting' =>$setting,
            'sliderdata' =>$sliderdata,
            'dailyproducts' =>$dailyproducts,
        ]);
    }
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request,MessageRepository $messageRepository,SettingRepository $settingRepository): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);
        $setting=$settingRepository->find(1);


                //submit yapınca if işlemi aktif oluyor
        if ($form->isSubmitted() && $form->isValid()) {

            $messageRepository->add($message,true);
            $this->addFlash(
                'success',
                'Your messages in saved!'
            );
            return $this->redirectToRoute('app_contact');
        }

        return $this->renderForm('home/contact.html.twig', [
            'form'=>$form,
            'setting' =>$setting

        ]);
    }
    #[Route('/aboutus', name: 'app_aboutus')]
    public function aboutus(SettingRepository $settingRepository): Response
    {
        $setting=$settingRepository->find(1);
        return $this->renderForm('home/aboutus.html.twig', [
        'setting' =>$setting
        ]);
    }
    #[Route('/references', name: 'app_references')]
    public function references(SettingRepository $settingRepository): Response
    {
        $setting=$settingRepository->find(1);
        return $this->renderForm('home/references.html.twig', [
            'setting' =>$setting
        ]);
    }
    #[Route('/sss', name: 'app_sss')]
    public function sss(SettingRepository $settingRepository): Response
    {
        $setting=$settingRepository->find(1);
        return $this->renderForm('home/sss.html.twig', [
            'setting' =>$setting
        ]);
    }
    #[Route('/accessdenied', name: 'app_accessdenied')]
    public function accessdenied(SettingRepository $settingRepository): Response
    {
        $setting=$settingRepository->find(1);
        return $this->renderForm('home/accessdenied.html.twig', [
            'setting' =>$setting
        ]);
    }

    #[Route('/product/{id}', name: 'app_product')]
    public function product(ProductRepository $productRepository,$id,ImageRepository $imageRepository): Response
    {
        $product=$productRepository->find($id);
        $images= $imageRepository->findBy(['product_id'=>$id]);
        $shopCart = new ShopCart();
        $form = $this->createForm(ShopCartType::class, $shopCart);

        return $this->renderForm('home/product.html.twig', [
            'product' =>$product,
            'images' =>$images,
            'form' =>$form,
        ]);
    }
}
