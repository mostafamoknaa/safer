# Cancellation API Documentation

## Overview
This API provides endpoints for users to cancel their bookings (hotels, events, bus/car services) with mandatory cancellation reasons.

## Endpoints

### 1. Get Cancellation Reasons
**GET** `/api/cancellation/reasons`

Returns a list of predefined cancellation reasons.

**Response:**
```json
{
    "success": true,
    "data": {
        "change_of_plans": "تغيير في الخطط",
        "emergency": "ظروف طارئة",
        "financial_issues": "مشاكل مالية",
        "health_issues": "مشاكل صحية",
        "weather_conditions": "الأحوال الجوية",
        "work_commitments": "التزامات العمل",
        "family_reasons": "أسباب عائلية",
        "travel_restrictions": "قيود السفر",
        "service_quality": "جودة الخدمة",
        "better_alternative": "وجود بديل أفضل",
        "other": "أخرى"
    }
}
```

### 2. Cancel Hotel Booking
**POST** `/api/cancellation/hotel/{booking_id}`

Cancels a hotel booking with a reason.

**Request Body:**
```json
{
    "reason": "change_of_plans"
}
```

**Response:**
```json
{
    "success": true,
    "message": "تم إلغاء الحجز بنجاح",
    "data": {
        "booking_id": 123,
        "booking_reference": "BK-ABC123",
        "cancelled_at": "2026-02-02 10:15:30",
        "cancellation_reason": "change_of_plans"
    }
}
```

### 3. Cancel Service Request (Bus/Car/Trip)
**POST** `/api/cancellation/service/{service_request_id}`

Cancels a service request with a reason.

**Request Body:**
```json
{
    "reason": "emergency"
}
```

**Response:**
```json
{
    "success": true,
    "message": "تم إلغاء الطلب بنجاح",
    "data": {
        "request_id": 456,
        "request_reference": "SR-DEF456",
        "service_type": "bus",
        "cancelled_at": "2026-02-02 10:15:30",
        "cancellation_reason": "emergency"
    }
}
```

### 4. Cancel Event Ticket
**POST** `/api/cancellation/event/{event_ticket_id}`

Cancels an event ticket with a reason.

**Request Body:**
```json
{
    "reason": "health_issues"
}
```

**Response:**
```json
{
    "success": true,
    "message": "تم إلغاء التذكرة بنجاح",
    "data": {
        "ticket_id": 789,
        "ticket_reference": "ET-GHI789",
        "cancelled_at": "2026-02-02 10:15:30",
        "cancellation_reason": "health_issues"
    }
}
```

## Updated Existing Endpoints

### Hotel Booking Cancellation (Updated)
**POST** `/api/bookings/{booking_id}/cancel`

Now requires a cancellation reason.

**Request Body:**
```json
{
    "reason": "change_of_plans"
}
```

## Database Changes

The following fields have been added to these tables:
- `bookings`: `cancellation_reason`, `cancelled_at`
- `service_requests`: `cancellation_reason`, `cancelled_at`
- `event_tickets`: `cancellation_reason`, `cancelled_at`

## Error Responses

**403 Unauthorized:**
```json
{
    "success": false,
    "message": "غير مصرح لك بإلغاء هذا الحجز"
}
```

**400 Bad Request:**
```json
{
    "success": false,
    "message": "لا يمكن إلغاء هذا الحجز في الوقت الحالي"
}
```

**422 Validation Error:**
```json
{
    "success": false,
    "message": "خطأ في التحقق من البيانات",
    "errors": {
        "reason": ["حقل السبب مطلوب"]
    }
}
```

## Notes

1. Only bookings with status `pending` or `confirmed` can be cancelled
2. Cancellation reason is mandatory for all cancellation requests
3. The `cancelled_at` timestamp is automatically set when cancellation occurs
4. All existing booking/request listing endpoints now include cancellation information