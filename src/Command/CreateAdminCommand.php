<?php
namespace App\Command;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:create-admin', description: 'Crée un utilisateur administrateur')]
class CreateAdminCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email de l\'administrateur')
            ->addArgument('password', InputArgument::REQUIRED, 'Mot de passe de l\'administrateur')
            ->addArgument('nom', InputArgument::REQUIRED, 'Nom de l\'administrateur');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $nom = $input->getArgument('nom');

        $utilisateur = new Utilisateur();
        $utilisateur->setEmail($email);
        $utilisateur->setNom($nom);
        $utilisateur->setRoles(['ROLE_ADMIN']); // Rôle admin
        $hashedPassword = $this->passwordHasher->hashPassword($utilisateur, $password);
        $utilisateur->setPassword($hashedPassword);

        $this->entityManager->persist($utilisateur);
        $this->entityManager->flush();

        $output->writeln("Administrateur créé avec succès : $email (rôle: ROLE_ADMIN)");
        return Command::SUCCESS;
    }
}
