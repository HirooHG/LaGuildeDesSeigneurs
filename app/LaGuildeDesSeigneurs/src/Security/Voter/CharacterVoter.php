<?php

namespace App\Security\Voter;

use App\Entity\Character;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CharacterVoter extends Voter
{
  public const CHARACTER_DISPLAY = 'characterDisplay';
  public const CHARACTER_CREATE = 'characterCreate';
  public const CHARACTER_INDEX = 'characterIndex';
  public const CHARACTER_UPDATE = 'characterUpdate';

  private const ATTRIBUTES = array(
    self::CHARACTER_CREATE,
    self::CHARACTER_DISPLAY,
    self::CHARACTER_INDEX,
    self::CHARACTER_UPDATE
  );
  // Checks if is allowed to display
  private function canDisplay($token, $subject)
  {
    return true;
  }

  private function canCreate($token, $subject)
  {
    return true;
  }

  private function canUpdate($token, $subject)
  {
    return true;
  }

  protected function supports(string $attribute, mixed $subject): bool
  {
    if (null !== $subject) {
      return $subject instanceof Character && in_array($attribute, self::ATTRIBUTES);
    }
    return in_array($attribute, self::ATTRIBUTES);
  }

  protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
  {
    switch ($attribute) {
      case self::CHARACTER_CREATE:
      case self::CHARACTER_INDEX:
        return $this->canCreate($token, $subject);
      case self::CHARACTER_DISPLAY:
        return $this->canDisplay($token, $subject);
      case self::CHARACTER_UPDATE:
        return $this->canUpdate($token, $subject);
    }

    throw new LogicException('Invalid attribute: ' . $attribute);
  }
}
