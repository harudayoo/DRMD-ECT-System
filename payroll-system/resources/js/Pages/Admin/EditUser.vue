<template>
    <div class="container mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-5">Edit User</h1>
        <form @submit.prevent="updateUser" class="space-y-4">
            <div>
                <label for="name" class="block mb-1">Name</label>
                <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    placeholder="Name"
                    class="w-full p-2 border rounded"
                />
            </div>
            <div>
                <label for="email" class="block mb-1">Email</label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    placeholder="Email"
                    class="w-full p-2 border rounded"
                />
            </div>
            <div class="flex space-x-4">
                <button
                    type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                    Update User
                </button>
                <button
                    type="button"
                    @click="goBack"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                >
                    Go Back
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    user: Object,
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
});

const updateUser = () => {
    form.put(route("admin.users.update", props.user.id), {
        preserveScroll: true,
        onSuccess: () => {
            alert("User updated successfully");
        },
        onError: (errors) => {
            console.error(errors);
            alert("An error occurred while updating the user");
        },
    });
};

const goBack = () => {
    window.history.back();
};
</script>
