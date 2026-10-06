<?php

declare(strict_types=1);

use Dominik\AppointmentBooking\Controller\BookingController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

ExtensionUtility::configurePlugin(
    'AppointmentBooking',
    'Booking',
    [
        BookingController::class => 'index',
    ],
    [
        BookingController::class => 'index'
    ],
);
?>