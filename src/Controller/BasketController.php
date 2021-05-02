<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class BasketController extends AbstractController
{
    /**
     * @Route("/", name="basket")
     */
    public function home(ArticleRepository $ArticleRepo): Response
    {
        return $this->render('home/index.html.twig', [
            'articles' => $ArticleRepo->findAll(),
        ]);
    }
    /**
     * @Route("/panier", name="panier")
     */
    public function monpanier(SessionInterface $session, ArticleRepository $articleRepo): Response
    {
        $panier = $session->get('panier', []);

        $monPanier = [];
        $total = 0;
        //parcoure le panier de la session courante afin d'extraire ces données dans un nouveau tableau
        foreach ($panier as $id => $quantity) {
            $article = $articleRepo->find($id);
            $monPanier[] = [
                'article' => $articleRepo->find($id),
                'quantité' => $quantity
            ];
            $total += $article->getPrice() * $quantity;
        }
        // dd($monPanier);
        return $this->render('basket/index.html.twig', compact("monPanier", "total"));
    }
    /**
     * @Route("/add/{id}", name="add_item")
     */
    public function add($id, SessionInterface $session): Response
    {
        //dd($session);

        //panier de la session courante
        $panier = $session->get("panier", []);

        //assiociation entre l'id du produit et la quantité ajoutée ou initialisé à 1
        //$panier = [
        //     "1" => 2,
        //     "2" => 1
        // ]
        if (!empty($panier[$id])) { //si le panier n'est pas vide on incrémente la quantité sinon on l'affecte à 1
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }
        //sauvegarde le nouveau panier
        $session->set("panier", $panier);
        dd($panier);

        return $this->redirectToRoute("panier");
    }
    /**
     * @Route("/remove/{id}", name="remove_item")
     */
    public function remove($id, SessionInterface $session): Response
    {
        //dd($session);

        //panier de la session courante
        $panier = $session->get("panier", []);

        //assiociation entre l'id du produit et la quantité ajoutée ou initialisé à 1
        //$panier = [
        //     "1" => 2,
        //     "2" => 1
        // ]
        if (!empty($panier[$id])) { //si le panier n'est pas vide on décrémente la quantité sinon on enlève le produit du panier
            if ($panier[$id] > 1) {
                $panier[$id]--;
            } else {
                unset($panier[$id]);
            }
        }
        //sauvegarde le nouveau panier
        $session->set("panier", $panier);
        // dd($panier);

        return $this->redirectToRoute("panier");
    }
}
