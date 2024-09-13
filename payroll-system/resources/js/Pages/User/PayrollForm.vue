<template>
    <div class="h-screen flex flex-col overflow-hidden">
        <!-- Top navigation bar -->
        <NavBar @toggle-sidebar="toggleSidebar" @click="toggleDarkMode" />

        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar -->
            <transition name="slide">
                <Sidebar
                    v-if="isSidebarOpen"
                    :is-open="isSidebarOpen"
                    @open-modal="openModal"
                />
            </transition>

            <!-- Main content -->
            <div class="p-6 flex px-14 flex-col w-full">
                <h1 class="text-2xl font-bold mb-4 text-center">
                    CASH ASSISTANCE PAYROLL
                </h1>

                <!-- Dropdown Menus and Search Bar -->
                <div class="flex justify-between items-center mb-4">
                    <!-- Dropdowns Container -->
                    <div class="flex flex-1 space-x-4">
                        <!-- Province Dropdown -->
                        <div class="flex-1">
                            <label for="province" class="block mb-1"
                                >Province</label
                            >
                            <select
                                id="province"
                                class="w-full border border-gray-300 rounded p-2"
                            >
                                <option value="province1">Province 1</option>
                                <option value="province2">Province 2</option>
                                <option value="province3">Province 3</option>
                            </select>
                        </div>

                        <!-- Barangay Dropdown -->
                        <div class="flex-1">
                            <label for="barangay" class="block mb-1"
                                >Barangay</label
                            >
                            <select
                                id="barangay"
                                class="w-full border border-gray-300 rounded p-2"
                            >
                                <option value="barangay1">Barangay 1</option>
                                <option value="barangay2">Barangay 2</option>
                                <option value="barangay3">Barangay 3</option>
                            </select>
                        </div>

                        <!-- Municipality Dropdown -->
                        <div class="flex-1">
                            <label for="municipality" class="block mb-1"
                                >Municipality</label
                            >
                            <select
                                id="municipality"
                                class="w-full border border-gray-300 rounded p-2"
                            >
                                <option value="municipality1">
                                    Municipality 1
                                </option>
                                <option value="municipality2">
                                    Municipality 2
                                </option>
                                <option value="municipality3">
                                    Municipality 3
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Spacing between Municipality Dropdown and Search Bar -->
                    <div class="w-1/3 ml-4 flex items-end justify-between">
                        <div class="w-full">
                            <label for="search" class="block mb-1"
                                >Search</label
                            >
                            <input
                                type="text"
                                id="search"
                                placeholder="Search by name"
                                class="w-full border border-gray-300 rounded p-2"
                            />
                        </div>

                        <!-- Export Button -->
                        <div class="ml-4">
                            <button
                                class="bg-blue-500 text-white font-bold py-2 px-4 rounded"
                                @click="exportToPDF"
                            >
                                Export
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <table
                    class="min-w-full table-auto bg-white border border-gray-300"
                >
                    <thead>
                        <tr class="bg-red-00 text-left">
                            <th class="px-4 py-2">NO</th>
                            <th class="px-4 py-2">LAST NAME</th>
                            <th class="px-4 py-2">FIRST NAME</th>
                            <th class="px-4 py-2">MIDDLE NAME</th>
                            <th class="px-4 py-2">EXT.</th>
                            <th class="px-4 py-2">BARANGAY</th>
                            <th class="px-4 py-2">AMOUNT</th>
                            <th class="px-4 py-2">DATE PAID</th>
                            <th class="px-4 py-2">SIGNATURE</th>
                            <th class="px-4 py-2">STATUS</th>
                            <th class="px-4 py-2">STUB</th>
                            <!-- New column -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(entry, index) in payrollData"
                            :key="index"
                            class="border-t"
                        >
                            <td class="px-4 py-2">{{ index + 1 }}</td>
                            <td class="px-4 py-2">{{ entry.lastName }}</td>
                            <td class="px-4 py-2">{{ entry.firstName }}</td>
                            <td class="px-4 py-2">{{ entry.middleName }}</td>
                            <td class="px-4 py-2">{{ entry.ext }}</td>
                            <td class="px-4 py-2">{{ entry.barangay }}</td>
                            <td class="px-4 py-2">{{ entry.amount }}</td>
                            <td class="px-4 py-2">{{ entry.datePaid }}</td>
                            <td class="px-4 py-2"></td>
                            <!-- Empty Signature Column -->
                            <td class="px-4 py-2">{{ entry.status }}</td>
                            <td class="px-4 py-2">{{ entry.stub }}</td>
                            <!-- New data field -->
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import NavBar from "@/Layouts/NavBar.vue";
import Sidebar from "@/Layouts/Sidebar.vue";
import jsPDF from "jspdf";
import "jspdf-autotable";

