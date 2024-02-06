<?php

namespace App\State;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Post;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class AssociateUserToPostProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private Security           $security,
    )
    {
    }

    /** @param Post $data */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Post|null
    {
        $user = $this->security->getUser();
        $data->setAuthor($user);

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
