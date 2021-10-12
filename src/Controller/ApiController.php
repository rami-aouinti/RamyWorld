<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventType;
use App\Repository\EventTypeRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ApiController extends AbstractController
{

    /**
     * @var Security
     */
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/api/new", name="api_event_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EventTypeRepository $eventTypeRepository): Response
    {
        // On récupère les données
        $donnees = json_decode($request->getContent());

        if(
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->description) && !empty($donnees->description)
        ){
            $calendar = new Event();
            $code = 201;
            // On hydrate l'objet avec les données
            $calendar->setUser( $this->security->getUser());
            $calendar->setTitle($donnees->title);
            $calendar->setDescription($donnees->description);
            $calendar->setStartDate(new DateTime($donnees->start));
            if($donnees->allDay){
                $calendar->setEndDate(new DateTime($donnees->start));
            }else{
                $calendar->setEndDate(new DateTime($donnees->end));
            }
            $calendar->setAllDay($donnees->allDay);

            $calendar->setType($eventTypeRepository->findOneBy([
                'name' => $donnees->type
            ]));

            $em = $this->getDoctrine()->getManager();
            $em->persist($calendar);
            $em->flush();

            // On retourne le code
            return new Response('Ok', $code);
        }else{
            // Les données sont incomplètes
            return new Response('Données incomplètes', 404);
        }
    }

    /**
     * @Route("/api/new/type", name="api_event_new_type", methods={"POST"})
     */
    public function newType(Request $request): Response
    {
        // On récupère les données
        $donnees = json_decode($request->getContent());

        if(
            isset($donnees->name) && !empty($donnees->name) &&
            isset($donnees->backgroundColor) && !empty($donnees->backgroundColor) &&
            isset($donnees->borderColor) && !empty($donnees->borderColor) &&
            isset($donnees->textColor) && !empty($donnees->textColor)
        ){
            $eventType = new EventType();
            $code = 201;
            // On hydrate l'objet avec les données
            $eventType->setName($donnees->name);
            $eventType->setBackgroundColor($donnees->backgroundColor);
            $eventType->setTextColor($donnees->textColor);
            $eventType->setBorderColor($donnees->borderColor);
            $em = $this->getDoctrine()->getManager();
            $em->persist($eventType);
            $em->flush();

            // On retourne le code
            return new JsonResponse([
                'status' => $code,
                'data' => $eventType
            ]);
        }else{
            // Les données sont incomplètes
            return new Response('Données incomplètes', 404);
        }
    }

    /**
     * @Route("/api/{id}/edit", name="api_event_edit", methods={"PUT"})
     */
    public function edit(?Event $calendar, Request $request)
    {
        // On récupère les données
        $donnees = json_decode($request->getContent());

        if(
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->description) && !empty($donnees->description) &&
            isset($donnees->backgroundColor) && !empty($donnees->backgroundColor) &&
            isset($donnees->borderColor) && !empty($donnees->borderColor) &&
            isset($donnees->textColor) && !empty($donnees->textColor)
        ){
            // Les données sont complètes
            // On initialise un code
            $code = 200;

            // On vérifie si l'id existe
            if(!$calendar){
                // On instancie un rendez-vous
                $calendar = new Event();

                // On change le code
                $code = 201;
            }

            // On hydrate l'objet avec les données
            $calendar->setTitle($donnees->title);
            $calendar->setDescription($donnees->description);
            $calendar->setStartDate(new DateTime($donnees->start));
            if($donnees->allDay){
                $calendar->setEndDate(new DateTime($donnees->start));
            }else{
                $calendar->setEndDate(new DateTime($donnees->end));
            }
            $calendar->setAllDay($donnees->allDay);

            $em = $this->getDoctrine()->getManager();
            $em->persist($calendar);
            $em->flush();

            // On retourne le code
            return new Response('Ok', $code);
        }else{
            // Les données sont incomplètes
            return new Response('Données incomplètes', 404);
        }
    }
}
