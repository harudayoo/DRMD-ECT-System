Certainly! Here's the full, integrated code for the admin user management page
with the edit user popup: ```vue
<template>
    <div class="h-screen flex flex-col overflow-hidden bg-white">
        <!-- Top bar -->
        <nav
            class="bg-red-700 shadow-2xl flex items-center justify-between px-4 py-2"
        >
            <button
                @click="toggleMenu"
                class="text-white focus:outline-none hover:text-blue-700 transition-colors duration-200"
            >
                <svg
                    class="w-6 h-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2.5"
                        d="M4 6h16M4 12h16M4 18h16"
                    ></path>
                </svg>
            </button>
            <div class="relative">
                <button
                    @click="toggleUserMenu"
                    class="text-white focus:outline-none hover:text-blue-700 transition-colors duration-200"
                >
                    <svg
                        class="w-7 h-7 -mb-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                        ></path>
                    </svg>
                </button>
                <div
                    v-if="isUserMenuOpen"
                    class="absolute right-0 w-48 bg-white rounded-md shadow-lg py-1 z-10"
                >
                    <a
                        href="#"
                        @click="logout"
                        class="block px-4 py-2 text-sm text-gray-900 hover:bg-gray-300"
                        >Log out</a
                    >
                </div>
            </div>
        </nav>
        <!-- Header -->
        <div class="flex-1 flex flex-col overflow-hidden p-4">
            <!-- Header -->
            <header class="mb-2 ml-10">
                <h1 class="text-3xl font-black text-black">Admin</h1>
            </header>

            <!-- User List Page -->
            <div
                class="flex-1 bg-grey-100 shadow-2xl rounded-lg overflow-hidden"
            >
                <div class="p-4">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-bold">Registered Users</h2>
                        <button
                            @click="goBack"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                        >
                            Back to Dashboard
                        </button>
                    </div>
                    <div v-if="!showUserList">
                        <input
                            v-model="adminPassword"
                            type="password"
                            placeholder="Enter your password"
                            class="border p-2 rounded"
                        />
                        <button
                            @click="verifyAdminPassword"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded ml-2"
                        >
                            Show User List
                        </button>
                    </div>

                    <div v-if="showUserList" class="overflow-x-auto">
                        <table class="w-full bg-white">
                            <thead>
                                <tr>
                                    <th
                                        class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        ID Number
                                    </th>
                                    <th
                                        class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Name
                                    </th>
                                    <th
                                        class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Email
                                    </th>
                                    <th
                                        class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="user in registeredUsers"
                                    :key="user.id"
                                >
                                    <td
                                        class="py-2 px-4 border-b border-gray-200"
                                    >
                                        {{ user.id }}
                                    </td>
                                    <td
                                        class="py-2 px-4 border-b border-gray-200"
                                    >
                                        {{ user.name }}
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
                                            class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded transition duration-300 ease-in-out focus:outline-none"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            @click="deleteUser(user.id)"
                                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-2 rounded transition duration-300 ease-in-out focus:outline-none"
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
                <h2 class="text-2xl font-bold mb-4 text-center">Edit User</h2>
                <form @submit.prevent="updateUser" class="space-y-4">
                    <div>
                        <label for="name" class="block mb-1">Name</label>
                        <input
                            id="name"
                            v-model="editUserForm.name"
                            type="text"
                            placeholder="Name"
                            class="w-full p-2 border rounded"
                        />
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
                            class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out focus:outline-none"
                        >
                            Update
                        </button>
                        <button
                            type="button"
                            @click="closeEditUserModal"
                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out focus:outline-none"
                        >
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { router, useForm } from "@inertiajs/vue3";

// Menu logic
const isMenuOpen = ref(false);
const isUserMenuOpen = ref(false);

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};

const toggleUserMenu = () => {
    isUserMenuOpen.value = !isUserMenuOpen.value;
};

const logout = () => {
    console.log("Logout clicked");
    window.location.href = "/";
};

// User List Page
const showUserList = ref(false);
const adminPassword = ref("");
const registeredUsers = ref([]);

const verifyAdminPassword = () => {
    router.post(
        "/admin/verify-password",
        { password: adminPassword.value },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                if (page.props.verified) {
                    showUserList.value = true;
                    registeredUsers.value = page.props.users;
                } else {
                    alert("Incorrect password");
                }
            },
        }
    );
};

const deleteUser = (userId) => {
    if (confirm("Are you sure you want to delete this user?")) {
        router.delete(`/admin/users/${userId}`, {
            preserveScroll: true,
            onSuccess: () => {
                registeredUsers.value = registeredUsers.value.filter(
                    (user) => user.id !== userId
                );
            },
        });
    }
};

const goBack = () => {
    router.visit("/admin/dashboard");
};

// Edit User Modal
const showEditUserModal = ref(false);
const editUserForm = useForm({
    id: "",
    name: "",
    email: "",
});

const openEditUserModal = (user) => {
    editUserForm.id = user.id;
    editUserForm.name = user.name;
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
            alert("User updated successfully");
            closeEditUserModal();
            // Update the user in the registeredUsers array
            const index = registeredUsers.value.findIndex(
                (u) => u.id === editUserForm.id
            );
            if (index !== -1) {
                registeredUsers.value[index] = {
                    ...registeredUsers.value[index],
                    ...editUserForm,
                };
            }
        },
        onError: (errors) => {
            console.error(errors);
            alert("An error occurred while updating the user");
        },
    });
};
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap");

.font-inter {
    font-family: "Inter", sans-serif;
}
</style>
``` This complete code integrates the edit user form as a popup within the
existing admin user management page. It includes all the necessary functionality
for viewing the user list, editing users in a popup modal, and deleting users.
The code uses Vue.js 3 with the Composition API and Tailwind CSS for styling.
Key features of this integrated code: 1. User list display with password
protection 2. Edit user functionality in a popup modal 3. Delete user
functionality 4. Responsive design using Tailwind CSS 5. Navigation menu and
user menu in the top bar 6. Error handling and success messages for user actions
You can now use this code as a single component in your Vue.js application. Make
sure to set up the necessary routes and backend API endpoints to handle the data
operations (fetching users, updating users, deleting users, etc.).
