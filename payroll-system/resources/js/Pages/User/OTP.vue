<template>
    <div class="relative w-screen h-screen overflow-hidden">
        <!-- Gradient background -->
            <div class="absolute inset-0 bg-gradient-to-tr from-yellow-600 to-blue-700 mix-blend-overlay"></div>
        <!-- Background image -->
            <img src="/icons/login.png" alt="login-image" class="w-full h-full object-cover" />
        <!-- Login box -->
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-opacity-30 bg-white backdrop-blur-2xl p-6 rounded-3xl shadow-lg w-3/12 h-2/4">
            <img src="/icons/logo.png" alt="form-bg" class="mx-auto w-full -mt-2">
                <p class="text-center mt-2 text-base">
                    Please enter the 6-digit authentication code we sent to your
                    email address.
                </p>
                <div class="mt-2 flex flex-row gap-2 justify-center">
                    <input
                        type="text"
                        class="border-2 border-black w-12 h-14 text-2xl rounded-xl text-center"
                        maxlength="1"/>
                    </input>
                    <input
                        type="text"
                        class="border-2 border-black w-12 h-14 text-2xl rounded-xl text-center"
                        maxlength="1"/>
                    </input>
                    <input
                        type="text"
                        class="border-2 border-black w-12 h-14 text-2xl rounded-xl text-center"
                        maxlength="1"/>
                    </input>
                    <input
                        type="text"
                        class="border-2 border-black w-12 h-14 text-2xl rounded-xl text-center"
                        maxlength="1"/>
                    </input>
                    <input
                        type="text"
                        class="border-2 border-black w-12 h-14 text-2xl rounded-xl text-center"
                        maxlength="1"/>
                    </input>
                    <input
                        type="text"
                        class="border-2 border-black w-12 h-14 text-2xl rounded-xl text-center"
                        maxlength="1"/>
                    </input>
                </div>
                    <a href="#" class="text-black text-base font-bold mt-2 block text-center">Didn't get the code?</a>
                    <a href="#" class="text-white text-base font-bold mt-.5 block text-center underline hover:text-blue-700">Resend</a>
                <div class="flex gap-4 mt-3">
                    <button action="{{ route('redirect.route') }}" method="GET"
                        type="submit"
                        class="w-1/2 bg-blue-400 text-black font-black content-center py-1 rounded-full hover:bg-blue-900 hover:text-white transition duration-300 ease-in-out focus:outline-none text-lg mx-auto block">
                        Submit
                    </button>
                    <button action="{{ route('redirect.route') }}" method="GET"
                        type="submit"
                        class="w-1/2 bg-red-400 text-black font-black content-center py-1 rounded-full hover:bg-red-900 hover:text-white transition duration-300 ease-in-out focus:outline-none text-lg mx-auto block">
                        Cancel
                    </button>
                </div>
        </div>
    </div>
</template>

