<template>
    <div
        class="relative flex-grow flex items-center justify-center overflow-hidden min-h-screen"
    >
        <img
            id="background"
            class="absolute inset-0 w-full h-screen bg-cover"
            :src="'/icons/login.png'"
        />
        <div
            class="absolute inset-0 bg-gradient-to-tr from-yellow-600 to-blue-700 mix-blend-overlay"
        ></div>
        <div
            class="absolute w-full sm:max-w-md px-6 py-4 bg-white bg-opacity-20 backdrop-blur-2xl shadow-md overflow-hidden rounded-3xl"
        >
            <slot />
        </div>
        <footer
            class="w-full text-center text-sm text-white/70 py-2 absolute bottom-0 bg-gradient-to-r from-indigo-900 ..."
        >
            Disaster Response and Management Division - 2024
        </footer>
    </div>
</template>
