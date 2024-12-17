<?php

namespace App\Helpers;

class GroupHelper
{
    /**
     * Group Type'ı kontrol ederek, adını döndürür.
     *
     * @param int $groupType
     * @return string
     */
    public static function getGroupTypeName($groupType)
    {
        if ($groupType == 1) {
            return 'Əyani'; // Group type 1 için
        } elseif ($groupType == 2) {
            return 'Qiyabi'; // Group type 2 için
        }

        return 'Unknown'; // Diğer durumlar için
    }

    /**
     * Group Level'i kontrol ederek, adını döndürür.
     *
     * @param int $groupLevel
     * @return string
     */
    public static function getGroupLevelName($groupLevel)
    {
        if ($groupLevel == 1) {
            return 'Magistr'; // Group level 1 için
        } elseif ($groupLevel == 2) {
            return 'Bakalavr'; // Group level 2 için
        }

        return 'Unknown'; // Diğer durumlar için
    }
}
