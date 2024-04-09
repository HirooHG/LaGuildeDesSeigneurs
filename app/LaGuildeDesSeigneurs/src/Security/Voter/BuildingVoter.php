<?php

namespace App\Security\Voter;

use App\Entity\Building;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class BuildingVoter extends Voter
{
  public const BUILDING_DISPLAY = 'buildingDisplay';
  public const BUILDING_CREATE = 'buildingCreate';
  public const BUILDING_INDEX = 'buildingIndex';
  public const BUILDING_UPDATE = 'buildingUpdate';
  public const BUILDING_DELETE = 'buildingDelete';

  private const ATTRIBUTES = array(
    self::BUILDING_CREATE,
    self::BUILDING_DISPLAY,
    self::BUILDING_INDEX,
    self::BUILDING_UPDATE,
    self::BUILDING_DELETE
  );

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

  private function canDelete($token, $subject)
  {
    return true;
  }

  protected function supports(string $attribute, mixed $subject): bool
  {
    if (null !== $subject) {
      return $subject instanceof Building && in_array($attribute, self::ATTRIBUTES);
    }
    return in_array($attribute, self::ATTRIBUTES);
  }

  protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
  {
    switch ($attribute) {
      case self::BUILDING_CREATE:
      case self::BUILDING_INDEX:
        return $this->canCreate($token, $subject);
      case self::BUILDING_DISPLAY:
        return $this->canDisplay($token, $subject);
      case self::BUILDING_UPDATE:
        return $this->canUpdate($token, $subject);
      case self::BUILDING_DELETE:
        return $this->canDelete($token, $subject);
      default:
        return false;
    }

    throw new LogicException('Invalid attribute: ' . $attribute);
  }
}
