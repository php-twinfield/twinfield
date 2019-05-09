<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\Response\Response;
use PhpTwinfield\User;
use PhpTwinfield\UserAccount;
use PhpTwinfield\UserPercentage;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class UserMapper extends BaseMapper
{
    /**
     * Maps a Response object to a clean User entity.
     *
     * @access public
     *
     * @param \PhpTwinfield\Response\Response $response
     *
     * @return User
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response)
    {
        // Generate new User object
        $user = new User();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Get the root/user element
        $userElement = $responseDOM->documentElement;

        // Set the iscurrentuser, level, result and status attribute
        $user->setIsCurrentUser(self::parseBooleanAttribute($userElement->getAttribute('iscurrentuser')));
        $user->setLevel($userElement->getAttribute('level'));
        $user->setResult($userElement->getAttribute('result'));
        $user->setStatus(self::parseEnumAttribute('Status', $userElement->getAttribute('status')));

        // Set the user elements from the user element
        $user->setCode(self::getField($user, $userElement, 'code'))
            ->setCreated(self::parseDateTimeAttribute(self::getField($user, $userElement, 'created')))
            ->setModified(self::parseDateTimeAttribute(self::getField($user, $userElement, 'modified')))
            ->setName(self::getField($user, $userElement, 'name'))
            ->setShortName(self::getField($user, $userElement, 'shortname'))
            ->setTouched(self::getField($user, $userElement, 'touched'));

        // Return the complete object
        return $user;
    }
}
