<?php

namespace  Rayenbou\TicketBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Rayenbou\TicketBundle\Service\TicketService;
use Rayenbou\TicketBundle\Form\TicketType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/ticket')]
class TicketController extends AbstractController
{
    #[Route('/', name: 'rayenbou_ticket_index')]
    public function index(TicketService $ticketService, Request $request)
    {
        $form = $this->createForm(TicketType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $ticketService->createTicket(['title' => $data['title'], 'description' => $data['description']]);

            return $this->redirectToRoute('rayenbou_ticket_index');
        }
        return $this->render('@RayenbouTicket/ticket/index.html.twig', [
            'form' => $form,
            'tickets' => $ticketService->findAll()
        ]);
    }
    #[Route('/{token}', name: 'rayenbou_ticket_conversation')]
    public function conversation(TicketService $ticketService, string $token, Request $request)
    {
        $form = $this->createForm(TicketType::class, null, ["title" => false]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $ticketService->modifyTicket(['token' => $token, 'description' => $data['description']]);

            return $this->redirectToRoute('rayenbou_ticket_conversation', ['token' => $token]);
        }
        return $this->render('@RayenbouTicket/ticket/conversation.html.twig', [
            'form' => $form,
            'ticket' => $ticketService->find($token)
        ]);
    }
}
