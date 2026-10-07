<?php

declare(strict_types=1);

namespace Dominik\AppointmentBooking\Controller;

use Dominik\AppointmentBooking\Service\BookingSettingsValidator;
use Dominik\AppointmentBooking\Service\SlotGenerator;
use Dominik\AppointmentBooking\Service\BookingService;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class BookingController extends ActionController
{
    public function __construct(
        private readonly BookingService $bookingService,
        private readonly SlotGenerator $slotGenerator
        ) {}
    
    public function indexAction(): ResponseInterface
    {   
        //Get settings
        $settings = $this->request->getAttribute('site')->getSettings();
        
        //Validate settings
        $validator = new BookingSettingsValidator();
        $validator->validate($settings);

        //Generate appointment timeslots
        $timeslotsByDay = $this->slotGenerator->generate($settings);

        $this->view->assign('timeslotsByDay', $timeslotsByDay);

        return $this->htmlResponse();
    }

    public function bookAction(string $name, string $email, string $selectedSlotInput): ResponseInterface {
        $settings = $this->request->getAttribute('site')->getSettings();

        $this->bookingService->bookAppointment(
            $name,
            $email,
            $selectedSlotInput,
            $settings
        );

        $this->addFlashMessage('Termin wurde gebucht.');

        return $this->redirect('index');
    }
}
?>