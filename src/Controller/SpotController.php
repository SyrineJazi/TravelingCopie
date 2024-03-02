<?php

namespace App\Controller;
use App\Form\AvisType;
use App\Repository\SpotRepository;
use App\Entity\Spot;
use App\Form\SpotType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

// ...

class SpotController extends AbstractController
{  
    /**
   *@Route("/",name="spot_list")
   */
    public function home()
    
{
$spot = $this->getDoctrine()->getRepository(Spot::class)->findAll();
    return $this->render('admin/media_gallery.html.twig', ['spot' => $spot]);
}

    /**
 * @Route("/spot/save")
 */
public function save()
{
    $entityManager = $this->getDoctrine()->getManager();

    // Créez une nouvelle instance de Spot
    $spot = new Spot();
    $spot->setNom('Nom du spot');  // Remplacez par le nom réel du spot
    $spot->setLocalisation('Localisation du spot');  // Remplacez par la localisation réelle
    $spot->setDescription('Description du spot');  // Remplacez par la description réelle

    // Persistez l'entité Spot
    $entityManager->persist($spot);

    // Flush pour enregistrer les changements en base de données
    $entityManager->flush();

    // Retournez une réponse avec l'ID du spot enregistré
    return new Response('Spot enregistré avec l\'ID '.$spot->getId());


}
/**
 * @Route("/spot/new", name="new_spot")
 * Method({"GET", "POST"})
 */
public function new(Request $request)
{
    $spot = new Spot();
    $form = $this->createForm(SpotType::class, $spot);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($spot);
        $entityManager->flush();

        return $this->redirectToRoute('app_spot');
    }

    return $this->render('spot/new.html.twig', ['form' => $form->createView()]);
}

/**
 * @Route("/spot/{id}", name="spot_show")
 */
public function show($id) {
    
    $spot = $this->getDoctrine()->getRepository(Spot::class)->find($id);

    return $this->render('spot/show.html.twig', ['spot' => $spot]);
}



    
    /**
         * @Route("/Spot/edit/{id}", name="edit_spot", methods={"GET", "POST"})
         */
        public function edit(Request $request, $id): Response
        {
            $spot = $this->getDoctrine()->getRepository(Spot::class)->find($id);

            $form = $this->createFormBuilder($spot)
                ->add('nom', TextType::class, [
                    'label' => 'Nom du Spot',
                    'attr' => ['class' => 'form-control'],
                ])
                ->add('localisation', TextType::class, [
                    'label' => 'Localisation',
                    'attr' => ['class' => 'form-control'],
                ])
                ->add('description', TextareaType::class, [
                    'label' => 'Description',
                    'attr' => ['class' => 'form-control', 'rows' => 5],
                ])
                ->add('save', SubmitType::class, [
                    'label' => 'Modifier',
                    'attr' => ['class' => 'btn btn-primary'],
                ])
                
                ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                return $this->redirectToRoute('spot_list');
            
        } else {
            dump($form->getErrors());
        }
            return $this->render('spot/edit.html.twig', ['form' => $form->createView()]);
        }


        
        #[Route('/tri', name: 'tri')]
        public function tri(Request $request , SpotRepository $SpotRepository): Response
        {
            $query = $request->query->get('query');
            $sort = $request->query->get('sort');
            $sort = 'ASC';
            $spot = $SpotRepository->createQueryBuilder('b');
        
           # // Ajouter une condition de recherche si un terme de recherche est fourni
           if ($query) {
           $spot->where('b.nom LIKE :query')
                    ->setParameter('query', '%'.$query.'%');
           }
        
            // Ajouter une condition de tri si un critère de tri est fourni
    if ($sort) {
        // Assurez-vous d'ajouter l'ASC ou le DESC pour spécifier le sens du tri
        $spot->orderBy('b.nom', $sort);
    }
    
    $result = $spot->getQuery()->getResult();
    
    return $this->render('admin/media_gallery.html.twig', [
        'spot' => $result,
    ]);
    
        }

  /**
 * @Route("/spot/delete/{id}", name="delete_spot", methods={"GET", "DELETE"})
 */

public function delete(Request $request, $id):Response {
    $spot = $this->getDoctrine()->getRepository(Spot::class)->find($id);

    if (!$spot) {
        throw $this->createNotFoundException('Spot non trouvé avec l\'id : ' . $id);
    }

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($spot);
    $entityManager->flush();

    return $this->redirectToRoute('spot_list');
}

}