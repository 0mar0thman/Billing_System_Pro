@php
    function getInitials($name)
    {
        $parts = explode(' ', trim($name));
        $count = count($parts);

        if ($count === 1) {
            return strtoupper(mb_substr($parts[0], 0, 1));
        } elseif ($count === 2) {
            return strtoupper(mb_substr($parts[0], 0, 1) . mb_substr($parts[1], 0, 1));
        } else {
            return strtoupper(mb_substr($parts[0], 0, 1) . mb_substr($parts[$count - 1], 0, 1));
        }
    }
@endphp
<!-- Sidebar-right-->
<div class="sidebar sidebar-left sidebar-animate">
    <div class="panel panel-primary card mb-0 box-shadow">
        <div class="tab-menu-heading border-0 p-3">
            <div>
                <div class="card-title mb-2">
                    <h4><b>Notifications</b></h4>
                </div>
                <div id="notifications_count">
                    <h6>تنبيه: {{ auth()->user()->unreadNotifications->count() }}</h6>
                </div>
                <a href="{{ route('mark_all_notifications') }}" class="btn btn-sm btn-success w-100"><b>رؤية الكل</b></a>
            </div>
            <div class="card-options mr-auto">
                <a href="#" class="sidebar-remove"><i class="fe fe-x"></i></a>
            </div>
        </div>
        <div class="panel-body tabs-menu-body latest-tasks p-0 border-0">
            <div class="tabs-menu"></div>

            <!-- Notification count -->
            <div class="p-3">
                <b>Total: {{ auth()->user()->unreadNotifications->count() }}</b>
            </div>
            <div class="tab-content" id="unreadNotifications">
                <div class="tab-pane active" id="side2">
                    @forelse(auth()->user()->unreadNotifications as $notification)
                        <div class="list d-flex align-items-center border-bottom p-3">
                            <div class="">
                                <span class="avatar bg-primary brround avatar-md">
                                    {{ getInitials($notification->data['user_send']) }}
                                </span>
                            </div>
                            <a class="wrapper w-100 mr-3" href="{{ route('read_notification', $notification->id) }}">
                                <p class="mb-0 d-flex">
                                    <b>{{ $notification->data['product_name'] ?? 'New Notification' }}</b>
                                </p>
                                <p class="mb-0">اجمالي: {{ $notification->data['Total'] ?? '0.00' }}</p>
                                <p class="mb-0">بواسطة: {{ $notification->data['user_send'] ?? 'Unknown' }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-clock text-muted ml-1"></i>
                                        <small class="text-muted ml-auto">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </a>
                        </div>

                    @empty
                        <div class="p-3 text-center text-muted">No new notifications</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
<!--/Sidebar-right-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
