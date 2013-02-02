<?php

namespace sasCC\User;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
* Wrapper around a Doctrine ObjectManager.
*
* Provides easy to use provisioning for Doctrine entity users.
*
* @author Fabien Potencier <fabien@symfony.com>
* @author Johannes M. Schmitt <schmittjoh@gmail.com>
*/
class EntityUserProvider implements UserProviderInterface
{
    private $class;
    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    public function __construct(\Doctrine\ORM\EntityManager  $em)
    {
        $this->em = $em;
        $this->class = '\sasCC\User\User';
    }


    public function loadUserByUsername($username)
    {
        $user = $this->em->getRepository($this->class)->findOneBy(array('userName' => $username));

        if (null === $user) {
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
        }

        return $user;
    }

    /**
* {@inheritDoc}
*/
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof $this->class) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        $id = $user->getId();
        if (null === $refreshedUser = $this->em->find($this->class, $id)) {
            throw new UsernameNotFoundException(sprintf('User with id %s not found', json_encode($id)));
        }

        return $refreshedUser;
    }

    public function supportsClass($class)
    {
        return $class === $this->class || is_subclass_of($class, $this->class);
    }
}

?>
