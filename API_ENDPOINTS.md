# API Endpoints Documentation

## Base URL
```
http://your-domain.com/api
```

## Authentication
All protected routes require authentication using Sanctum. Include the token in the Authorization header:
```
Authorization: Bearer {token}
```

---

## Public Endpoints

### Authentication
- `POST /register` - Register new user
- `POST /login` - Login user

### Provinces
- `GET /provinces` - Get all active provinces

### Hotels (Public)
- `GET /hotels` - Get all active hotels (with filters: province_id, search)
- `GET /hotels/{hotel}` - Get hotel details with rooms and media
- `GET /hotels/{hotel}/rooms` - Get hotel rooms (with filters: min_price, max_price, beds_count, check_in_date, check_out_date)

### Services (Public)
- `GET /services/buses` - Get all active buses
- `GET /services/trips` - Get available trips (with filters: departure_location, arrival_location, date)
- `GET /services/trips/{trip}` - Get trip details with available seats
- `GET /services/private-cars` - Get all active private cars

### Events (Public)
- `GET /events` - Get all active events (with filter: date)
- `GET /events/{event}` - Get event details

---

## Protected Endpoints (Require Authentication)

### User
- `GET /me` - Get authenticated user info
- `GET /user` - Get authenticated user info
- `POST /logout` - Logout user

### Hotels
- `POST /hotels/check-availability` - Check room availability for specific dates
  - Body: `room_id`, `check_in_date`, `check_out_date`

### Bookings
- `GET /bookings` - Get user bookings (with filters: status, hotel_id)
- `GET /bookings/{booking}` - Get booking details
- `POST /bookings` - Create new booking
  - Body: `hotel_id`, `room_id` (optional), `check_in_date`, `check_out_date`, `guests_count`, `rooms_count`, `notes` (optional)
- `POST /bookings/{booking}/cancel` - Cancel a booking

### Payments
- `GET /payments` - Get user payments (with filters: status, booking_id)
- `GET /payments/{payment}` - Get payment details
- `POST /payments` - Create payment for booking
  - Body: `booking_id`, `amount`, `payment_method` (cash|card|bank_transfer|online|other), `transaction_id` (optional), `notes` (optional)

### Conversations
- `GET /conversation` - Get or create conversation with admin
- `POST /conversation/message` - Send message in conversation
- `GET /conversation/unread-count` - Get unread messages count

### Services (Protected)
- `POST /services/bus-request` - Create bus service request
  - Body: `trip_id`, `passengers_count`, `selected_seats` (optional array), `notes` (optional)
- `POST /services/private-car-request` - Create private car service request
  - Body: `private_car_id`, `duration_hours`, `start_date`, `notes` (optional)
- `GET /services/my-requests` - Get user service requests

### Events (Protected)
- `POST /events/purchase` - Purchase event tickets
  - Body: `event_id`, `tickets_count`, `notes` (optional)
- `GET /events/my-tickets` - Get user event tickets

---

## Response Format

### Success Response
```json
{
    "success": true,
    "data": { ... },
    "message": "Optional message"
}
```

### Error Response
```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        "field": ["Error message"]
    }
}
```

---

## Status Codes
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error

---

## Notes
- All dates should be in format: `Y-m-d` (e.g., `2024-12-25`)
- All datetime fields should be in format: `Y-m-d H:i:s`
- Prices are returned as floats
- The API supports both Arabic and English locales based on `Accept-Language` header or `app()->getLocale()`

