<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TaskController extends AbstractController
{
//    #[Route('/task', name: 'app_task')]
//    public function index(): Response
//    {
//        return $this->render('task/index.html.twig', [
//            'controller_name' => 'TaskController',
//        ]);
//    }

    #[Route('index', name: 'app_index')]
    public function index(TaskRepository $taskRepository): Response{


        $tasks= $taskRepository->findAll();
        return $this->render('task/index.html.twig',[
            'controller_name' => 'TaskController',
            'tasks' => $tasks
        ]);
    }

    #[Route('create', name: 'app_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response{
        $task = new Task();
        $task->setCreatedAt(new \DateTimeImmutable());
        $task->setUpdatedAt(new \DateTimeImmutable());
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_index');
        }

        return $this->render('task/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('edit/{id}', name: 'app_edit')]
    #[IsGranted('TASK_EDIT', 'task')]
    public function edit(Task $task, Request $request,EntityManagerInterface $entityManager,AuthorizationCheckerInterface $authorizationChecker): Response{
        if(!$authorizationChecker->isGranted(attribute: 'TASK_EDIT', subject: $task)){
            $this->addFlash('error', 'Vous n'.'êtes pas autorisé à modifier cette tâche');
        }
        $task->setUpdatedAt(new \DateTimeImmutable('now +1 hour'));

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_index');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('view/{id}', name: 'app_view')]
    #[IsGranted('TASK_VIEW', 'task')]
    public function view(Task $task, AuthorizationCheckerInterface $authorizationChecker): Response{
        if(!$authorizationChecker->isGranted(attribute: 'TASK_VIEW', subject: $task)){
            $this->addFlash('error', 'Vous n'.'êtes pas autorisé à voir cette tâche');
        }
        return $this->render('task/view.html.twig',
            ['task' => $task]
        );
    }

    #[Route('delete/{id}', name: 'app_delete')]
    #[IsGranted('TASK_DELETE', 'task')]
    public function delete(Task $task,EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authorizationChecker): Response{
        if(!$authorizationChecker->isGranted(attribute: 'TASK_DELETE', subject: $task)){
            $this->addFlash('error', 'Vous n'.'êtes pas autorisé à supprimer cette tâche');
        }
        $entityManager->remove($task);
        $entityManager->flush();
        return $this->redirectToRoute('app_index');
    }
}
