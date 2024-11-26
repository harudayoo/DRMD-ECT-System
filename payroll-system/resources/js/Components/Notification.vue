<script>
import { ref, onMounted, onBeforeUnmount } from "vue";
import axios from "axios";

export default {
    name: "NotificationSystem",
    emits: ["notification-count"],

    setup(props, { emit }) {
        const notifications = ref([]);
        const showNotifications = ref(false);
        const unreadCount = ref(0);
        let pollingInterval = null;

        const fetchNotifications = async () => {
            try {
                const response = await axios.get("/api/notifications");
                notifications.value = response.data.map((notification) => ({
                    ...notification,
                    createdAt: new Date(
                        notification.created_at || notification.createdAt
                    ),
                }));
                updateUnreadCount();
            } catch (error) {
                console.error("Error fetching notifications:", error);
            }
        };

        const updateUnreadCount = () => {
            const count = notifications.value.filter((n) => !n.read).length;
            unreadCount.value = count;
            emit("notification-count", count);
        };

        const markAsRead = async (notificationId) => {
            try {
                await axios.patch(`/api/notifications/${notificationId}/read`);
                const notification = notifications.value.find(
                    (n) => n.id === notificationId
                );
                if (notification) {
                    notification.read = true;
                    updateUnreadCount();
                }
            } catch (error) {
                console.error("Error marking notification as read:", error);
            }
        };

        const markAllAsRead = async () => {
            try {
                await axios.patch("/api/notifications/mark-all-read");
                notifications.value.forEach((notification) => {
                    notification.read = true;
                });
                updateUnreadCount();
            } catch (error) {
                console.error(
                    "Error marking all notifications as read:",
                    error
                );
            }
        };

        const deleteNotification = async (notificationId) => {
            try {
                await axios.delete(`/api/notifications/${notificationId}`);
                notifications.value = notifications.value.filter(
                    (n) => n.id !== notificationId
                );
                updateUnreadCount();
            } catch (error) {
                console.error("Error deleting notification:", error);
            }
        };

        const formatTimestamp = (date) => {
            const now = new Date();
            const diff = now - date;
            const minutes = Math.floor(diff / 60000);
            const hours = Math.floor(minutes / 60);
            const days = Math.floor(hours / 24);

            if (days > 0) return `${days}d ago`;
            if (hours > 0) return `${hours}h ago`;
            if (minutes > 0) return `${minutes}m ago`;
            return "Just now";
        };

        const toggleNotifications = () => {
            showNotifications.value = !showNotifications.value;
            if (showNotifications.value) {
                fetchNotifications();
            }
        };

        const handleClickOutside = (event) => {
            const notificationPanel = document.querySelector(
                ".notification-panel"
            );
            const notificationButton = document.querySelector(
                ".notification-button"
            );

            if (
                showNotifications.value &&
                notificationPanel &&
                !notificationPanel.contains(event.target) &&
                !notificationButton.contains(event.target)
            ) {
                showNotifications.value = false;
            }
        };

        onMounted(() => {
            fetchNotifications();
            document.addEventListener("click", handleClickOutside);
            pollingInterval = setInterval(fetchNotifications, 30000); // Poll every 30 seconds
        });

        onBeforeUnmount(() => {
            document.removeEventListener("click", handleClickOutside);
            if (pollingInterval) {
                clearInterval(pollingInterval);
            }
        });

        return {
            notifications,
            showNotifications,
            unreadCount,
            toggleNotifications,
            markAsRead,
            markAllAsRead,
            deleteNotification,
            formatTimestamp,
        };
    },
};
</script>

<template>
    <div class="relative">
        <!-- Notification Button -->
        <button
            @click="toggleNotifications"
            class="notification-button relative p-2 text-gray-600 hover:text-blue-900 hover:bg-gray-100 rounded-full transition-colors duration-200"
        >
            <svg
                class="w-6 h-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                />
            </svg>
            <span
                v-if="unreadCount > 0"
                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center"
            >
                {{ unreadCount }}
            </span>
        </button>

        <!-- Notifications Panel -->
        <div
            v-if="showNotifications"
            class="notification-panel absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl z-50 border border-gray-200"
        >
            <!-- Header -->
            <div
                class="px-4 py-3 border-b border-gray-200 flex justify-between items-center bg-gray-50"
            >
                <h3 class="font-semibold text-gray-700">Notifications</h3>
                <button
                    v-if="unreadCount > 0"
                    @click="markAllAsRead"
                    class="text-sm text-blue-600 hover:text-blue-800"
                >
                    Mark all as read
                </button>
            </div>

            <!-- Notifications List -->
            <div class="max-h-96 overflow-y-auto">
                <template v-if="notifications.length">
                    <div
                        v-for="notification in notifications"
                        :key="notification.id"
                        :class="[
                            'p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200',
                            { 'bg-blue-50': !notification.read },
                        ]"
                    >
                        <div class="flex justify-between items-start gap-2">
                            <div class="flex-1">
                                <p class="text-sm text-gray-800">
                                    {{ notification.message }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{
                                        formatTimestamp(notification.createdAt)
                                    }}
                                </p>
                            </div>
                            <div
                                class="flex items-center space-x-2 flex-shrink-0"
                            >
                                <button
                                    v-if="!notification.read"
                                    @click="markAsRead(notification.id)"
                                    class="text-blue-600 hover:text-blue-800 p-1"
                                    title="Mark as read"
                                >
                                    <svg
                                        class="w-4 h-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 13l4 4L19 7"
                                        />
                                    </svg>
                                </button>
                                <button
                                    @click="deleteNotification(notification.id)"
                                    class="text-gray-400 hover:text-red-600 p-1"
                                    title="Delete notification"
                                >
                                    <svg
                                        class="w-4 h-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
                <div v-else class="p-4 text-center text-gray-500">
                    No notifications
                </div>
            </div>
        </div>
    </div>
</template>
