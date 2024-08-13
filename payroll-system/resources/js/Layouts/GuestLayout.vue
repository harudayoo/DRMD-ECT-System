<script setup lang="ts">
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div
        class="relative flex-grow flex items-center justify-center overflow-hidden min-h-screen"
    >
        <img
            id="background"
            class="absolute inset-0 w-full h-screen bg-cover"
            src="icons/login.png"
        />
        <div
            class="absolute inset-0 bg-gradient-to-tr from-yellow-600 to-blue-700 mix-blend-overlay"
        ></div>
        <div
            class="absolute w-full sm:max-w-md px-6 py-4 bg-white bg-opacity-20 backdrop-blur-2xl shadow-md overflow-hidden rounded-3xl"
        >
            <slot />
        </div>
    </div>
</template>
