<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactLink;
use App\Models\Faq;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    /**
     * Get contact information.
     */
    public function getContactInfo(): JsonResponse
    {
        $contacts = ContactLink::where('is_active', true)
            ->orderBy('id')
            ->get()
            ->map(function ($contact) {
                return [
                    'id' => $contact->id,
                    'type' => $contact->type,
                    'title' => app()->getLocale() === 'ar' ? $contact->title_ar : $contact->title_en,
                    'value' => $contact->url,   
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $contacts,
        ]);
    }

    /**
     * Send contact message.
     */
    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();

        // Here you would typically save the message to database or send email
        // For now, we'll just return success response
        
        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent successfully. We will get back to you soon.',
        ]);
    }

    /**
     * Get frequently asked questions.
     */
    public function getFAQ(): JsonResponse
    {
        $faqs = Faq::where('is_active', true)
            ->orderBy('id')
            ->get()
            ->map(function ($faq) {
                return [
                    'id' => $faq->id,
                    'question' => app()->getLocale() === 'ar' ? $faq->question_ar : $faq->question_en,
                    'answer' => app()->getLocale() === 'ar' ? $faq->answer_ar : $faq->answer_en,
                    'category' => $faq->category ?? 'general',
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $faqs,
        ]);
    }

    /**
     * Insert sample contact links.
     */
    public function insertContactLinks(): JsonResponse
    {
        $contacts = [
            [
                'type' => 'phone',
                'title_ar' => 'الهاتف',
                'title_en' => 'Phone',
                'url' => '+966123456789',
                'icon' => 'phone',
                'is_active' => true,
            ],
            [
                'type' => 'email',
                'title_ar' => 'البريد الإلكتروني',
                'title_en' => 'Email',
                'url' => 'info@saferplus.net',
                'icon' => 'email',
                'is_active' => true,
            ],
            [
                'type' => 'whatsapp',
                'title_ar' => 'واتساب',
                'title_en' => 'WhatsApp',
                'url' => 'https://wa.me/966123456789',
                'icon' => 'whatsapp',
                'is_active' => true,
            ],
        ];

        foreach ($contacts as $contact) {
            ContactLink::create($contact);
        }

        return response()->json([
            'success' => true,
            'message' => 'Contact links inserted successfully',
        ]);
    }

    /**
     * Insert sample FAQs.
     */
    public function insertFAQs(): JsonResponse
    {
        $faqs = [
            [
                'question_ar' => 'كيف يمكنني حجز فندق؟',
                'question_en' => 'How can I book a hotel?',
                'answer_ar' => 'يمكنك حجز فندق من خلال تصفح الفنادق المتاحة واختيار الغرفة المناسبة ثم إتمام عملية الدفع.',
                'answer_en' => 'You can book a hotel by browsing available hotels, selecting a suitable room, and completing the payment process.',
                'is_active' => true,
                'order_column' => 1,
            ],
            [
                'question_ar' => 'ما هي طرق الدفع المتاحة؟',
                'question_en' => 'What payment methods are available?',
                'answer_ar' => 'نقبل جميع بطاقات الائتمان الرئيسية وطرق الدفع الإلكترونية المحلية.',
                'answer_en' => 'We accept all major credit cards and local electronic payment methods.',
                'is_active' => true,
                'order_column' => 2,
            ],
            [
                'question_ar' => 'هل يمكنني إلغاء الحجز؟',
                'question_en' => 'Can I cancel my booking?',
                'answer_ar' => 'نعم، يمكنك إلغاء الحجز حسب سياسة الإلغاء الخاصة بكل فندق.',
                'answer_en' => 'Yes, you can cancel your booking according to each hotel\'s cancellation policy.',
                'is_active' => true,
                'order_column' => 3,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }

        return response()->json([
            'success' => true,
            'message' => 'FAQs inserted successfully',
        ]);
    }
}