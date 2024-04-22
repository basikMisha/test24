<?php

// src/Security/PostVoter.php
namespace App\Security;

use App\Entity\Claim;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ClaimVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'view';
    const EDIT = 'edit';

    public function __construct(private Security $security)
    {

    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::VIEW, self::EDIT])) {
            return false;
        }

        // only vote on `Post` objects
        if (!$subject instanceof Claim) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Post object, thanks to `supports()`
        /** @var Claim $claim */
        $claim = $subject;

        return match($attribute) {
            self::VIEW => $this->canView($claim, $user),
            self::EDIT => $this->canEdit($claim, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canView(Claim $claim, User $user): bool
    {
        // if they can edit, they can view
        if ($this->canEdit($claim, $user)) {
            return true;
        }

        // the Post object could have, for example, a method `isPrivate()`
        // return !$Comment->isPrivate();
        return true;
    }

    private function canEdit(Claim $claim, User $user): bool
    {
        if($this->security->isGranted('ROLE_ADMIN')) {

            return true;

        } elseif($this->security->isGranted('ROLE_MANAGER')) {

            return true;

        }

        return $user === $claim->getUser();
    }
}