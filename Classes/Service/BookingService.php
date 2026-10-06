<?php

declare(strict_types=1);

namespace Dominik\AppointmentBooking\Service;

use TYPO3\CMS\Core\Database\ConnectionPool;

use TYPO3\CMS\Core\Site\Entity\SiteSettings;


final class BookingService {

    public function __construct(private readonly ConnectionPool $connectionPool) {}

    public function bookAppointment(string $name, string $email, string $selectedSlotInput, SiteSettings $settings):void {

        $duration = $settings->get('appointmentBooking.durationMinutes');

        $timezone = new \DateTimeZone($settings->get('appointmentBooking.timezone'));

        $appointmentStart = new \DateTimeImmutable($selectedSlotInput, $timezone);
        $appointmentEnd = $appointmentStart->add(new \DateInterval("PT{$duration}M"));
        
        $connection = $this->connectionPool->getConnectionForTable('appointments');

        $connection->insert('appointments', [
            'name' => $name,
            'email' => $email,
            'appointment_start' => $appointmentStart->format('Y-m-d H:i:s'),
            'appointment_end' => $appointmentEnd->format('Y-m-d H:i:s'),
        ]);
    }
}