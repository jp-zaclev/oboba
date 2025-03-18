<?php
namespace App\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class EnumType extends Type
{
    const ENUM = 'enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        if (isset($fieldDeclaration['columnDefinition'])) {
            preg_match("/ENUM\((.+)\)/", $fieldDeclaration['columnDefinition'], $matches);
            if (isset($matches[1])) {
                return "ENUM(" . $matches[1] . ")";
            }
        }
        throw new \Exception("Les valeurs ENUM doivent être définies via columnDefinition (ex. ENUM('val1', 'val2')).");
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    public function getName()
    {
        return self::ENUM;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
