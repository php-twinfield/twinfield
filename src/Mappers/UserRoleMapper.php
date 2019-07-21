<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\Response\Response;
use PhpTwinfield\UserRole;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class UserRoleMapper extends BaseMapper
{
    /**
     * Maps a Response object to a clean UserRole entity.
     *
     * @access public
     *
     * @param \PhpTwinfield\Response\Response $response
     *
     * @return UserRole
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response)
    {
        // Generate new UserRole object
        $userRole = new UserRole();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Get the root/user role element
        $userRoleElement = $responseDOM->documentElement;

        // Set the user role elements from the user role element
        $userRole->setCode(self::getField($userRoleElement, 'code', $userRole))
            ->setName(self::getField($userRoleElement, 'name', $userRole))
            ->setShortName(self::getField($userRoleElement, 'shortname', $userRole));

        // Return the complete object
        return $userRole;
    }
}
