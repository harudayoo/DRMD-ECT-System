<template>
    <div>
        <!-- Button to open the modal -->
        <button
            @click="openModal"
            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
            Add Beneficiary
        </button>

        <!-- Add Beneficiary Modal -->
        <div
            v-if="isModalOpen"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center"
        >
            <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-2xl">
                <h2 class="text-2xl font-bold mb-4">Add Beneficiary</h2>
                <form @submit.prevent="addBeneficiary">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="lastName" class="block mb-2"
                                >Last Name:</label
                            >
                            <input
                                type="text"
                                id="lastName"
                                v-model="beneficiary.lastName"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            />
                        </div>
                        <div>
                            <label for="firstName" class="block mb-2"
                                >First Name:</label
                            >
                            <input
                                type="text"
                                id="firstName"
                                v-model="beneficiary.firstName"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            />
                        </div>
                        <div>
                            <label for="middleName" class="block mb-2"
                                >Middle Name:</label
                            >
                            <input
                                type="text"
                                id="middleName"
                                v-model="beneficiary.middleName"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            />
                        </div>
                        <div>
                            <label for="extensionName" class="block mb-2"
                                >Name Extension:</label
                            >
                            <input
                                type="text"
                                id="extensionName"
                                v-model="beneficiary.extensionName"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>
                        <div>
                            <label for="dateOfBirth" class="block mb-2"
                                >Date of Birth:</label
                            >
                            <input
                                type="date"
                                id="dateOfBirth"
                                v-model="beneficiary.dateOfBirth"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            />
                        </div>
                        <div>
                            <label for="province" class="block mb-2"
                                >Province:</label
                            >
                            <select
                                id="province"
                                v-model="beneficiary.provinceID"
                                @change="fetchMunicipalities"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            >
                                <option value="" disabled selected>
                                    Select Province
                                </option>
                                <option
                                    v-for="province in provinces"
                                    :key="province.provinceID"
                                    :value="province.provinceID"
                                >
                                    {{ province.provinceName }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="municipality" class="block mb-2"
                                >Municipality:</label
                            >
                            <select
                                id="municipality"
                                v-model="beneficiary.municipalityID"
                                @change="fetchBarangays"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            >
                                <option value="" disabled selected>
                                    Select Municipality
                                </option>
                                <option
                                    v-for="municipality in municipalities"
                                    :key="municipality.municipalityID"
                                    :value="municipality.municipalityID"
                                >
                                    {{ municipality.municipalityName }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="barangay" class="block mb-2"
                                >Barangay:</label
                            >
                            <select
                                id="barangay"
                                v-model="beneficiary.barangayID"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            >
                                <option value="" disabled selected>
                                    Select Barangay
                                </option>
                                <option
                                    v-for="barangay in barangays"
                                    :key="barangay.barangayID"
                                    :value="barangay.barangayID"
                                >
                                    {{ barangay.barangayName }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-4">
                        <button
                            type="button"
                            @click="closeModal"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, reactive, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";
import axios from "axios";

export default {
    name: "AddBeneficiary",
    setup() {
        const page = usePage();
        const provinces = ref([]);
        const municipalities = ref([]);
        const barangays = ref([]);
        const isModalOpen = ref(false);

        const beneficiary = reactive({
            lastName: "",
            firstName: "",
            middleName: "",
            extensionName: "",
            barangayID: "",
            municipalityID: "",
            provinceID: "",
            dateOfBirth: "",
        });

        onMounted(() => {
            provinces.value = page.props.provinces;
            console.log("Provinces:", provinces.value);
        });

        const fetchMunicipalities = async () => {
            if (beneficiary.provinceID) {
                try {
                    const response = await axios.get(
                        route("api.municipalities.index", {
                            provinceID: beneficiary.provinceID,
                        })
                    );
                    municipalities.value = response.data.municipalities;
                    console.log("Municipalities:", municipalities.value);
                } catch (error) {
                    console.error("Error fetching municipalities:", error);
                }
            } else {
                municipalities.value = [];
                beneficiary.municipalityID = "";
                barangays.value = [];
                beneficiary.barangayID = "";
            }
        };

        const fetchBarangays = async () => {
            if (beneficiary.municipalityID) {
                try {
                    const response = await axios.get(
                        route("api.barangays.index", {
                            municipalityID: beneficiary.municipalityID,
                        })
                    );
                    barangays.value = response.data.barangays;
                    console.log("Barangays:", barangays.value);
                } catch (error) {
                    console.error("Error fetching barangays:", error);
                }
            } else {
                barangays.value = [];
                beneficiary.barangayID = "";
            }
        };

        const addBeneficiary = async () => {
            console.log("Beneficiary data:", beneficiary);

            if (
                !beneficiary.lastName ||
                !beneficiary.firstName ||
                !beneficiary.middleName ||
                !beneficiary.barangayID ||
                !beneficiary.municipalityID ||
                !beneficiary.provinceID ||
                !beneficiary.dateOfBirth
            ) {
                console.error("Please fill in all required fields.");
                return;
            }

            try {
                const response = await axios.post(
                    route("beneficiaries.store"),
                    beneficiary
                );
                console.log("Beneficiary added:", response.data);
                closeModal();
                // Optionally, show a success message or redirect
            } catch (error) {
                console.error("Error adding beneficiary:", error);
                if (error.response) {
                    console.error("Response data:", error.response.data);
                    console.error("Response status:", error.response.status);
                    console.error("Response headers:", error.response.headers);
                } else if (error.request) {
                    console.error("Request:", error.request);
                } else {
                    console.error("Error message:", error.message);
                }
            }
        };

        const openModal = () => {
            isModalOpen.value = true;
        };

        const closeModal = () => {
            isModalOpen.value = false;
        };

        return {
            provinces,
            municipalities,
            barangays,
            beneficiary,
            fetchMunicipalities,
            fetchBarangays,
            addBeneficiary,
            isModalOpen,
            openModal,
            closeModal,
        };
    },
};
</script>
