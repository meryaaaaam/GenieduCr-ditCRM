<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Entity\Typemedia;
use App\Entity\Concessionnaire;
use App\Entity\GalerieVehicule;
use App\Entity\Marchand;
use App\Entity\Medias;
use App\Repository\VehiculeRepository;
use App\Repository\ConcessionnaireRepository;
use App\Repository\MarchandRepository;
use App\Repository\PartenaireRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\VehiculeType;
use App\Form\MediaType;
use App\Repository\AdministrateurRepository;
use App\Repository\AgentRepository;
use App\Repository\ConcessionnairemarchandRepository;
use App\Repository\GalerieVehiculeRepository;
use App\Repository\MediasRepository;
use App\Repository\TypemediaRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VehiculeController extends AbstractController
{
    public function __construct(MediasRepository $MR,
     PartenaireRepository $partenaireRepository, 
     AgentRepository $agentRepository,
     ConcessionnairemarchandRepository $concessionnairemarchandRepository,
     AdministrateurRepository $administrateurRepository,
     ObjectManager $om,
     GalerieVehiculeRepository $repositorygalerie,
     VehiculeRepository $vehiculeRepository,
     

    )
    {
        $this->MR = $MR;
        $this->om = $om;
        
        //ici on instancie le repo
        $this->vehiculeRepository=$vehiculeRepository;
       
        
    }


    #[Route('/vehicule', name: 'vehicule')]
    public function index(VehiculeRepository $repository): Response
    {
        $vehicules = $repository -> findAll();
       
        return $this->render('vehicule/index.html.twig', [
            'vehicules' => $vehicules,
        ]);
    }



    #[Route('/edit-vehicule/{id}', name: 'edit-vehicule', methods:'GET|POST')]
    #[Route('/add-vehicule', name: 'add_vehicule')]
    public function ajouter(Vehicule $vehicules = null,TypemediaRepository $repository,Request $request)
    {

       if(!$vehicules){

            $vehicules = new Vehicule();
       }
            $om = $this->om;

         
            
           

           if ($vehicules->getGalerie()->isEmpty()) {
                $image = new GalerieVehicule();
                $image->setVehicule($vehicules);  
                $vehicules->getGalerie()->add($image);
           }
            
            $form = $this->createForm(VehiculeType::class,$vehicules);
           
            $form -> handleRequest($request);
          
           
            if($form->isSubmitted() && $form->isValid()){
               
                $galerie =$form->getData()->getGalerie();
                
                
                foreach($galerie as $photogalerie){

                   

                    $photogaleriefile = $photogalerie->getImageFile();
                    
                  
                    //Ajouter le nom
                   if ($photogaleriefile){ $photogalerienom= $photogaleriefile->getClientOriginalName();
                  

                    //Déplacer le fichier
                   $photogalerielien = '/media/galerie/'.$photogalerienom;
                    $photogaleriefile->move('../public/media/galerie', $photogalerienom);

                   

                    $photogalerie ->setNom($photogalerienom);
                    $photogalerie ->setLien($photogalerielien);

                   

                    //Ajoute le type du média
                  
                        $type = $repository->gettype('galerie');
                      
                        
                        $photogalerie->setType($type);
                    
                   }
                       
                    
                } 
            
            
               

               


                //Récupère l'image
              $media = $form->getData()->getMedia();
               if ($media){ 
                //Récupère le fichier image
                $mediafile = $form->getData()->getMedia()->getImageFile();
                
                $name = $mediafile->getClientOriginalName();
            
                //Ajouter le nom
              
                //Déplacer le fichier
                $lien = '/media/logos/'.$mame;
                $mediafile->move('../public/media/logos', $name);

                //Définit les valeurs
                $media->setNom($name);
                $media->setLien($lien);

                //Ajoute le type du média

                /* $type = 'photo';*/
                $type = $repository->gettype('photo');

                $media->setType($type);



            }
            
            
            $this->om->persist($vehicules);
           
           
            $om->flush();
            return $this->redirectToRoute("vehicule");
                
        
                 
             
        }

        
      
        return $this->render('vehicule/ajouter-modif.html.twig', [
            
            'vehicules' => $vehicules,
             'form' => $form->createView(),
             'isModification' => $vehicules->getId() !== null
            
        ]);   
    }     
            

    #[Route('/delete-vehicule/{id}', name: 'delete-vehicule', methods:'delete')]
    public function delete (Vehicule $vehicules,TypemediaRepository $repository,Request $request,ObjectManager $objectManager)
    {
     
        if($this->isCsrfTokenValid("SUP". $vehicules->getId(),$request->get('_token'))){
            $objectManager->remove($vehicules);
            $objectManager->flush();
            return $this->redirectToRoute("vehicule");
        }

    }
     
     #[Route('/image/{id}', name:'galerie_delete_image',methods:'delete')]
     
    public function deleteImage(Medias $image, Request $request,ObjectManager $objectManager){
        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){
            // On récupère le nom de l'image
            $nom = $image->getNom();
            // On supprime le fichier
            unlink($this->getParameter('/media/media/').'/'.$nom);

            // On supprime l'entrée de la base
          
            $objectManager->remove($image);
            $objectManager->flush();

            // On répond en json
            return new JsonResponse(['success' => 1]);
        }else{
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    
        
    }

   #[Route('/supprimeimage/{id}', name:'delete_image',methods:'delete')]
     
    public function deletephoto(GalerieVehicule $image, Request $request,ObjectManager $objectManager){
        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){
            // On récupère le nom de l'image
            $nom = $image->getNom();
            // On supprime le fichier
            unlink($this->getParameter('/media/galerie/').'/'.$nom);

            // On supprime l'entrée de la base
          
            $objectManager->remove($image);
            $objectManager->flush();

            // On répond en json
            return new JsonResponse(['success' => 1]);
        }else{
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    
        
    }



    #[Route('/conslt-vehicule/{id}', name: 'consultation_vehicule', methods:'GET|POST')]
     public function consultation( Vehicule  $vehicules): Response
 {
     
      $vehiculeRepository = $this->vehiculeRepository;
      
      $vehicules = $vehiculeRepository ->findOneById ($vehicules->getId());
     
      return $this->render('vehicule/consultation.html.twig', [
      'vehicules' => $vehicules
      
     ]);
 
 }
 
 #[Route('/options-vehicule/{id}', name: 'options_vehicule', methods:'GET|POST')]
 public function consultationoptions( Vehicule  $vehicules): Response
{
 
  $vehiculeRepository = $this->vehiculeRepository;
  
  $vehicules = $vehiculeRepository ->findOneById ($vehicules->getId());
 
 return $this->render('vehicule/options-vehicule.html.twig', [
    'vehicules' => $vehicules
    
   ]);
}

    

}    



  
    
