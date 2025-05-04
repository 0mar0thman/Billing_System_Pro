/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});

import ExampleComponent from './components/ExampleComponent.vue';
app.component('example-component', ExampleComponent);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.mount('#app');





// في ملف resources/js/app.js
document.addEventListener('DOMContentLoaded', function() {
    // تحديث الإشعارات بدون تحديث الصفحة
    const notificationList = document.getElementById('notificationList');
    const unreadCount = document.getElementById('unreadCount');
    const liveUnreadCount = document.getElementById('liveUnreadCount');

    // تمييز الإشعار كمقروء عند النقر
    document.querySelectorAll('.mark-as-read').forEach(item => {
        item.addEventListener('click', function(e) {
            const notificationId = this.closest('.notification-item').dataset.id;
            markAsRead(notificationId);
        });
    });

    // تمييز الكل كمقروء
    document.getElementById('markAllAsRead').addEventListener('click', function(e) {
        e.preventDefault();
        markAllAsRead();
    });

    // الاتصال بــ WebSocket أو استخدام Polling للتحديث الفوري
    setupRealTimeNotifications();
});

function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/mark-as-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            updateUnreadCount(data.unreadCount);
            document.querySelector(`.notification-item[data-id="${notificationId}"]`).remove();
        }
    });
}

function markAllAsRead() {
    fetch('/notifications/mark-all-as-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            updateUnreadCount(0);
            document.getElementById('notificationList').innerHTML =
                '<div class="p-3 text-center text-muted">لا توجد إشعارات جديدة</div>';
        }
    });
}

function updateUnreadCount(count) {
    liveUnreadCount.textContent = count;
    unreadCount.style.display = count > 0 ? 'block' : 'none';
}

// استخدام Echo أو Pusher للتحديث الفوري
function setupRealTimeNotifications() {
    // إذا كنت تستخدم Laravel Echo
    if(typeof Echo !== 'undefined') {
        Echo.private(`App.Models.User.${window.Laravel.userId}`)
            .notification((notification) => {
                fetch('/notifications/latest')
                    .then(response => response.json())
                    .then(data => {
                        const notificationList = document.getElementById('notificationList');
                        if(notificationList.firstChild.classList.contains('text-muted')) {
                            notificationList.innerHTML = data.html;
                        } else {
                            notificationList.insertAdjacentHTML('afterbegin', data.html);
                        }
                        updateUnreadCount(data.unreadCount);

                        // إضافة تأثير للتنبيه الجديد
                        const newNotification = notificationList.firstElementChild;
                        newNotification.style.animation = 'highlight 2s';
                        setTimeout(() => {
                            newNotification.style.animation = '';
                        }, 2000);
                    });
            });
    } else {
        // بديل باستخدام Long Polling
        setInterval(fetchNewNotifications, 5000);
    }
}

function fetchNewNotifications() {
    fetch('/notifications/check-updates')
        .then(response => response.json())
        .then(data => {
            if(data.hasNew) {
                fetch('/notifications/latest')
                    .then(response => response.json())
                    .then(data => {
                        const notificationList = document.getElementById('notificationList');
                        if(notificationList.firstChild.classList.contains('text-muted')) {
                            notificationList.innerHTML = data.html;
                        } else {
                            notificationList.insertAdjacentHTML('afterbegin', data.html);
                        }
                        updateUnreadCount(data.unreadCount);
                    });
            }
        });
}



 