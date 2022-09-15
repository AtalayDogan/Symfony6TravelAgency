<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ShopCard;
use App\Entity\ShopCart;
use App\Form\ShopCartType;
use App\Repository\ShopCardRepository;
use App\Repository\ShopCartRepository;
use Doctrine\ORM\Mapping\Id;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/shop/cart')]
class ShopCartController extends AbstractController
{
    #[Route('/', name: 'app_shop_cart_index', methods: ['GET'])]
    public function index(ShopCardRepository $shopCardRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $shop_carts =$shopCardRepository->findOneBy(['user'=>$user->getId()]);


        return $this->render('shop_cart/shopcard.html.twig', [
            'shop_carts' => $shop_carts,
        ]);
    }

    #[Route('/new', name: 'app_shop_cart_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ShopCartRepository $shopCartRepository): Response
    {
        $shopCart = new ShopCart();
        $form = $this->createForm(ShopCartType::class, $shopCart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shopCartRepository->add($shopCart, true);

            return $this->redirectToRoute('app_shop_cart_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('shop_cart/new.html.twig', [
            'shop_cart' => $shopCart,
            'form' => $form,
        ]);
    }
    #[Route('/add', name: 'app_shop_cart_add', methods: ['GET', 'POST'])]
    public function save(Request $request,ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $conn = $doctrine->getManager()->getConnection();
        $sql ='INSERT INTO shop_card (quantity,product_id,user_id)
            VALUES (:quantity, :product_id , :user_id) 
            ';
        $stmt =$conn->prepare( $sql);
        $stmt->executeQuery([
            'quantity'=> $request->get('quantity'),
            'product_id'=> $request->get('product_id'),
            'user_id'=> $user->getId()

        ]);
        $this->addFlash(
            'success',
            'Your TRAVEL is saved!'
        );
        $route = $request->headers->get('referer');
        return $this->redirect($route);






    }

    #[Route('/{id}', name: 'app_shop_cart_show', methods: ['GET'])]
    public function show(ShopCart $shopCart): Response
    {
        return $this->render('shop_cart/show.html.twig', [
            'shop_cart' => $shopCart,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_shop_cart_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ShopCart $shopCart, ShopCartRepository $shopCartRepository): Response
    {
        $form = $this->createForm(ShopCartType::class, $shopCart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shopCartRepository->add($shopCart, true);

            return $this->redirectToRoute('app_shop_cart_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('shop_cart/edit.html.twig', [
            'shop_cart' => $shopCart,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_shop_cart_delete', methods: ['POST'])]
    public function delete(Request $request, ShopCart $shopCart, ShopCartRepository $shopCartRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$shopCart->getId(), $request->request->get('_token'))) {
            $shopCartRepository->remove($shopCart, true);
        }

        return $this->redirectToRoute('app_shop_cart_index', [], Response::HTTP_SEE_OTHER);
    }
}
