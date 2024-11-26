<template>
    <div
        v-if="show"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        @click="$emit('close')"
    >
        <div
            class="bg-white rounded-lg shadow-xl p-6 w-[800px] max-h-[90vh] overflow-y-auto"
            @click.stop
        >
            <div class="grid grid-cols-12 gap-4">
                <!-- Calendar Section (8 columns) -->
                <div class="col-span-8">
                    <!-- Calendar Header -->
                    <div class="flex items-center justify-between mb-6">
                        <button
                            @click="previousMonth"
                            class="p-2 hover:bg-gray-100 rounded-full transition-colors duration-200"
                            aria-label="Previous Month"
                        >
                            <svg
                                class="w-5 h-5 text-gray-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 19l-7-7 7-7"
                                />
                            </svg>
                        </button>

                        <h2 class="text-xl font-semibold text-gray-800">
                            {{ months[currentDate.getMonth()] }}
                            {{ currentDate.getFullYear() }}
                        </h2>

                        <button
                            @click="nextMonth"
                            class="p-2 hover:bg-gray-100 rounded-full transition-colors duration-200"
                            aria-label="Next Month"
                        >
                            <svg
                                class="w-5 h-5 text-gray-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>
                        </button>
                    </div>

                    <!-- Calendar Grid -->
                    <div class="mb-6">
                        <!-- Weekday Headers -->
                        <div class="grid grid-cols-7 mb-2">
                            <div
                                v-for="day in weekDays"
                                :key="day"
                                class="text-center text-sm font-medium text-gray-600 py-2"
                            >
                                {{ day }}
                            </div>
                        </div>

                        <!-- Calendar Days -->
                        <div class="grid grid-cols-7 gap-1">
                            <template
                                v-for="(day, index) in currentMonthDays"
                                :key="index"
                            >
                                <!-- Empty cells -->
                                <div
                                    v-if="day.empty"
                                    class="aspect-square p-2 text-center"
                                ></div>

                                <!-- Day cells -->
                                <button
                                    v-else
                                    @click="selectDate(day.date)"
                                    class="aspect-square p-2 relative group hover:bg-gray-100 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                    :class="{
                                        'bg-blue-50': day.isSelected,
                                        'font-bold': day.isToday,
                                        'border-2 border-blue-500':
                                            day.isSelected,
                                    }"
                                >
                                    <span
                                        :class="{
                                            'text-blue-900': day.isSelected,
                                            'text-gray-900': !day.isSelected,
                                        }"
                                    >
                                        {{ day.day }}
                                    </span>

                                    <!-- Event indicators -->
                                    <div
                                        v-if="day.events.length"
                                        class="absolute bottom-1 left-1/2 transform -translate-x-1/2 flex gap-1.5"
                                    >
                                        <div
                                            v-for="(
                                                event, idx
                                            ) in day.events.slice(0, 3)"
                                            :key="idx"
                                            class="w-2.5 h-2.5 rounded-full"
                                            :class="getEventColor(event.type)"
                                        ></div>
                                    </div>

                                    <!-- Events tooltip -->
                                    <div
                                        v-if="day.events.length"
                                        class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-56 bg-white shadow-lg rounded-lg p-2 text-sm hidden group-hover:block z-10"
                                    >
                                        <div
                                            v-for="event in day.events"
                                            :key="event.id"
                                            class="py-1 px-2 text-left text-gray-700 hover:bg-gray-50 rounded flex justify-between items-center"
                                        >
                                            <span>{{ event.title }}</span>
                                            <div class="flex gap-2">
                                                <button
                                                    @click.stop="
                                                        editEvent(event)
                                                    "
                                                    class="text-blue-500 hover:text-blue-700"
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
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                                        />
                                                    </svg>
                                                </button>
                                                <button
                                                    @click.stop="
                                                        confirmDeleteEvent(
                                                            event
                                                        )
                                                    "
                                                    class="text-red-500 hover:text-red-700"
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
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Event Form Section (4 columns) -->
                <div class="col-span-4 border-l pl-4">
                    <div v-if="selectedDate" class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ isEditing ? "Edit Event" : "Add Event" }} for
                            {{ new Date(selectedDate).toLocaleDateString() }}
                        </h3>
                        <form
                            @submit.prevent="handleSubmitEvent"
                            class="space-y-4"
                        >
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Event Title</label
                                >
                                <input
                                    v-model="newEvent.title"
                                    type="text"
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Event Type</label
                                >
                                <select
                                    v-model="newEvent.type"
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                                    <option value="general-meeting">
                                        General Meeting
                                    </option>
                                    <option value="general-event">
                                        General Event
                                    </option>
                                    <option value="deadline">Deadline</option>
                                    <option value="drmd-meeting">
                                        DRMD Meeting
                                    </option>
                                    <option value="drmd-event">
                                        DRMD Event
                                    </option>
                                </select>
                            </div>
                            <button
                                type="submit"
                                class="w-full bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            >
                                {{ isEditing ? "Update Event" : "Add Event" }}
                            </button>
                            <button
                                v-if="isEditing"
                                type="button"
                                @click="cancelEdit"
                                class="w-full bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                            >
                                Cancel Edit
                            </button>
                        </form>
                    </div>
                    <div v-else class="text-center text-gray-500">
                        Select a date to add an event
                    </div>

                    <!-- Legend -->
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">
                            Event Types
                        </h4>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-2.5 h-2.5 rounded-full bg-blue-500"
                                ></div>
                                <span class="text-sm text-gray-600"
                                    >General Meeting</span
                                >
                            </div>
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-2.5 h-2.5 rounded-full bg-green-500"
                                ></div>
                                <span class="text-sm text-gray-600"
                                    >General Event</span
                                >
                            </div>
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-2.5 h-2.5 rounded-full bg-red-500"
                                ></div>
                                <span class="text-sm text-gray-600"
                                    >Deadline</span
                                >
                            </div>
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-2.5 h-2.5 rounded-full bg-orange-500"
                                ></div>
                                <span class="text-sm text-gray-600"
                                    >DRMD Meeting</span
                                >
                            </div>
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-2.5 h-2.5 rounded-full bg-yellow-500"
                                ></div>
                                <span class="text-sm text-gray-600"
                                    >DRMD Event</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div
                class="flex justify-center space-x-4 pt-4 mt-4 border-t border-gray-200"
            >
                <button
                    @click="goToToday"
                    class="px-4 py-2 text-sm text-blue-900 hover:bg-blue-50 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Today
                </button>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div
            v-if="showDeleteModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    Confirm Delete
                </h3>
                <p class="text-gray-600 mb-6">
                    Are you sure you want to delete this event?
                </p>
                <div class="flex justify-end space-x-4">
                    <button
                        @click="showDeleteModal = false"
                        class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md"
                    >
                        Cancel
                    </button>
                    <button
                        @click="handleDeleteEvent"
                        class="px-4 py-2 text-sm text-white bg-red-500 hover:bg-red-600 rounded-md"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted, watch } from "vue";

