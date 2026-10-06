<?php

declare(strict_types=1);

namespace Dominik\AppointmentBooking\Controller;
use Dominik\AppointmentBooking\Service\BookingSettingsValidator;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class BookingController extends ActionController
{
    public function indexAction(): ResponseInterface
    {
        $settings = $this->request->getAttribute('site')->getSettings();
        $validator = new BookingSettingsValidator();
        $validator->validate($settings);

        return $this->htmlResponse();
    }
}
?>