const isSidebarOpen = ref(true);
const isDarkMode = ref(false);

const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value;
    document.body.classList.toggle("dark", isDarkMode.value);
};

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const openModal = () => {
    // Implement your modal opening logic here
};

// Dummy data for the table
const payrollData = ref([
    {
        lastName: "Dela Cruz",
        firstName: "Juan",
        middleName: "Santos",
        ext: "Jr.", // New data
        barangay: "Barangay 1",
        amount: "5000",
        datePaid: "2024-01-01",
        status: "Paid",
        stub: "", // New data
    },
    {
        lastName: "Reyes",
        firstName: "Maria",
        middleName: "Lambago",
        ext: "", // New data
        barangay: "Barangay 2",
        amount: "4500",
        datePaid: "2024-01-02",
        status: "Paid",
        stub: "", // New data
    },
    {
        lastName: "Garcia",
        firstName: "Jose",
        middleName: "Mando",
        ext: "III", // New data
        barangay: "Barangay 3",
        amount: "5200",
        datePaid: "2024-01-03",
        status: "Paid",
        stub: "", // New data
    },
    {
        lastName: "Garcia",
        firstName: "John",
        middleName: "Celajes",
        ext: "", // New data
        barangay: "Barangay 7",
        amount: "5200",
        datePaid: "2024-01-04",
        status: "Paid",
        stub: "", // New data
    },
    {
        lastName: "Delos Reyes",
        firstName: "Martin",
        middleName: "Dingo",
        ext: "", // New data
        barangay: "Barangay 1",
        amount: "4600",
        datePaid: "2024-01-05",
        status: "Paid",
        stub: "", // New data
    },
    {
        lastName: "Ignacio",
        firstName: "John Mark",
        middleName: "Tanudra",
        ext: "Sr.", // New data
        barangay: "Barangay 9",
        amount: "5200",
        datePaid: "2024-01-06",
        status: "Disqualified",
        stub: "", // New data
    },
    {
        lastName: "Ambalong",
        firstName: "Russel",
        middleName: "Omagtang",
        ext: "", // New data
        barangay: "Barangay 8",
        amount: "4600",
        datePaid: "2024-01-06",
        status: "Paid",
        stub: "", // New data
    },
    {
        lastName: "Salares",
        firstName: "Carl",
        middleName: "Palubio",
        ext: "Jr.", // New data
        barangay: "Barangay 5",
        amount: "4600",
        datePaid: "2024-01-06",
        status: "Paid",
        stub: "", // New data
    },
    {
        lastName: "Nasataya",
        firstName: "Leonard",
        middleName: "Renante",
        ext: "", // New data
        barangay: "Barangay 4",
        amount: "5200",
        datePaid: "2024-01-07",
        status: "Paid",
        stub: "", // New data
    },
    {
        lastName: "Tanudra",
        firstName: "Fritz Willard",
        middleName: "Lee",
        ext: "", // New data
        barangay: "Barangay 8",
        amount: "5200",
        datePaid: "2024-01-07",
        status: "Paid",
        stub: "", // New data
    },
    // Add more data as needed
]);

const exportToPDF = () => {
    const doc = new jsPDF();

    // Title
    doc.text("Cash Assistance Payroll", 20, 10);

    // Define columns and rows
    const columns = [
        { header: "NO", dataKey: "no" },
        { header: "LAST NAME", dataKey: "lastName" },
        { header: "FIRST NAME", dataKey: "firstName" },
        { header: "MIDDLE NAME", dataKey: "middleName" },
        { header: "EXT.", dataKey: "ext" }, // New column
        { header: "BARANGAY", dataKey: "barangay" },
        { header: "AMOUNT", dataKey: "amount" },
        { header: "DATE PAID", dataKey: "datePaid" },
        { header: "STATUS", dataKey: "status" },
        { header: "STUB", dataKey: "stub" }, // New column
    ];
    const rows = payrollData.value.map((entry, index) => ({
        no: index + 1,
        lastName: entry.lastName,
        firstName: entry.firstName,
        middleName: entry.middleName,
        ext: entry.ext, // New data
        barangay: entry.barangay,
        amount: entry.amount,
        datePaid: entry.datePaid,
        status: entry.status,
        stub: entry.stub, // New data
    }));

    // Add autoTable
    (doc as any).autoTable({
        columns: columns,
        body: rows,
        startY: 20,
        margin: { top: 25 },
    });

    // Save the PDF
    doc.save("payroll.pdf");
};
</script>

<style scoped>
/* Add your component styles here */
</style>
