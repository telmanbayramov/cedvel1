<?php

namespace App\Helpers;

class GroupHelper
{
    /**
     * Grup türünü sayısal değere dönüştürür.
     *
     * @param int $groupType
     * @return int
     */
    public static function mapGroupType($groupType)
    {
        $groupTypes = [
            1 => 'eyani',   // 1: Əyani
            2 => 'qiyabi',  // 2: Qiyabi
        ];

        return $groupTypes[$groupType] ?? 0;  // Eğer geçerli bir tür yoksa 0 döndür
    }

    /**
     * Grup seviyesini sayısal değere dönüştürür.
     *
     * @param int $groupLevel
     * @return int
     */
    public static function mapGroupLevel($groupLevel)
    {
        $groupLevels = [
            1 => 'magistr',  // 1: Magistr
            2 => 'bakalavr', // 2: Bakalavr
        ];

        return $groupLevels[$groupLevel] ?? 0;  // Eğer geçerli bir seviye yoksa 0 döndür
    }

    /**
     * Sayısal grup türüne göre adı döndürür.
     *
     * @param int $groupType
     * @return string
     */
    public static function getGroupTypeName($groupType)
    {
        $groupNames = [
            1 => 'Əyani',   // 1: Əyani
            2 => 'Qiyabi',  // 2: Qiyabi
        ];

        return $groupNames[$groupType] ?? 'Unknown';
    }

    /**
     * Sayısal grup seviyesine göre adı döndürür.
     *
     * @param int $groupLevel
     * @return string
     */
    public static function getGroupLevelName($groupLevel)
    {
        $groupLevels = [
            1 => 'Magistr',   // 1: Magistr
            2 => 'Bakalavr',  // 2: Bakalavr
        ];

        return $groupLevels[$groupLevel] ?? 'Unknown';
    }
}
