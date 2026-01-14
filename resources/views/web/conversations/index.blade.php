@extends('layouts.web')

@section('title', 'المحادثات')

@section('content')
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">المحادثات</h1>

        <div class="bg-white rounded-lg shadow-md">
            <!-- Chat Header -->
            <div class="border-b p-4">
                <h2 class="text-xl font-semibold">
                    الدعم الفني
                    @if($conversation->hotelManager)
                        - {{ $conversation->hotelManager->name }}
                    @elseif($conversation->admin)
                        - {{ $conversation->admin->name }}
                    @endif
                </h2>
            </div>

            <!-- Messages -->
            <div class="p-4 h-96 overflow-y-auto" id="messages-container">
                @forelse($conversation->messages as $message)
                    <div class="mb-4 {{ $message->sender_type == 'user' ? 'text-right' : 'text-left' }}">
                        <div class="inline-block max-w-md">
                            <div class="bg-{{ $message->sender_type == 'user' ? 'blue' : 'gray' }}-100 rounded-lg p-3">
                                <p class="font-semibold text-sm mb-1">{{ $message->sender->name }}</p>

                                @if($message->type == 'file')
                                    <div class="mb-2">
                                        <a href="{{ Storage::url($message->file_path) }}" target="_blank"
                                            class="text-blue-600 hover:underline flex items-center">
                                            📎 {{ $message->file_name }}
                                        </a>
                                    </div>
                                @endif

                                @if($message->message)
                                    <p class="text-gray-800">{{ $message->message }}</p>
                                @endif

                                <p class="text-xs text-gray-500 mt-1">
                                    {{ \Carbon\Carbon::parse($message->created_at)->format('Y-m-d H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-12">
                        لا توجد رسائل بعد. ابدأ المحادثة!
                    </div>
                @endforelse
            </div>

            <!-- Message Input -->
            <div class="border-t p-4">
                <form method="POST" action="{{ route('web.conversations.send-message') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="flex gap-2">
                        <input type="text" name="message" placeholder="اكتب رسالتك..."
                            class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

                        <label
                            class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 cursor-pointer flex items-center">
                            📎
                            <input type="file" name="file" class="hidden"
                                onchange="this.form.querySelector('.file-name').textContent = this.files[0]?.name || ''">
                        </label>

                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                            إرسال
                        </button>
                    </div>
                    <p class="text-sm text-gray-500 mt-1 file-name"></p>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Auto scroll to bottom
            const messagesContainer = document.getElementById('messages-container');
            if (messagesContainer) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        </script>
    @endpush
@endsection