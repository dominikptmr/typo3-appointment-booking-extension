<?php

declare(strict_types=1);

namespace Dominik\AppointmentBooking\Service;

use TYPO3\CMS\Core\Site\Entity\SiteSettings;
use Dominik\AppointmentBooking\Domain\Repository\AppointmentRepository;


final class BookingService {

    public function __construct(private readonly AppointmentRepository $appointmentRepository) {}

    public function bookAppointment(string $name, string $email, string $selectedSlotInput, SiteSettings $settings):void {
        $duration = $settings->get('appointmentBooking.durationMinutes');
        $timezone = new \DateTimeZone($settings->get('appointmentBooking.timezone'));

        $appointmentStart = new \DateTimeImmutable($selectedSlotInput, $timezone);
        $appointmentEnd = $appointmentStart->add(new \DateInterval("PT{$duration}M"));

        $this->appointmentRepository->saveAppointment(
            $name,
            $email,
            $appointmentStart,
            $appointmentEnd
        );
    }
}