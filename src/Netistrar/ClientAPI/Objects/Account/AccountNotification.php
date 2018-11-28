<?php

namespace Netistrar\ClientAPI\Objects\Account;

use Kinikit\Core\Object\SerialisableObject;
/**
 * A generic account notification.  This are used to encode events described by the <a href="#notificationType">notificationType</a> and <a href="#notificationSubType">notificationSubType</a> properties.
 * A human readable message is also returned along with the description of an associated object contained in the <a href="#refersTo">refersTo</a> property if relevant to this notification.
 */
class AccountNotification extends SerialisableObject {

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $dateTime;

    /**
     * @var string
     */
    protected $notificationType;

    /**
     * @var string
     */
    protected $notificationSubType;

    /**
     * @var string
     */
    protected $refersTo;

    /**
     * @var string
     */
    protected $message;



    /**
     * Constructor
     *
    */
    public function __construct(){

        
    }

    /**
     * Get the id
     *
     * @return integer
     */
    public function getId(){
        return $this->id;
    }

    /**
     * Get the dateTime
     *
     * @return string
     */
    public function getDateTime(){
        return $this->dateTime;
    }

    /**
     * Get the notificationType
     *
     * @return string
     */
    public function getNotificationType(){
        return $this->notificationType;
    }

    /**
     * Get the notificationSubType
     *
     * @return string
     */
    public function getNotificationSubType(){
        return $this->notificationSubType;
    }

    /**
     * Get the refersTo
     *
     * @return string
     */
    public function getRefersTo(){
        return $this->refersTo;
    }

    /**
     * Get the message
     *
     * @return string
     */
    public function getMessage(){
        return $this->message;
    }


}