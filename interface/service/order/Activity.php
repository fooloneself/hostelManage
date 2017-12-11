<?php
namespace service\order;
use common\components\Server;
use service\guest\Guest;

class Activity extends Server{
    protected $guest;
    protected $order;
    public function __construct(Guest $guest,Order $order)
    {

    }
}