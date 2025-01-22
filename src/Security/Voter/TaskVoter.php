<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class TaskVoter extends Voter
{
    public const EDIT = 'TASK_EDIT';
    public const VIEW = 'TASK_VIEW';
    public const DELETE = 'TASK_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW,self::DELETE])
            && $subject instanceof \App\Entity\Task;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($subject, $user);

            case self::VIEW:
                return $this->canView($subject, $user);

            case self::DELETE:
                return $this->canDelete($subject, $user);
        }

        return false;
    }
    function canEdit(\App\Entity\Task $task, UserInterface $user): bool
    {
        return $task->getAuthor() === $user;
    }

    function canDelete(\App\Entity\Task $task, UserInterface $user): bool
    {
        return $task->getAuthor()->getRoles()[0] === 'ROLE_ADMIN';
    }

    function canView(\App\Entity\Task $task, UserInterface $user): bool
    {
        return $task->getAuthor() === $user;
    }
}
