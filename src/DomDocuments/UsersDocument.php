<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\User;

/**
 * The Document Holder for making new XML User. Is a child class
 * of DOMDocument and makes the required DOM tree for the interaction in
 * creating a new User.
 *
 * @package PhpTwinfield
 * @subpackage User\DOM
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class UsersDocument extends BaseDocument
{
    final protected function getRootTagName(): string
    {
        return "users";
    }

    /**
     * Turns a passed User class into the required markup for interacting
     * with Twinfield.
     *
     * This method doesn't return anything, instead just adds the User to
     * this DOMDOcument instance for submission usage.
     *
     * @access public
     * @param User $user
     * @return void | [Adds to this instance]
     */
    public function addUser(User $user)
    {
        $userElement = $this->createElement('user');
        $this->rootElement->appendChild($userElement);

        $status = $user->getStatus();

        if (!empty($status)) {
            $userElement->setAttribute('status', $status);
        }

        $userElement->appendChild($this->createNodeWithTextContent('code', $user->getCode()));
        $userElement->appendChild($this->createNodeWithTextContent('name', $user->getName()));
        $userElement->appendChild($this->createNodeWithTextContent('shortname', $user->getShortName()));
    }
}
