<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 2020-04-21
 * Time: 23:56
 */

namespace App\Service\Interfaces;


use App\Entity\Space;

interface SpaceServiceInterface
{
    /**
     * Retrieves space data
     *
     * @param string $spaceId – Uniq Space identifier
     * @param string $token – Token with Space info access
     * @return Space|null
     */
    public function pullSpaceData(string $spaceId, string $token): ?Space;

    /**
     * Searches space data in DB
     *
     * @param string $spaceId
     * @return Space|null
     */
    public function findExistingSpace(string $spaceId): ?Space;
}