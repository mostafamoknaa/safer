<?php

return [
    'validation_error' => 'خطأ في التحقق من البيانات.',
    'services' => [
        'not_enough_seats' => 'لا توجد مقاعد كافية متاحة.',
        'seat_already_booked' => 'المقعد رقم :seat محجوز بالفعل.',
        'invalid_seat_number' => 'رقم المقعد غير صحيح.',
        'request_created' => 'تم إنشاء طلب الخدمة بنجاح.',
    ],
    'events' => [
        'event_not_available' => 'الفعالية غير متاحة.',
        'not_enough_tickets' => 'لا توجد تذاكر كافية متاحة. المتاح: :available',
        'tickets_purchased' => 'تم شراء التذاكر بنجاح.',
    ],
    'hotels' => [
        'hotel_not_available' => 'الفندق غير متاح.',
        'room_not_available' => 'الغرفة غير متاحة.',
        'no_rooms_available' => 'لا توجد غرف متاحة في هذا الفندق.',
    ],
    'bookings' => [
        'created' => 'تم إنشاء الحجز بنجاح.',
        'cancelled' => 'تم إلغاء الحجز بنجاح.',
        'cannot_cancel' => 'لا يمكن إلغاء هذا الحجز.',
        'unauthorized' => 'غير مصرح لك بالوصول إلى هذا الحجز.',
        'room_not_available_dates' => 'الغرفة غير متاحة في التواريخ المحددة.',
    ],
    'payments' => [
        'created' => 'تم إنشاء الدفعة بنجاح.',
        'unauthorized' => 'غير مصرح لك بالوصول إلى هذه الدفعة.',
        'amount_exceeds_remaining' => 'المبلغ يتجاوز المبلغ المتبقي (:remaining ج.م).',
    ],
];

