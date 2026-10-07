<?php

declare(strict_types=1);

namespace Dominik\AppointmentBooking\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;

final class AppointmentRepository {
    
    public function __construct(private readonly ConnectionPool $connectionPool) {}

    public function saveAppointment(string $name, string $email, \DateTimeImmutable $start, \DateTimeImmutable $end) {
        $connection = $this->connectionPool->getConnectionForTable('appointments');
        
        $connection->insert('appointments', [
            'name' => $name,
            'email' => $email,
            'appointment_start' => $start->format('Y-m-d H:i:s'),
            'appointment_end' => $end->format('Y-m-d H:i:s'),
        ]);
    }

    public function getOverlappingAppointments(
        \DateTimeImmutable $bookingWindowStart,
        \DateTimeImmutable $bookingWindowEnd
    ): array {
        $connection = $this->connectionPool->getConnectionForTable('appointments');

        $overlappingAppointments = $connection->executeQuery('
            SELECT appointment_start, appointment_end
            FROM appointments
            WHERE appointment_start < :end
            AND appointment_end > :start
            ORDER BY appointment_start ASC;
        ', [
        'start' => $bookingWindowStart->format('Y-m-d H:i:s'),
        'end' => $bookingWindowEnd->format('Y-m-d H:i:s')
        ]);

        return  $overlappingAppointments->fetchAllAssociative();
    }
}