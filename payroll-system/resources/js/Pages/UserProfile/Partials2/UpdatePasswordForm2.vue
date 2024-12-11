<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

// Import eye icons (assuming you're using Lucide or similar icon library)
import { Eye, EyeOff } from 'lucide-vue-next';

const passwordInput = ref<HTMLInputElement | null>(null);
const currentPasswordInput = ref<HTMLInputElement | null>(null);

// Refs to track password visibility
const currentPasswordVisible = ref(false);
const newPasswordVisible = ref(false);
const confirmPasswordVisible = ref(false);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value?.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value?.focus();
            }
        },
    });
};

// Toggle password visibility function
const togglePasswordVisibility = (type: 'current' | 'new' | 'confirm') => {
    switch (type) {
        case 'current':
            currentPasswordVisible.value = !currentPasswordVisible.value;
            break;
        case 'new':
            newPasswordVisible.value = !newPasswordVisible.value;
            break;
        case 'confirm':
            confirmPasswordVisible.value = !confirmPasswordVisible.value;
            break;
    }
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-xl font-medium text-gray-900">Update Password</h2>
            <p class="mt-1 text-base text-gray-600">
                Ensure your account is using a long, random password to stay secure.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            <div>
                <InputLabel 
                    for="current_password" 
                    value="Current Password" 
                    class="block mb-2 text-base font-bold text-imperial-primer" 
                />
                <div class="relative">
                    <TextInput
                        id="current_password"
                        ref="currentPasswordInput"
                        v-model="form.current_password"
                        :type="currentPasswordVisible ? 'text' : 'password'"
                        class="mt-1 block w-full pr-10"
                        autocomplete="current-password"
                    />
                    <button
                        type="button"
                        @click="togglePasswordVisibility('current')"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 hover:text-gray-900"
                    >
                        <EyeOff v-if="!currentPasswordVisible" class="h-5 w-5" />
                        <Eye v-else class="h-5 w-5" />
                    </button>
                </div>
                <InputError :message="form.errors.current_password" class="mt-2" />
            </div>

            <div>
                <InputLabel 
                    for="password" 
                    value="New Password" 
                    class="block mb-2 text-base font-bold text-imperial-primer" 
                />
                <div class="relative">
                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        :type="newPasswordVisible ? 'text' : 'password'"
                        class="mt-1 block w-full pr-10"
                        autocomplete="new-password"
                    />
                    <button
                        type="button"
                        @click="togglePasswordVisibility('new')"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 hover:text-gray-900"
                    >
                        <EyeOff v-if="!newPasswordVisible" class="h-5 w-5" />
                        <Eye v-else class="h-5 w-5" />
                    </button>
                </div>
                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div>
                <InputLabel 
                    for="password_confirmation" 
                    value="Confirm Password" 
                    class="block mb-2 text-base font-bold text-imperial-primer" 
                />
                <div class="relative">
                    <TextInput
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        :type="confirmPasswordVisible ? 'text' : 'password'"
                        class="mt-1 block w-full pr-10"
                        autocomplete="new-password"
                    />
                    <button
                        type="button"
                        @click="togglePasswordVisibility('confirm')"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 hover:text-gray-900"
                    >
                        <EyeOff v-if="!confirmPasswordVisible" class="h-5 w-5" />
                        <Eye v-else class="h-5 w-5" />
                    </button>
                </div>
                <InputError :message="form.errors.password_confirmation" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>

<style scoped>
/* Optional: Add some styling for the eye icon button */
button {
    @apply focus:outline-none;
}
</style>