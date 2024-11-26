<template>
    <NSAdmin>
        <div class="h-full flex flex-col px-14 overflow-hidden bg-stone-100">
            <div class="flex-1 flex flex-col overflow-hidden p-4">
                <div class="flex justify-between items-center mt-2 mb-4">
                    <h1 class="text-2xl font-black text-black">
                        Registered Users
                    </h1>
                    <button
                        @click="goBack"
                        class="bg-blue-900 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl"
                    >
                        Back to Dashboard
                    </button>
                </div>

                <!-- Table -->
                <div
                    class="h-[85%] bg-white shadow-xl border-gray-400 rounded-2xl overflow-hidden"
                >
                    <div class="p-4">
                        <div class="overflow-x-auto">
                            <table class="w-full bg-white">
                                <thead>
                                    <tr>
                                        <th
                                            class="pb-5 pt-1 pr-14 border-b border-gray-200 text-lg leading-4 font-medium text-gray-500 tracking-wider"
                                        >
                                            ID Number
                                        </th>
                                        <th
                                            class="pb-5 pt-1 px-4 border-b border-gray-200 text-left text-lg leading-4 font-medium text-gray-500 tracking-wider"
                                        >
                                            Name
                                        </th>
                                        <th
                                            class="pb-5 pt-1 px-4 border-b border-gray-200 text-left text-lg leading-4 font-medium text-gray-500 tracking-wider"
                                        >
                                            Email
                                        </th>
                                        <th
                                            class="pb-5 pt-1 px-4 border-b border-gray-200 text-left text-lg leading-4 font-medium text-gray-500 tracking-wider"
                                        >
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="user in users" :key="user.id">
                                        <td
                                            class="py-2 px-7 border-b border-gray-200"
                                        >
                                            {{ user.id }}
                                        </td>
                                        <td
                                            class="py-2 px-4 border-b border-gray-200"
                                        >
                                            {{
                                                `${user.firstName} ${
                                                    user.middleName
                                                } ${user.lastName}${
                                                    user.nameExt
                                                        ? " " + user.nameExt
                                                        : ""
                                                }`
                                            }}
                                        </td>
                                        <td
                                            class="py-2 px-4 border-b border-gray-200"
                                        >
                                            {{ user.email }}
                                        </td>
                                        <td
                                            class="py-2 px-4 border-b border-gray-300 flex flex-item gap-1.5"
                                        >
                                            <button
                                                @click="openEditUserModal(user)"
                                                class="bg-blue-900 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded transition duration-300 ease-in-out focus:outline-none"
                                            >
                                                Edit
                                            </button>
                                            <button
                                                @click="
                                                    openDeleteConfirmation(user)
                                                "
                                                class="bg-red-800 hover:bg-red-600 text-white font-bold py-1 px-2 rounded transition duration-300 ease-in-out focus:outline-none"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit User Modal -->
            <div
                v-if="showEditUserModal"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
            >
                <div class="bg-white p-6 rounded-lg w-full max-w-md">
                    <h2 class="text-2xl font-bold mb-4 text-center">
                        Edit User
                    </h2>
                    <form @submit.prevent="updateUser" class="space-y-4">
                        <div>
                            <label for="firstName" class="block mb-1"
                                >First Name</label
                            >
                            <input
                                id="firstName"
                                v-model="editUserForm.firstName"
                                type="text"
                                placeholder="First Name"
                                class="w-full p-2 border rounded"
                            />
                        </div>
                        <div>
                            <label for="middleName" class="block mb-1"
                                >Middle Name</label
                            >
                            <input
                                id="middleName"
                                v-model="editUserForm.middleName"
                                type="text"
                                placeholder="Middle Name"
                                class="w-full p-2 border rounded"
                            />
                        </div>
                        <div>
                            <label for="lastName" class="block mb-1"
                                >Last Name</label
                            >
                            <input
                                id="lastName"
                                v-model="editUserForm.lastName"
                                type="text"
                                placeholder="Last Name"
                                class="w-full p-2 border rounded"
                            />
                        </div>
                        <div>
                            <label for="nameExt" class="block mb-1"
                                >Name Extension</label
                            >
                            <select
                                id="nameExt"
                                v-model="editUserForm.nameExt"
                                class="w-full p-2 border rounded"
                            >
                                <option value="" disabled>
                                    Select Name Extension
                                </option>
                                <option value="Jr.">Jr.</option>
                                <option value="Sr.">Sr.</option>
                                <option value="II">II</option>
                                <option value="III">III</option>
                            </select>
                        </div>
                        <div>
                            <label for="email" class="block mb-1">Email</label>
                            <input
                                id="email"
                                v-model="editUserForm.email"
                                type="email"
                                placeholder="Email"
                                class="w-full p-2 border rounded"
                            />
                        </div>
                        <div class="flex justify-center space-x-4">
                            <button
                                type="submit"
                                class="bg-blue-900 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out focus:outline-none"
                            >
                                Update
                            </button>
                            <button
                                type="button"
                                @click="closeEditUserModal"
                                class="bg-red-800 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out focus:outline-none"
                            >
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Delete Confirmation Modal -->
            <div
                v-if="showDeleteConfirmation"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            >
                <div class="bg-white p-6 rounded-lg w-full max-w-md">
                    <h2 class="text-2xl font-bold mb-4 text-center">
                        Confirm Deletion
                    </h2>
                    <div v-if="userToDelete">
                        <p class="mb-6 text-center">
                            Are you sure you want to delete the user "{{
                                userToDelete.firstName
                            }}"? This action cannot be undone.
                        </p>
                        <div class="flex justify-center space-x-4">
                            <button
                                @click="confirmDelete"
                                class="bg-red-800 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out focus:outline-none"
                            >
                                Confirm Delete
                            </button>
                            <button
                                @click="closeDeleteConfirmation"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded transition duration-300 ease-in-out focus:outline-none"
                            >
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </NSAdmin>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import NSAdmin from "@/Layouts/NSAdmin.vue";

