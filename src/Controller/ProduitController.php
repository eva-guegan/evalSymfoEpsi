<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProduitRepository $repository)
    {
        $produits=$repository->findAll();
        return $this->render('produit/index.html.twig', [
            'produits'=>$produits
        ]);
    }

    /**
     * @Route("/ajouter-produit", name="produit_ajouter")
     */
    public function ajouter(Request $request)
    {
        $produit = new Produit();
        $formulaire=$this->createForm(ProduitType::class, $produit);
        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute("home");
        }

        return $this->render('produit/form.html.twig', [
            'formulaire'=>$formulaire->createView(),
            'h1'=>"Ajouter un produit"
        ]);
    }

    /**
     * @Route("/modifier-produit/{id}", name="produit_modifier")
     */
    public function modifier(ProduitRepository $repository, $id, Request$request)
    {
        $produit = $repository->find($id);
        $formulaire=$this->createForm(ProduitType::class, $produit);
        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute("home");
        }

        return $this->render('produit/form.html.twig', [
            'formulaire'=>$formulaire->createView(),
            'h1'=>"Modifier le produit ".$produit->getNom()
        ]);
    }

    /**
     * @Route("/supprimer-produit/{id}", name="produit_supprimer")
     */
    public function supprimer(ProduitRepository $repository, $id, Request $request)
    {
        $produit = $repository->find($id);
        $formulaire=$this->createForm(ProduitType::class, $produit);
        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->remove($produit);
            $em->flush();

            return $this->redirectToRoute("home");
        }

        return $this->render('produit/formSuppr.html.twig', [
            'formulaire'=>$formulaire->createView(),
            'h1'=>"Supprimer la catégorie ".$produit->getNom()
        ]);
    }
}
