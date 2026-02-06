<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ServiceRequest;
use App\Models\EventTicket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CancellationController extends Controller
{
    /**
     * Cancel a hotel booking.
     */
    public function cancelHotelBooking(Request $request, Booking $booking): JsonResponse
    {
        if ($booking->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بإلغاء هذا الحجز',
            ], 403);
        }

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن إلغاء هذا الحجز في الوقت الحالي',
            ], 400);
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $validated['reason'],
            'cancelled_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إلغاء الحجز بنجاح',
            'data' => [
                'booking_id' => $booking->id,
                'booking_reference' => $booking->booking_reference,
                'cancelled_at' => $booking->cancelled_at->format('Y-m-d H:i:s'),
                'cancellation_reason' => $booking->cancellation_reason,
            ],
        ]);
    }

    /**
     * Cancel a service request (bus, car, trip).
     */
    public function cancelServiceRequest(Request $request, ServiceRequest $serviceRequest): JsonResponse
    {
        if ($serviceRequest->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بإلغاء هذا الطلب',
            ], 403);
        }

        if (!in_array($serviceRequest->status, ['pending', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن إلغاء هذا الطلب في الوقت الحالي',
            ], 400);
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $serviceRequest->update([
            'status' => 'cancelled',
            'cancellation_reason' => $validated['reason'],
            'cancelled_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إلغاء الطلب بنجاح',
            'data' => [
                'request_id' => $serviceRequest->id,
                'request_reference' => $serviceRequest->request_reference,
                'service_type' => $serviceRequest->service_type,
                'cancelled_at' => $serviceRequest->cancelled_at->format('Y-m-d H:i:s'),
                'cancellation_reason' => $serviceRequest->cancellation_reason,
            ],
        ]);
    }

    /**
     * Cancel an event ticket.
     */
    public function cancelEventTicket(Request $request, EventTicket $eventTicket): JsonResponse
    {
        if ($eventTicket->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بإلغاء هذا التذكرة',
            ], 403);
        }

        if (!in_array($eventTicket->status, ['pending', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن إلغاء هذه التذكرة في الوقت الحالي',
            ], 400);
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $eventTicket->update([
            'status' => 'cancelled',
            'cancellation_reason' => $validated['reason'],
            'cancelled_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إلغاء التذكرة بنجاح',
            'data' => [
                'ticket_id' => $eventTicket->id,
                'ticket_reference' => $eventTicket->ticket_reference,
                'cancelled_at' => $eventTicket->cancelled_at->format('Y-m-d H:i:s'),
                'cancellation_reason' => $eventTicket->cancellation_reason,
            ],
        ]);
    }

    /**
     * Get cancellation reasons list.
     */
    public function getCancellationReasons(): JsonResponse
    {
        $reasons = [
            'change_of_plans' => 'تغيير في الخطط',
            'emergency' => 'ظروف طارئة',
            'financial_issues' => 'مشاكل مالية',
            'health_issues' => 'مشاكل صحية',
            'weather_conditions' => 'الأحوال الجوية',
            'work_commitments' => 'التزامات العمل',
            'family_reasons' => 'أسباب عائلية',
            'travel_restrictions' => 'قيود السفر',
            'service_quality' => 'جودة الخدمة',
            'better_alternative' => 'وجود بديل أفضل',
            'other' => 'أخرى',
        ];

        return response()->json([
            'success' => true,
            'data' => $reasons,
        ]);
    }
}