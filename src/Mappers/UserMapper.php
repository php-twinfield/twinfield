<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\Response\Response;
use PhpTwinfield\User;
use PhpTwinfield\UserAccount;
use PhpTwinfield\UserPercentage;
use PhpTwinfield\Util;

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
        $user->setIsCurrentUser(Util::parseBoolean($userElement->getAttribute('iscurrentuser')));
        $user->setLevel($userElement->getAttribute('level'));
        $user->setResult($userElement->getAttribute('result'));
        $user->setStatus(self::parseEnumAttribute(\PhpTwinfield\Enums\Status::class, $userElement->getAttribute('status')));

        // Set the user elements from the user element
        $user->setAcceptExtraCost(Util::parseBoolean(self::getField($userElement, 'acceptextracost', $user)))
            ->setCulture(self::parseEnumAttribute(\PhpTwinfield\Enums\Culture::class, self::getField($userElement, 'culture', $user)))
            ->setCode(self::getField($userElement, 'code', $user))
            ->setCreated(self::parseDateTimeAttribute(self::getField($userElement, 'created', $user)))
            ->setDemo(Util::parseBoolean(self::getField($userElement, 'demo', $user)))
            ->setEmail(self::getField($userElement, 'email', $user))
            ->setExchangeQuota(self::getField($userElement, 'exchangequota', $user))
            ->setFileManagerQuota(self::getField($userElement, 'filemanagerquota', $user))
            ->setModified(self::parseDateTimeAttribute(self::getField($userElement, 'modified', $user)))
            ->setName(self::getField($userElement, 'name', $user))
            ->setRole(self::parseObjectAttribute(\PhpTwinfield\UserRole::class, $user, $userElement, 'method', array('level' => 'setLevel', 'name' => 'setName', 'shortname' => 'setShortName')))
            ->setShortName(self::getField($userElement, 'shortname', $user))
            ->setTouched(self::getField($userElement, 'touched', $user))
            ->setType(self::parseEnumAttribute(\PhpTwinfield\Enums\UserType::class, self::getField($userElement, 'type', $user)));

        // Set the user elements from the user element attributes
        $user->setCultureName(self::getAttribute($userElement, 'culture', 'name'))
            ->setCultureNativeName(self::getAttribute($userElement, 'culture', 'nativename'))
            ->setDemoLocked(self::getAttribute($userElement, 'demo', 'locked'))
            ->setExchangeQuotaLocked(self::getAttribute($userElement, 'exchangequota', 'locked'))
            ->setFileManagerQuotaLocked(self::getAttribute($userElement, 'filemanagerquota', 'locked'))
            ->setRoleLocked(self::getAttribute($userElement, 'role', 'locked'))
            ->setTypeLocked(self::getAttribute($userElement, 'type', 'locked'));

        // Return the complete object
        return $user;
    }
}
