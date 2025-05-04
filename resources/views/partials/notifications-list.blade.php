{{-- notifications-list.blade.php --}}
@isset($notifications)
{{-- @ --}}
@forelse($notifications as $notification)
    @php
    $notificationData = is_array($notification->data) ? $notification->data : json_decode($notification->data, true);
    $invoiceData = $notificationData['data'] ?? $notificationData;
    $isHtmlContent = is_string($invoiceData) && strpos($invoiceData, '<br>') !== false;
    @endphp

    <div class="notification-item" data-id="{{ $notification->id }}">
        <a href="{{ route('invoices.show', $invoiceData['invoiceId'] ?? '#') }}" class="dropdown-item d-flex align-items-center py-3 border-bottom mark-as-read">
            <div class="notification-icon mr-3">
                <i class="fas fa-file-invoice text-{{ ($invoiceData['Status'] ?? '') == 'مدفوعة' ? 'success' : (($invoiceData['Status'] ?? '') == 'غير مدفوعة' ? 'danger' : 'warning') }}"></i>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between">
                    <strong class="text-dark">{{ $invoiceData['user_send'] ?? 'النظام' }}</strong>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
                <div class="text-muted mb-1">{{ $invoiceData['Status'] ?? 'تنبيه جديد' }}</div>
                <div class="d-flex justify-content-between small">
                    <span>#{{ $invoiceData['invoiceId'] ?? '---' }}</span>
                    <span>{{ $invoiceData['Total'] ?? 0 }} ر.س</span>
                </div>
            </div>
        </a>
    </div>
@empty
    <div class="p-3 text-center text-muted">لا توجد إشعارات جديدة</div>
@endforelse
@else
    <div class="p-3 text-center text-muted">لم يتم تمرير متغير الإشعارات</div>
@endisset