export default {
    name: "Calendar",
    props: {
        show: Boolean,
    },
    emits: ["close", "dateSelect"],

    setup(props, { emit }) {
        // State
        const currentDate = ref(new Date());
        const selectedDate = ref(null);
        const calendarEvents = ref([]);

        // Edit and Delete State
        const isEditing = ref(false);
        const editingEventId = ref(null);
        const showDeleteModal = ref(false);
        const eventToDelete = ref(null);

        // New Event Form State
        const newEvent = ref({
            id: null,
            title: "",
            type: "general-meeting",
        });

        // Constants
        const months = [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December",
        ];

        const weekDays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

        // Load events from localStorage on component mount
        onMounted(() => {
            const savedEvents = localStorage.getItem("calendarEvents");
            if (savedEvents) {
                calendarEvents.value = JSON.parse(savedEvents);
            }
        });

        // Watch for changes in calendar events and save to localStorage
        watch(
            calendarEvents,
            (newEvents) => {
                localStorage.setItem(
                    "calendarEvents",
                    JSON.stringify(newEvents)
                );
            },
            { deep: true }
        );

        // Computed Properties
        const daysInMonth = computed(() => {
            return new Date(
                currentDate.value.getFullYear(),
                currentDate.value.getMonth() + 1,
                0
            ).getDate();
        });

        const firstDayOfMonth = computed(() => {
            return new Date(
                currentDate.value.getFullYear(),
                currentDate.value.getMonth(),
                1
            ).getDay();
        });

        const currentMonthDays = computed(() => {
            const days = [];
            const daysCount = daysInMonth.value;
            const firstDay = firstDayOfMonth.value;

            // Add empty cells for days before the first day of the month
            for (let i = 0; i < firstDay; i++) {
                days.push({ day: "", empty: true });
            }

            // Add the days of the month
            for (let i = 1; i <= daysCount; i++) {
                const date = new Date(
                    currentDate.value.getFullYear(),
                    currentDate.value.getMonth(),
                    i
                )
                    .toISOString()
                    .split("T")[0];

                const dayEvents = calendarEvents.value.filter(
                    (event) => event.date === date
                );

                const isToday =
                    new Date(date).toDateString() === new Date().toDateString();
                const isSelected = date === selectedDate.value;

                days.push({
                    day: i,
                    date,
                    events: dayEvents,
                    empty: false,
                    isToday,
                    isSelected,
                });
            }

            return days;
        });

        // Event color mapping
        const getEventColor = (type) => {
            const colorMap = {
                "general-meeting": "bg-blue-500",
                "general-event": "bg-green-500",
                deadline: "bg-red-500",
                "drmd-meeting": "bg-orange-500",
                "drmd-event": "bg-yellow-500",
            };
            return colorMap[type] || "bg-gray-500";
        };

        // Methods
        const previousMonth = () => {
            currentDate.value = new Date(
                currentDate.value.getFullYear(),
                currentDate.value.getMonth() - 1,
                1
            );
        };

        const nextMonth = () => {
            currentDate.value = new Date(
                currentDate.value.getFullYear(),
                currentDate.value.getMonth() + 1,
                1
            );
        };

        const selectDate = (date) => {
            selectedDate.value = date;
            emit("dateSelect", date);
            if (!isEditing.value) {
                newEvent.value = {
                    id: null,
                    title: "",
                    type: "general-meeting",
                };
            }
        };

        const goToToday = () => {
            currentDate.value = new Date();
            selectDate(new Date().toISOString().split("T")[0]);
        };

        const editEvent = (event) => {
            isEditing.value = true;
            editingEventId.value = event.id;
            newEvent.value = { ...event };
            selectedDate.value = event.date;
        };

        const cancelEdit = () => {
            isEditing.value = false;
            editingEventId.value = null;
            newEvent.value = {
                id: null,
                title: "",
                type: "general-meeting",
            };
        };

        const confirmDeleteEvent = (event) => {
            eventToDelete.value = event;
            showDeleteModal.value = true;
        };

        const handleDeleteEvent = () => {
            calendarEvents.value = calendarEvents.value.filter(
                (event) => event.id !== eventToDelete.value.id
            );
            showDeleteModal.value = false;
            eventToDelete.value = null;

            // Reset form if we're editing the event that's being deleted
            if (editingEventId.value === eventToDelete.value.id) {
                cancelEdit();
            }
        };

        const handleSubmitEvent = () => {
            if (isEditing.value) {
                // Update existing event
                const eventIndex = calendarEvents.value.findIndex(
                    (e) => e.id === editingEventId.value
                );
                if (eventIndex !== -1) {
                    calendarEvents.value[eventIndex] = {
                        ...newEvent.value,
                        date: selectedDate.value,
                    };
                }
                isEditing.value = false;
                editingEventId.value = null;
            } else {
                // Add new event
                const newId =
                    Math.max(0, ...calendarEvents.value.map((e) => e.id), 0) +
                    1;
                calendarEvents.value.push({
                    ...newEvent.value,
                    id: newId,
                    date: selectedDate.value,
                });
            }

            // Reset form
            newEvent.value = {
                id: null,
                title: "",
                type: "general-meeting",
            };
        };

        // Return all necessary variables and methods
        return {
            // State
            currentDate,
            selectedDate,
            calendarEvents,
            newEvent,
            isEditing,
            editingEventId,
            showDeleteModal,
            eventToDelete,

            // Constants
            months,
            weekDays,

            // Computed
            currentMonthDays,

            // Methods
            previousMonth,
            nextMonth,
            selectDate,
            goToToday,
            editEvent,
            cancelEdit,
            confirmDeleteEvent,
            handleDeleteEvent,
            handleSubmitEvent,
            getEventColor,
        };
    },
};
</script>
