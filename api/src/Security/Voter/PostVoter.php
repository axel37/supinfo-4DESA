<?php

namespace App\Security\Voter;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Psr\Log\LoggerInterface;

class PostVoter extends Voter
{
    public const EDIT = 'POST_EDIT';
    public const VIEW = 'POST_VIEW';

    public function __construct(private LoggerInterface $logger)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof Post;
    }

    /** @var Post $subject */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        return match ($attribute) {
            self::VIEW => self::canView($subject, $user),
            self::EDIT => self::canEdit($subject, $user)
        };
    }

    private static function canView(Post $post, User $currentUser): bool
    {
        if ($post->getAuthor()->isProfileIsPublic()) {
            return true;
        }

        return $currentUser === $post->getAuthor();
    }

    private static function canEdit(Post $post, User $currentUser)
    {
        return $post->getAuthor() === $currentUser;
    }

}
