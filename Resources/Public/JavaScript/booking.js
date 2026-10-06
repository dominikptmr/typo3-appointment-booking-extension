const timeslots = document.querySelectorAll('.appointment-slot');

const selectedSlotInput = document.getElementById('selectedSlotInput');

const formButton = document.querySelector(
    '.appointment-form button[type="submit"]'
);

if (selectedSlotInput && formButton) {
    timeslots.forEach(function (slotButton) {

        slotButton.addEventListener('click', function () {

            // Clear other buttons
            timeslots.forEach(function (otherButtons) {
                otherButtons.setAttribute('aria-pressed', 'false');
            });

            // Highlight current slot
            slotButton.setAttribute('aria-pressed', 'true');

            // Store date and time
            selectedSlotInput.value = slotButton.value;

            // Enable form button
            formButton.disabled = false;
        });

    });
}