interface User {
    id: number;
    firstName: string;
    lastName: string;
    middleName: string;
    nameExt: string | null;
    email: string;
}

const props = defineProps<{
    users: User[];
}>();

// Menu logic
const isMenuOpen = ref(false);
const isUserMenuOpen = ref(false);

const toggleUserMenu = () => {
    isUserMenuOpen.value = !isUserMenuOpen.value;
};

const logout = () => {
    router.post(route("logout"));
};

const editUserForm = useForm<User>({
    id: 0,
    firstName: "",
    lastName: "",
    middleName: "",
    nameExt: "",
    email: "",
});

const showEditUserModal = ref(false);
const showDeleteConfirmation = ref(false);
const userToDelete = ref<User | null>(null);

const openEditUserModal = (user: User) => {
    editUserForm.id = user.id;
    editUserForm.firstName = user.firstName;
    editUserForm.lastName = user.lastName;
    editUserForm.middleName = user.middleName;
    editUserForm.nameExt = user.nameExt || "";
    editUserForm.email = user.email;
    showEditUserModal.value = true;
};

const closeEditUserModal = () => {
    showEditUserModal.value = false;
    editUserForm.reset();
};

const updateUser = () => {
    editUserForm.put(route("admin.users.update", editUserForm.id), {
        preserveScroll: true,
        onSuccess: () => {
            closeEditUserModal();
            // Refresh the page to get updated data
            router.reload();
        },
    });
};

const openDeleteConfirmation = (user: User) => {
    userToDelete.value = user;
    showDeleteConfirmation.value = true;
};

const closeDeleteConfirmation = () => {
    showDeleteConfirmation.value = false;
    userToDelete.value = null;
};

const confirmDelete = () => {
    if (userToDelete.value) {
        router.delete(route("admin.users.delete", userToDelete.value.id), {
            preserveScroll: true,
            onSuccess: () => {
                closeDeleteConfirmation();
                // Refresh the page to get updated data
                router.reload();
            },
        });
    }
};

const goBack = () => {
    router.visit("/admin/dashboard");
};
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap");

.font-inter {
    font-family: "Inter", sans-serif;
}
</style>
