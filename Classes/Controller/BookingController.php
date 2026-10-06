<?php

declare(strict_types=1);

namespace Dominik\AppointmentBooking\Controller;

use Dominik\AppointmentBooking\Service\BookingSettingsValidator;
use Dominik\AppointmentBooking\Service\SlotGenerator;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class BookingController extends ActionController
{
    public function indexAction(): ResponseInterface
    {   
        //Get settings
        $settings = $this->request->getAttribute('site')->getSettings();
        
        //Validate settings
        $validator = new BookingSettingsValidator();
        $validator->validate($settings);

        //Generate appointment timeslots
        $generator = new SlotGenerator();
        $timeslots = $generator->generate($settings);

        return $this->htmlResponse();
    }
}
?>