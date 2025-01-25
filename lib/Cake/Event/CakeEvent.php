<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	  Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link		  https://cakephp.org CakePHP(tm) Project
 * @package		  Cake.Observer
 * @since		  CakePHP(tm) v 2.1
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Represents the transport class of events across the system. It receives a name, subject and an optional
 * payload. The name can be any string that uniquely identifies the event across the application, while the subject
 * represents the object that the event applies to.
 *
 * @package Cake.Event
 */
class CakeEvent {

/**
 * PHP 8.2 deprecation notice: added to avoid `Creation of dynamic property ... is deprecated.`
 * @var mixed|true
 */
	public mixed $break;

/**
 * PHP 8.2 deprecation notice: added to avoid `Creation of dynamic property ... is deprecated.`
 * @var mixed|true
 */
	public mixed $modParams;

/**
 * PHP 8.2 deprecation notice: added to avoid `Creation of dynamic property ... is deprecated.`
 * @var array|mixed
 */
	public mixed $breakOn;

/**
 * PHP 8.2 deprecation notice: added to avoid `Creation of dynamic property ... is deprecated.`
 * @var array|mixed
 */
	public mixed $omitSubject;

/**
 * PHP 8.2 deprecation notice: added to avoid `Creation of dynamic property ... is deprecated.`
 * @var mixed
 */
	public mixed $collectReturn;

/**
 * Property used to retain the result value of the event listeners
 *
 * @var mixed
 */
	public $result = null;

/**
 * Flags an event as stopped or not, default is false
 *
 * @var bool
 */
	protected $_stopped = false;

/**
     * Constructor
     *
     * @param string $_name Name of the event
     * @param object $_subject the object that this event applies to (usually the object that is generating the event)
     * @param mixed $data any value you wish to be transported with this event to it can be read by listeners
     *
     * ## Examples of usage:
     *
     * ```
     *	$event = new CakeEvent('Order.afterBuy', $this, array('buyer' => $userData));
     *	$event = new CakeEvent('User.afterRegister', $UserModel);
     * ```
     */
    public function __construct(
        /**
         * Name of the event
         */
        protected $_name,
        /**
         * The object this event applies to (usually the same object that generates the event)
         */
        protected $_subject = null,
        /**
         * Custom data for the method that receives the event
         */
        public $data = null
    )
    {
    }

/**
 * Dynamically returns the name and subject if accessed directly
 *
 * @param string $attribute Attribute name.
 * @return mixed
 */
	public function __get($attribute) {
		if ($attribute === 'name' || $attribute === 'subject') {
			return $this->{$attribute}();
		}
	}

/**
 * Returns the name of this event. This is usually used as the event identifier
 *
 * @return string
 */
	public function name() {
		return $this->_name;
	}

/**
 * Returns the subject of this event
 *
 * @return object
 */
	public function subject() {
		return $this->_subject;
	}

/**
 * Stops the event from being used anymore
 *
 * @return bool
 */
	public function stopPropagation() {
		return $this->_stopped = true;
	}

/**
 * Check if the event is stopped
 *
 * @return bool True if the event is stopped
 */
	public function isStopped() {
		return $this->_stopped;
	}

}
