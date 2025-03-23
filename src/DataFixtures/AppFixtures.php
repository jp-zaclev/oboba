<?php
namespace App\DataFixtures;

use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Vérifier s'il existe déjà un utilisateur
        $existingUsers = $manager->getRepository(Utilisateur::class)->findAll();
        if (!empty($existingUsers)) {
            return; // Ne rien faire si la table n'est pas vide
        }

        // Créer l'admin par défaut
        $admin = new Utilisateur();
        $admin->setEmail('admin@default.com');
        $admin->setNom('Admin Défaut');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123')); // Mot de passe par défaut

        $manager->persist($admin);
        $manager->flush();
    }
}
