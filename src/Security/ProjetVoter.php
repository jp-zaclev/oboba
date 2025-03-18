<?php
namespace App\Security;

use App\Entity\Projet;
use App\Entity\Utilisateur;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProjetVoter extends Voter
{
    const CAN_EDIT_CABLES = 'CAN_EDIT_CABLES';
    const CAN_EDIT_CONNECTEURS = 'CAN_EDIT_CONNECTEURS';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::CAN_EDIT_CABLES, self::CAN_EDIT_CONNECTEURS])
            && $subject instanceof Projet;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof Utilisateur) {
            return false;
        }

        if (!$subject instanceof Projet) {
            return false;
        }

        foreach ($subject->getProjetUtilisateurs() as $pu) {
            if ($pu->getUtilisateur() === $user) {
                return in_array($pu->getRole(), ['proprietaire', 'concepteur']);
            }
        }

        return false;
    }
}
