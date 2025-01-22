<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $user1 = new User();
        $user1->setEmail('admin');
        $user1->setPassword($this->passwordHasher->hashPassword($user1, 'admin'));
        $user1->setRoles(['ROLE_ADMIN']);

        $user2 = new User();
        $user2->setEmail('user2@gmail.com');
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'user2!'));
        $user2->setRoles(['ROLE_USER']);
//
        $user3 = new User();
        $user3->setEmail('user3@gmail.com');
        $user3->setPassword($this->passwordHasher->hashPassword($user3, 'user3!'));
        $user3->setRoles(['ROLE_USER']);

        $user4 = new User();
        $user4->setEmail('user4@gmail.com');
        $user4->setPassword($this->passwordHasher->hashPassword($user4, 'user4!'));
        $user4->setRoles(['ROLE_USER']);

        $user5 = new User();
        $user5->setEmail('user5@gmail.com');
        $user5->setPassword($this->passwordHasher->hashPassword($user5, 'user5!'));
        $user5->setRoles(['ROLE_USER']);

        $user6 = new User();
        $user6->setEmail('user6@gmail.com');
        $user6->setPassword($this->passwordHasher->hashPassword($user6, 'user6!'));
        $user6->setRoles(['ROLE_USER']);


        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);
        $manager->persist($user4);
        $manager->persist($user5);
        $manager->persist($user6);
        $manager->flush();
    }
}
