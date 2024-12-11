<script setup lang="ts">
import DeleteUserForm from './Partials2/DeleteUserForm2.vue';
import UpdatePasswordForm from './Partials2/UpdatePasswordForm2.vue';
import UpdateProfileInformationForm from './Partials2/UpdateProfileInformationForm2.vue';
import { Head } from '@inertiajs/vue3';

defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
}>();
</script>

<template>
    <Head title="User Profile" />

    <div class="bg-stone-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex items-center justify-between py-5">
            <h1 class="font-medium text-3xl text-gray-800 leading-tight">Account Details</h1>
            <a 
                href="/main"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
                Back to Main
            </a>
        </div>    
            
        <AuthenticatedLayout>
            <div class="py-8 -mt-8">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="p-4 sm:p-8 bg-white shadow-xl sm:rounded-lg">
                            <UpdateProfileInformationForm
                                :must-verify-email="mustVerifyEmail"
                                :status="status"
                                class="max-w-xl"
                            />
                        </div>
                        <div class="p-4 sm:p-8 bg-white shadow-xl sm:rounded-lg">
                            <UpdatePasswordForm class="max-w-xl" />
                        </div>
                    </div>

                    <div class="p-4 sm:p-8 bg-white shadow-xl sm:rounded-lg">
                        <DeleteUserForm class="max-w" />
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    </div>
</template>
