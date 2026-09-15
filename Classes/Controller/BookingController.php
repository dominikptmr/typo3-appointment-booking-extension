<?php

declare(strict_types=1);

namespace Dominik\AppointmentBooking\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class BookingController extends ActionController
{
    public function indexAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }
}
?>