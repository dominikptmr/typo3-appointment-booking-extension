<?php

declare(strict_types=1);

namespace Dominik\AppointmentBooking\Service;

use TYPO3\CMS\Core\Site\Entity\SiteSettings;

final class BookingSettingsValidator {
    public function validate(SiteSettings $settings): void {
        $duration = $settings->get('appointmentBooking.durationMinutes');
        $startingTime = $settings->get('appointmentBooking.startingTime');
        $endingTime = $settings->get('appointmentBooking.endingTime');
        $bufferDays = $settings->get('appointmentBooking.bufferDays');
        $advanceDays = $settings->get('appointmentBooking.advanceDays');
        $weekdays = $settings->get('appointmentBooking.availableWeekdays');

        // Validating duration
        if (!is_int($duration) || $duration <= 0) {
            throw new \InvalidArgumentException(
                'Termindauer muss eine ganze Zahl größer 0 sein.'
            );
        }

        // Validating startingTime
        if (!is_string($startingTime) || preg_match('/\A([01][0-9]|2[0-3]):[0-5][0-9]\z/', $startingTime) != 1) {
            throw new \InvalidArgumentException(
                'Startzeit muss eine gültige Uhrzeit im Format HH:MM sein.'
            );
        }

        // Validating endingTime
        if (!is_string($endingTime) || preg_match('/\A([01][0-9]|2[0-3]):[0-5][0-9]\z/', $endingTime) != 1) {
            throw new \InvalidArgumentException(
                'Endzeit muss eine gültige Uhrzeit im Format HH:MM sein.'
            );
        }

        // Validating startingTime < endingTime
        if ($endingTime <= $startingTime) {
            throw new \InvalidArgumentException(
                'Endzeit muss nach der Startzeit liegen.'
            );
        }

        // Validating bufferDays
        if (!is_int($bufferDays) || $bufferDays < 0) {
            throw new \InvalidArgumentException(
                'Die Anzahl der Terminvorlauftage muss eine ganze Zahl größer oder gleich 0 sein.'
            );
        }

        // Validating advanceDays
        if (!is_int($advanceDays) || $advanceDays < 0) {
            throw new \InvalidArgumentException(
                'Die Anzahl der Tage für Vorausbuchungen muss eine ganze Zahl größer oder gleich 0 sein.'
            );
        }

        // Validating bufferDays < advanceDays
        if ($advanceDays < $bufferDays) {
            throw new \InvalidArgumentException(
                'Die Anzahl der Tage für Vorausbuchungen darf nicht kleiner der Terminvorlauftage sein.'
            );
        }

        // Validating weekdays list
        if (!is_array($weekdays) || !array_is_list($weekdays)) {
            throw new \InvalidArgumentException(
                'Verfügbare Wochentage müssen als Liste angegeben werden.'
            );
        }

        // Validating each weekday
        foreach ($weekdays as $weekday) {
            if (!in_array($weekday, ['1', '2', '3', '4', '5', '6', '7'], true)) {
                throw new \InvalidArgumentException(
                    'Wochentage müssen als Werte von 1 - 7 angegeben werden.'
                );
            }
        }
    }
}
