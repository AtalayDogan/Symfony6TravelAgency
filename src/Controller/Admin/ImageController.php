<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('admin/image')]
class ImageController extends AbstractController
{
    private function uploadimage(mixed $imageFile,$slugger)
    {

        // this condition is needed because the 'image' field is not required
        // so the PDF file must be processed only when a file is uploaded
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

            // Move the file to the directory where images are stored
            try {
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            // updates the 'imageFilename' property to store the PDF file name
            // instead of its contents

        }
        return $newFilename;
    }
    #[Route('/', name: 'app_admin_image_index', methods: ['GET'])]
    public function index(ImageRepository $imageRepository): Response
    {

        return $this->render('admin/image/index.html.twig', [
            'images' => $imageRepository->findAll(),

        ]);
    }

    #[Route('/new/{pid}', name: 'app_admin_image_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ImageRepository $imageRepository,$pid,SluggerInterface $slugger): Response
    {

        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if($imageFile)
            {
                $image->setImage($this->uploadimage($imageFile,$slugger));

            }
            $image->setProduct($pid);
            $imageRepository->add($image, true);

            return $this->redirectToRoute('app_admin_image_new',['pid'=>$pid], Response::HTTP_SEE_OTHER);
        }
        $images =$imageRepository->findBy(
            ['product_id' => $pid ],
        );

        return $this->renderForm('admin/image/new.html.twig', [
            'image' => $image,
            'form' => $form,
            'pid'=>$pid,
            'images' => $images,

        ]);
    }

    #[Route('/{id}', name: 'app_admin_image_show', methods: ['GET'])]
    public function show(Image $image): Response
    {
        return $this->render('admin/image/show.html.twig', [
            'image' => $image,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_image_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Image $image, ImageRepository $imageRepository): Response
    {
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageRepository->add($image, true);

            return $this->redirectToRoute('app_admin_image_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/image/edit.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_image_delete', methods: ['POST'])]
    public function delete(Request $request, Image $image, ImageRepository $imageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $request->request->get('_token'))) {
            $imageRepository->remove($image, true);
        }
        $route = $request->headers->get('referer');

        return $this->redirect($route);

        # return $this->redirectToRoute('app_admin_image_index', [], Response::HTTP_SEE_OTHER);
    }
}
