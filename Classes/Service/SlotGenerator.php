<?php

declare(strict_types=1);

namespace Dominik\AppointmentBooking\Service;

use TYPO3\CMS\Core\Site\Entity\SiteSettings;

final class SlotGenerator {
    public function generate(SiteSettings $settings): array {

        $timeslotsByDay = [];

        //Get settings
        $duration = $settings->get('appointmentBooking.durationMinutes');
        $startingTime = $settings->get('appointmentBooking.startingTime');
        $endingTime = $settings->get('appointmentBooking.endingTime');
        $weekdays = $settings->get('appointmentBooking.availableWeekdays');
        $bufferDays = $settings->get('appointmentBooking.bufferDays');
        $advanceDays = $settings->get('appointmentBooking.advanceDays');

        // Set first and last day (00:00)
        $now = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Berlin'));
        $today = $now->setTime(0, 0);
        $firstDay = $today->modify("+{$bufferDays} days"); // first available day
        $lastDay = $today->modify("+{$advanceDays} days"); // last available day

        // Set appointment interval in minutes
        $interval = new \DateInterval("PT{$duration}M");


        for ($day = $firstDay; $day <= $lastDay; $day = $day->modify('+1 day')) {
            
            // Check if current weekday is included in available weekdays
            if (!in_array($day->format('N'), $weekdays, true)) {
                continue;
            }

            $start = $day->modify($startingTime);   // Start of first appointment slot
            $dayEnd = $day->modify($endingTime);    // End time of the day

            // Generate timeslots
            while (true) {
                $end = $start->add($interval); //End of current appointment slot
                
                // Dont'include appointment if it doesn't fit into the last slot
                if ($end > $dayEnd) {
                    break;
                }

                //Include appointment if it starts after the current date and time
                if ($start > $now) {
                    $appointment = [
                        'start' => $start,
                        'end' => $end
                    ];
                    
                    $date = $day->format('Y-m-d');

                    if (!isset($timeslotsByDay[$date])) {
                        $timeslotsByDay[$date] = [];
                    }

                    array_push($timeslotsByDay[$date], $appointment);
                }

                //Set start of next appointment slot to the end of the current clot
                $start = $end;
            }
        }

        return $timeslotsByDay;

    }
}