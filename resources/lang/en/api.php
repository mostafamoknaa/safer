<?php

return [
    'validation_error' => 'Validation error.',
    'services' => [
        'not_enough_seats' => 'Not enough seats available.',
        'seat_already_booked' => 'Seat number :seat is already booked.',
        'invalid_seat_number' => 'Invalid seat number.',
        'request_created' => 'Service request created successfully.',
    ],
    'events' => [
        'event_not_available' => 'Event is not available.',
        'not_enough_tickets' => 'Not enough tickets available. Available: :available',
        'tickets_purchased' => 'Tickets purchased successfully.',
    ],
    'hotels' => [
        'hotel_not_available' => 'Hotel is not available.',
        'room_not_available' => 'Room is not available.',
        'no_rooms_available' => 'No rooms available in this hotel.',
    ],
    'bookings' => [
        'created' => 'Booking created successfully.',
        'cancelled' => 'Booking cancelled successfully.',
        'cannot_cancel' => 'Cannot cancel this booking.',
        'unauthorized' => 'You are not authorized to access this booking.',
        'room_not_available_dates' => 'Room is not available for the selected dates.',
    ],
    'payments' => [
        'created' => 'Payment created successfully.',
        'unauthorized' => 'You are not authorized to access this payment.',
        'amount_exceeds_remaining' => 'Amount exceeds remaining amount (:remaining EGP).',
    ],
];

