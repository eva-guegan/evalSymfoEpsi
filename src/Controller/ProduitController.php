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

        //Ce qui est en commentaire correspond à la recherche pour EAN
        /*$EAN=$produit->getEAN();
        $dernierChiffreEAN=  $EAN{strlen($EAN)-1};

        $tableau = [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1];
        $calcul= $tableau[0]*1+$tableau[1]*3+$tableau[2]*1+$tableau[3]*3+$tableau[4]*1+$tableau[5]*3+$tableau[6]*1
            +$tableau[7]*3+$tableau[8]*1+$tableau[9]*3+$tableau[10]*1+$tableau[11]*3; //24
        $calcul2=$calcul- ceiling($calcul/10)*10;
        $calcul3=$calcul2-($calcul2*2);*/


        if ($formulaire->isSubmitted() && $formulaire->isValid() /*&& $dernierChiffreEAN==$calcul3*/){
            $em=$this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute("home");
        }

        return $this->render('produit/form.html.twig', [
            'formulaire'=>$formulaire->createView(),
            'h1'=>"Modifier le produit ".$produit->getNom(),
            /*"result"=>$calcul3,
            "essai"=>$dernierChiffreEAN*/
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
