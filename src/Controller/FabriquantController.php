<?php

namespace App\Controller;

use App\Entity\Fabriquant;
use App\Entity\Medias;
use App\Entity\Typemedia;
use App\Form\FabriquantType;
use App\Repository\FabriquantRepository;
use App\Repository\TypemediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class FabriquantController extends AbstractController
{


    public function __construct(ObjectManager $om, FabriquantRepository $fabriquantRepository){
        $this->om = $om;
        $this->fabriquantRepository = $fabriquantRepository;

    }



    #[Route('/fabriquant', name: 'fabriquant')]
    public function index(FabriquantRepository $repository): Response
    {
        $fabriquants = $repository -> findAll();
        return $this->render('fabriquant/index.html.twig', [
            'fabriquants' => $fabriquants
        ]);
    }


    #[Route('/fabriquant/{id}', name: 'suppression_fabriquant', methods:'delete')]
    public function suppression(Fabriquant $fabriquants, Request $request){

        $om=$this->om;
     //   if($this->isCsrfTokenValid("SUP". $fabriquants->getId(),$request->get('_token'))){
            $om->remove($fabriquants);
            $om->flush();
            return $this->redirectToRoute("fabriquant");
     //   }
    }


    #[Route('/fabriquant-mofification/{id}', name: 'modification_fabriquant', methods:'GET|POST')]
    public function modification(Fabriquant $fabriquants = null, TypemediaRepository $repository, Request $request)
    {

        
        if(!$fabriquants){

            $fabriquants = new fabriquant();
            
        }
        //$objectManager=$this->getDoctrine()->getManager();
        
        $om = $this->om;
        $form = $this->createForm(FabriquantType::class,$fabriquants);
        
        $vendeurs = $form->getData();
        $form -> handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            
            //Récupère l'image
            $media = $form->getData()->getMedia();
            //Récupère le fichier image
            $mediafile = $form->getData()->getMedia()->getImageFile();
            
            if ($mediafile) {
            
            //Ajouter le nom
            $name = $mediafile->getClientOriginalName();
            //Déplacer le fichier
            $lien = '/media/logos/'.$name;
            $mediafile->move('../public/media/logos', $name);
            
            //Définit les valeurs
            $media->setNom($name);
            $media->setLien($lien);

            //Ajoute le type du média
           
            /* $type = 'photo';*/
            $type = $repository->gettype('photo');
           
            $media->setType($type);
           
            
            
            }

            $om->persist($fabriquants);
           
            $om->flush();
            return $this->redirectToRoute("fabriquant");
        }
        return $this->render('fabriquant/modificationetajoutFabriquant.html.twig', [
            'fabriquant' => $fabriquants,
            'form' => $form->createView()
            //'isModification' => $fabriquants->getId() !== null
        ]);
    }

    #[Route('/fabriquant/creation', name: 'creation_fabriquant')]
    public function ajout_fab(Fabriquant $fabriquants = null, TypemediaRepository $repository, Request $request)
    {


        if(!$fabriquants){

            $fabriquants = new fabriquant();

        }
        //$objectManager=$this->getDoctrine()->getManager();

        $om = $this->om;
        $form = $this->createForm(FabriquantType::class,$fabriquants);

        $vendeurs = $form->getData();
        $form -> handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            //Récupère l'image
            $media = $form->getData()->getMedia();
            if ($media) {
                //Récupère le fichier image
                $mediafile = $form->getData()->getMedia()->getImageFile();
                //Ajouter le nom
                $name = $mediafile->getClientOriginalName();
                //Déplacer le fichier
                $lien = '/media/logos/'.$name;
                $mediafile->move('../public/media/logos', $name);

                //Définit les valeurs
                $media->setNom($name);
                $media->setLien($lien);

                //Ajoute le type du média

                /* $type = 'photo';*/
                $type = $repository->gettype('photo');

                $media->setType($type);



            }

            $om->persist($fabriquants);

            $om->flush();
            return $this->redirectToRoute("fabriquant");
        }
        return $this->render('fabriquant/modification.html.twig', [
            'fabriquant' => $fabriquants,
            'form' => $form->createView()
           // 'isModification' => $fabriquants->getId() !== null
        ]);
    }

    #[Route('/consultation-fabriquant/{id}', name: 'consultation_fabriquant', methods:'GET|POST')]
    public function consultation(Fabriquant $fabriquant): Response
    {


        $fabriquant = $this->fabriquantRepository->findOneById($fabriquant->getId());


        return $this->render('fabriquant/consultation.html.twig', [
            'fabriquant' => $fabriquant

        ]);
    }

    #[Route('/security-fabriquant/{id}', name: 'security_fabriquant', methods:'GET|POST')]
    public function secure(UserPasswordHasherInterface $userPasswordHasher, ObjectManager $objectManager, Request $request, $id)
    {


        $fabriquant = $this->FabriquantRepository->findOneById($id);

        $user= $fabriquant->getUtilisateur();
        $form = $this->createForm(SecUtilisateurType::class,$user);
        $form -> handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){


            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );



            $objectManager->persist($user);
            $objectManager->flush();

            return $this->redirectToRoute("fabriquant");


        }



        return $this->render('utilisateur/security.html.twig', [
            'utilisateur' => $user,
            'form' => $form->createView()


        ]);

    }
}
