<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Factory\UuidFactory;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV6;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder
    )
    {
        $this->passwordEncoder  = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $user = (new User())->setRoles(['ROLE_ADMIN']);

        $user->setPassword(($this->passwordEncoder)->hashPassword($user, '111111'));

        $manager->persist((new ApiToken($user)));
        $manager->persist($user);
        
        $manager->persist((new Category())->setName('Some Name'));

        $manager->flush();
    }
}
