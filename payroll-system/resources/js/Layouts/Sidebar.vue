<template>
    <div>
        <aside
            v-if="isOpen"
            class="bg-white w-64 flex-shrink-0 border-r overflow-y-auto rounded-tr-xl rounded-br-lg shadow-lg flex flex-col h-full"
        >
            <nav class="mt-5 flex-grow">
                <h3
                    class="px-6 text-xs font-semibold text-gray-600 uppercase tracking-wide"
                >
                    Home
                </h3>
                <a
                    href="#"
                    @click.prevent="$inertia.visit(route('user.dashboard'))"
                    class="flex items-center px-6 py-2 text-gray-700 hover:bg-gray-100"
                >
                    <svg
                        class="w-5 h-5 mr-3"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                        ></path>
                    </svg>
                    Dashboard
                </a>
                <div class="mt-4">
                    <h3
                        class="px-6 text-xs font-semibold text-gray-600 uppercase tracking-wide"
                    >
                        Main Menu
                    </h3>

                    <!-- Beneficiaries Submenu -->
                    <div class="mt-2">
                        <button
                            @click="toggleSubMenu('beneficiaries')"
                            class="flex items-center justify-between w-full px-6 py-2 text-gray-700 hover:bg-gray-100 focus:outline-none"
                        >
                            <span class="flex items-center">
                                <svg
                                    class="w-5 h-5 mr-3"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                    ></path>
                                </svg>
                                Deduplication
                            </span>
                            <svg
                                :class="{
                                    'transform rotate-180':
                                        openSubMenu === 'beneficiaries',
                                }"
                                class="w-4 h-4 transition-transform duration-200"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7"
                                ></path>
                            </svg>
                        </button>
                        <div
                            v-show="openSubMenu === 'beneficiaries'"
                            class="mt-2 py-2 bg-gray-50"
                        >
                            <a
                                @click="openModal"
                                href="#"
                                class="block px-6 py-2 text-gray-600 hover:bg-gray-100"
                                >Add New Beneficiary</a
                            >
                            <a
                                href="#"
                                @click.prevent="
                                    $inertia.visit(route('beneficiaries.edit'))
                                "
                                class="block px-6 py-2 text-gray-600 hover:bg-gray-100"
                                >Edit Beneficiaries</a
                            >
                        </div>
                    </div>

                    <!-- Masterlist Submenu -->
                    <div class="mt-2">
                        <button
                            @click="toggleSubMenu('masterlist')"
                            class="flex items-center justify-between w-full px-6 py-2 text-gray-700 hover:bg-gray-100 focus:outline-none"
                        >
                            <span class="flex items-center">
                                <svg
                                    class="w-5 h-5 mr-3"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                                    ></path>
                                </svg>
                                Payroll
                            </span>
                            <svg
                                :class="{
                                    'transform rotate-180':
                                        openSubMenu === 'masterlist',
                                }"
                                class="w-4 h-4 transition-transform duration-200"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7"
                                ></path>
                            </svg>
                        </button>
                        <div
                            v-show="openSubMenu === 'masterlist'"
                            class="mt-2 py-2 bg-gray-50"
                        >
                            <a
                                @click="openAddMasterlistModal"
                                href="#"
                                class="block px-6 py-2 text-gray-600 hover:bg-gray-100"
                                >Create Empty Masterlist</a
                            >
                            <a
                                href="#"
                                @click.prevent="
                                    $inertia.visit(route('masterlists.view'))
                                "
                                class="block px-6 py-2 text-gray-600 hover:bg-gray-100"
                                >View Masterlists</a
                            >
                            <a
                                href="#"
                                @click.prevent="openImportModal"
                                class="block px-6 py-2 text-gray-600 hover:bg-gray-100"
                                >Import Existing Masterlist</a
                            >
                        </div>
                    </div>

                    <!-- Documents Submenu -->
                    <div class="mt-2">
                        <button
                            @click="toggleSubMenu('documents')"
                            class="flex items-center justify-between w-full px-6 py-2 text-gray-700 hover:bg-gray-100 focus:outline-none"
                        >
                            <span class="flex items-center">
                                <svg
                                    class="w-5 h-5 mr-3"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                                    ></path>
                                </svg>
                                Liquidation
                            </span>
                            <svg
                                :class="{
                                    'transform rotate-180':
                                        openSubMenu === 'documents',
                                }"
                                class="w-4 h-4 transition-transform duration-200"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7"
                                ></path>
                            </svg>
                        </button>
                        <div
                            v-show="openSubMenu === 'documents'"
                            class="mt-2 py-2 bg-gray-50"
                        >
                            <a
                                href="#"
                                @click.prevent="
                                    $inertia.visit(route('payroll.index'))
                                "
                                class="block px-6 py-2 text-gray-600 hover:bg-gray-100"
                                >Payroll Release</a
                            >
                            <a
                                href="#"
                                @click.prevent="
                                    $inertia.visit(route('rcd.index'))
                                "
                                class="block px-6 py-2 text-gray-600 hover:bg-gray-100"
                                >Report of Cash Disbursement</a
                            >
                            <a
                                href="#"
                                @click.prevent="
                                    $inertia.visit(route('cdr.index'))
                                "
                                class="block px-6 py-2 text-gray-600 hover:bg-gray-100"
                                >Cash Disbursement Record</a
                            >
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Footer -->
            <div class="mt-auto p-2 border-t">
                <p class="text-sm text-gray-600 text-center">DRMD@ACE 2024</p>
            </div>
        </aside>

        <!-- Add Beneficiary Modal -->
        <div
            v-if="isModalOpen"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50"
            @mousedown.self="closeModal"
        >
            <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-2xl">
                <h2 class="text-2xl font-bold mb-4 text-center">
                    Add Beneficiary
                </h2>
                <form @submit.prevent="addBeneficiary">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="firstName" class="block mb-2"
                                >First Name</label
                            >
                            <input
                                type="text"
                                id="firstName"
                                v-model="beneficiary.firstName"
                                @input="validateName('firstName')"
                                placeholder="Enter your First Name"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            />
                        </div>
                        <div>
                            <label for="middleName" class="block mb-2"
                                >Middle Name</label
                            >
                            <input
                                type="text"
                                id="middleName"
                                v-model="beneficiary.middleName"
                                @input="validateName('middleName')"
                                placeholder="Enter your Middle Name"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            />
                        </div>
                        <div>
                            <label for="lastName" class="block mb-2"
                                >Last Name</label
                            >
                            <input
                                type="text"
                                id="lastName"
                                v-model="beneficiary.lastName"
                                @input="validateName('lastName')"
                                placeholder="Enter your Last Name"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            />
                        </div>
                        <div>
                            <label for="extensionName" class="block mb-2"
                                >Extension Name</label
                            >
                            <select
                                id="extensionName"
                                v-model="beneficiary.extensionName"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="" disabled selected>
                                    Select an Extension
                                </option>
                                <option value="Jr.">Jr.</option>
                                <option value="Sr.">Sr.</option>
                                <option value="I">I</option>
                                <option value="II">II</option>
                                <option value="III">III</option>
                                <option value="IV">IV</option>
                                <option value="V">V</option>
                            </select>
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
                            <label for="sex" class="block mb-2">Sex</label>
                            <select
                                id="sex"
                                v-model="beneficiary.sex"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            >
                                <option value="" disabled selected>
                                    Select Sex
                                </option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div>
                            <label for="province" class="block mb-2"
                                >Province</label
                            >
                            <select
                                id="province"
                                v-model="beneficiary.provinceID"
                                @change="fetchMunicipalities"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            >
                                <option value="" disabled>
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
                                >Municipality</label
                            >
                            <select
                                id="municipality"
                                v-model="beneficiary.municipalityID"
                                @change="fetchMasterlists"
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
                            <label for="masterlist" class="block mb-2"
                                >Masterlist</label
                            >
                            <select
                                id="masterlist"
                                v-model="beneficiary.masterlistID"
                                @change="fetchBarangays"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            >
                                <option value="" disabled selected>
                                    Select Masterlist
                                </option>
                                <option
                                    v-for="masterlist in masterlists"
                                    :key="masterlist.masterlistID"
                                    :value="masterlist.masterlistID"
                                >
                                    {{ masterlist.masterlistName }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="barangay" class="block mb-2"
                                >Barangay</label
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
                            Add Beneficiary
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Similar Beneficiaries Modal -->
        <div
            v-if="showSimilarModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50"
        >
            <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-2xl">
                <h2 class="text-2xl font-bold mb-4 text-center">
                    Similar Beneficiaries Found
                </h2>
                <ul class="mb-4">
                    <li
                        v-for="item in similarBeneficiaries"
                        :key="item.beneficiary.id"
                        class="mb-2 p-2 border rounded"
                    >
                        <div>
                            {{ item.beneficiary.lastName }},
                            {{ item.beneficiary.firstName }}
                            {{ item.beneficiary.middleName }}
                            {{ item.beneficiary.extensionName }}
                        </div>
                        <div class="text-sm text-gray-600">
                            Similarity:
                            {{ (item.similarity * 100).toFixed(2) }}%
                        </div>
                    </li>
                </ul>
                <div class="flex justify-end space-x-4">
                    <button
                        @click="proceedAdd"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        Proceed
                    </button>
                    <button
                        @click="cancelAdd"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>

        <!-- Updated Import Modal -->
        <div
            v-if="showImportModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50"
            @mousedown.self="closeImportModal"
        >
            <div class="bg-white p-5 rounded-lg shadow-xl max-w-md w-full">
                <h2 class="text-xl font-semibold mb-4 flex justify-center">
                    Import Masterlist
                </h2>
                <div class="mb-4">
                    <label for="province" class="block mb-2">Province</label>
                    <select
                        id="province"
                        v-model="selectedProvinceId"
                        @change="fetchImportMunicipalities"
                        class="w-full px-3 py-2 border rounded-md"
                    >
                        <option value="" disabled>Select Province</option>
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
                        >Municipality</label
                    >
                    <select
                        id="municipality"
                        v-model="selectedMunicipalityId"
                        @change="fetchMasterlists"
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
                <div
                    @dragover.prevent
                    @drop.prevent="handleFileDrop"
                    class="border-dashed border-2 border-gray-300 p-8 text-center cursor-pointer hover:bg-gray-50 mt-4"
                >
                    <p>Drag and drop your spreadsheet here or</p>
                    <input
                        type="file"
                        ref="fileInput"
                        @change="handleFileSelect"
                        class="hidden"
                        accept=".xlsx,.xls,.csv"
                    />
                    <button
                        @click="$refs.fileInput.click()"
                        class="mt-2 px-6 py-2 bg-blue-900 text-white rounded-full hover:bg-blue-700"
                    >
                        Select File
                    </button>
                </div>
                <div v-if="selectedFile" class="mt-4 text-center">
                    <p>Selected file: {{ selectedFile.name }}</p>
                    <button
                        @click="uploadFile"
                        :disabled="isUploading"
                        class="mt-2 px-6 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 disabled:bg-gray-400"
                    >
                        {{ isUploading ? "Uploading..." : "Import Masterlist" }}
                    </button>
                </div>
                <div class="flex justify-center">
                    <button
                        @click="closeImportModal"
                        :disabled="isUploading"
                        class="mt-4 px-6 py-2 bg-gray-300 text-gray-700 rounded-full hover:bg-gray-400 disabled:bg-gray-200"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading Modal -->
        <div
            v-if="isUploading"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center"
        >
            <div
                class="bg-white p-5 rounded-lg shadow-xl flex flex-col items-center"
            >
                <p class="text-lg font-semibold mb-4">
                    Uploading and processing file...
                </p>
                <div
                    class="w-12 h-12 border-t-4 border-blue-500 border-solid rounded-full animate-spin"
                ></div>
            </div>
        </div>

        <!-- Error Modal -->
        <div
            v-if="showErrorModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center"
        >
            <div
                class="bg-white text-center p-5 rounded-lg shadow-xl max-w-lg w-full"
            >
                <h2 class="text-xl font-semibold mb-4 text-red-600">
                    Import Error
                </h2>
                <p class="mb-4">{{ errorMessage }}</p>
                <div v-if="errorDetails.length > 0" class="mb-4">
                    <h3 class="font-semibold mb-2">Error Details:</h3>
                    <div class="max-h-60 overflow-y-auto">
                        <ul class="list-disc list-inside">
                            <li
                                v-for="(error, index) in errorDetails"
                                :key="index"
                                class="text-sm text-gray-600"
                            >
                                {{ error }}
                            </li>
                        </ul>
                    </div>
                </div>
                <button
                    @click="closeErrorModal"
                    class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
                >
                    Close
                </button>
            </div>
        </div>

        <!-- Success Modal -->
        <div
            v-if="showSuccessModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50"
        >
            <div class="bg-white text-center p-5 rounded-lg shadow-xl">
                <h2 class="text-xl font-semibold mb-4 text-green-600">
                    Import Successful
                </h2>
                <p class="mb-4">
                    The masterlist has been successfully imported.
                </p>
                <button
                    @click="closeSuccessModalAndReload"
                    class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600"
                >
                    OK
                </button>
            </div>
        </div>

        <!-- Add Masterlist Modal -->
        <div
            v-if="showAddMasterlistModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50"
            @mousedown.self="closeAddMasterlistModal"
        >
            <div class="bg-white p-8 rounded-lg shadow-xl max-w-md w-full">
                <h2 class="text-2xl font-bold mb-4 flex justify-center">
                    Add New Masterlist
                </h2>
                <div class="mb-4">
                    <label class="block mb-2" for="province"> Province </label>
                    <select
                        id="province"
                        v-model="newMasterlist.provinceID"
                        class="shadow appearance-none border rounded-full w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        :class="{ 'border-red-500': errors.provinceID }"
                        :disabled="isLoading"
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
                    <p
                        v-if="errors.provinceID"
                        class="text-red-500 text-xs italic"
                    >
                        {{ errors.provinceID }}
                    </p>
                </div>
                <div class="mb-4">
                    <label class="block mb-2" for="municipality">
                        Municipality
                    </label>
                    <select
                        id="municipality"
                        v-model="newMasterlist.municipalityID"
                        class="shadow appearance-none border rounded-full w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        :class="{ 'border-red-500': errors.municipalityID }"
                        :disabled="isLoading || !newMasterlist.provinceID"
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
                    <p
                        v-if="errors.municipalityID"
                        class="text-red-500 text-xs italic"
                    >
                        {{ errors.municipalityID }}
                    </p>
                </div>
                <div class="mb-4">
                    <label class="block mb-2" for="masterlistName">
                        Masterlist Name
                    </label>
                    <input
                        id="masterlistName"
                        v-model="newMasterlist.masterlistName"
                        type="text"
                        class="shadow appearance-none border rounded-full w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        :class="{ 'border-red-500': errors.masterlistName }"
                        placeholder="Enter Masterlist Name"
                        :disabled="isLoading"
                    />
                    <p
                        v-if="errors.masterlistName"
                        class="text-red-500 text-xs italic"
                    >
                        {{ errors.masterlistName }}
                    </p>
                </div>
                <div class="flex justify-end">
                    <button
                        @click="closeAddMasterlistModal"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-full mr-2"
                        :disabled="isLoading"
                    >
                        Cancel
                    </button>
                    <button
                        @click="createMasterlist"
                        class="bg-blue-900 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-full"
                        :disabled="isLoading"
                    >
                        {{ isLoading ? "Creating..." : "Create" }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div
            v-if="showAddMListConfirmationModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center"
        >
            <div class="bg-white p-8 rounded-md shadow-xl max-w-md w-full">
                <h2 class="text-2xl font-bold mb-4">
                    Confirm Masterlist Creation
                </h2>
                <p>Are you sure you want to create this masterlist?</p>
                <div class="mt-4 flex justify-end">
                    <button
                        @click="showAddMListConfirmationModal = false"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2"
                        :disabled="isLoading"
                    >
                        Cancel
                    </button>
                    <button
                        @click="confirmCreateMasterlist"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                        :disabled="isLoading"
                    >
                        {{ isLoading ? "Confirming..." : "Confirm" }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div
            v-if="showAddMListSuccessModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center"
        >
            <div class="bg-white p-8 rounded-md shadow-xl max-w-md w-full">
                <h2 class="text-2xl font-bold mb-4">Success!</h2>
                <p class="mb-4">
                    New masterlist has been created successfully.
                </p>
                <button
                    @click="showAddMListSuccessModal = false"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mb-4 w-full"
                >
                    OK
                </button>
                <h3 class="text-xl font-bold mb-2">Add Beneficiary</h3>
                <button
                    @click="openAddBeneficiaryModal"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2 w-full"
                >
                    Add New Beneficiary
                </button>
                <button
                    @click="openExistingBeneficiaryModal"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full"
                >
                    Add Existing Beneficiary
                </button>
                <button
                    @click="closeAllModals"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mt-4 w-full"
                >
                    Close
                </button>
            </div>
        </div>

        <!-- Existing Beneficiary Modal -->
        <div
            v-if="showExistingBeneficiaryModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center"
        >
            <div class="bg-white p-8 rounded-md shadow-xl max-w-4xl w-full">
                <h2 class="text-2xl font-bold mb-4">
                    Add Existing Beneficiary
                </h2>
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Search beneficiaries..."
                    class="w-full p-2 mb-4 border rounded"
                    :disabled="isLoading"
                />
                <div class="max-h-96 overflow-y-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Name</th>
                                <th class="px-4 py-2">Barangay</th>
                                <th class="px-4 py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="beneficiary in filteredBeneficiaries"
                                :key="beneficiary.id"
                            >
                                <td class="border px-4 py-2">
                                    {{ beneficiary.lastName }},
                                    {{ beneficiary.firstName }}
                                </td>
                                <td class="border px-4 py-2">
                                    {{ beneficiary.barangay.barangayName }}
                                </td>
                                <td class="border px-4 py-2">
                                    <button
                                        @click="
                                            addExistingBeneficiary(
                                                beneficiary.id
                                            )
                                        "
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded"
                                        :disabled="isLoading"
                                    >
                                        {{ isLoading ? "Adding..." : "Add" }}
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button
                    @click="showExistingBeneficiaryModal = false"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mt-4"
                    :disabled="isLoading"
                >
                    Close
                </button>
            </div>
        </div>

        <!-- Error Modal -->
        <div
            v-if="errorAddMListMessage"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center"
        >
            <div class="bg-white p-8 rounded-md shadow-xl max-w-md w-full">
                <h2 class="text-2xl font-bold mb-4 text-red-600">Error</h2>
                <p class="mb-4">{{ errorAddMListMessage }}</p>
                <button
                    @click="errorAddMListMessage = ''"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded w-full"
                >
                    Close
                </button>
            </div>
        </div>
        <!-- End of Modals -->
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import axios from "axios";

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(["openModal"]);

const provinces = ref([]);
const municipalities = ref([]);
const barangays = ref([]);
const masterlists = ref([]);
const showSimilarModal = ref(false);
const showSuccessModal = ref(false);
const showErrorModal = ref(false);
const errorMessage = ref("");
const similarBeneficiaries = ref([]);
const isModalOpen = ref(false);
const openSubMenu = ref(null);
const showImportModal = ref(false);
const selectedFile = ref(null);
const selectedProvinceId = ref("");
const selectedMunicipalityId = ref("");
const isUploading = ref(false);
const errorDetails = ref([]);
const showExistingBeneficiaryModal = ref(false);
const searchQuery = ref("");
const filteredBeneficiaries = ref([]);
const isLoading = ref(false);
const showAddMasterlistModal = ref(false);
const showAddMListConfirmationModal = ref(false);
const showAddMListSuccessModal = ref(false);
const errorAddMListMessage = ref(false);

const beneficiary = ref({
    lastName: "",
    firstName: "",
    middleName: "",
    extensionName: "",
    dateOfBirth: "",
    sex: "",
    provinceID: "",
    municipalityID: "",
    barangayID: "",
    masterlistID: "",
});

const validateName = (field) => {
    beneficiary.value[field] = beneficiary.value[field].replace(
        /[^A-Za-z ]/g,
        ""
    );
};

const newMasterlist = ref({
    municipalityID: "",
    masterlistName: "",
});

const errors = ref({
    municipalityID: "",
    masterlistName: "",
});

const createMasterlist = () => {
    errors.value = {
        municipalityID: "",
        masterlistName: "",
    };

    if (!newMasterlist.value.municipalityID) {
        errors.value.municipalityID = "Municipality is required";
    }
    if (!newMasterlist.value.masterlistName) {
        errors.value.masterlistName = "Masterlist name is required";
    }

    if (Object.values(errors.value).some((error) => error)) {
        return;
    }
    showAddMListConfirmationModal.value = true;
};

const fetchData = async (route, params = {}) => {
    try {
        const response = await axios.get(route, { params });
        return response.data;
    } catch (error) {
        console.error(`Error fetching data from ${route}:`, error);
        throw error;
    }
};

const fetchProvinces = async () => {
    isLoading.value = true;
    try {
        const data = await fetchData(route("api.provinces.index"));
        provinces.value = data.provinces;
    } catch (error) {
        handleError(error, "Error fetching provinces");
    } finally {
        isLoading.value = false;
    }
};

const fetchMunicipalities = async () => {
    if (beneficiary.value.provinceID) {
        const data = await fetchData(route("api.municipalities.index"), {
            provinceID: beneficiary.value.provinceID,
        });
        municipalities.value = data.municipalities;
    } else {
        resetDependentFields("municipalityID");
    }
};

const fetchAddMListMunicipalities = async () => {
    if (newMasterlist.value.provinceID) {
        isLoading.value = true;
        try {
            const data = await fetchData(route("api.municipalities.index"), {
                provinceID: newMasterlist.value.provinceID,
            });
            municipalities.value = data.municipalities;
        } catch (error) {
            handleError(error, "Error fetching municipalities");
        } finally {
            isLoading.value = false;
        }
    } else {
        municipalities.value = [];
    }
};

const fetchImportMunicipalities = async () => {
    if (selectedProvinceId.value) {
        try {
            const response = await axios.get(
                route("api.municipalities.index"),
                {
                    params: { provinceID: selectedProvinceId.value },
                }
            );
            municipalities.value = response.data.municipalities;
        } catch (error) {
            console.error("Error fetching municipalities:", error);
        }
    } else {
        municipalities.value = [];
    }
};

const fetchBarangays = async () => {
    if (beneficiary.value.municipalityID) {
        const data = await fetchData(route("api.barangays.index"), {
            municipalityID: beneficiary.value.municipalityID,
        });
        barangays.value = data.barangays;
    } else {
        resetDependentFields("barangayID");
    }
};

const fetchMasterlists = async () => {
    if (beneficiary.value.municipalityID) {
        const data = await fetchData(route("api.masterlists.index"), {
            municipalityID: beneficiary.value.municipalityID,
        });
        masterlists.value = data.masterlists;
    } else {
        resetDependentFields("masterlistID");
    }
};

const resetDependentFields = (startField) => {
    const fields = ["municipalityID", "barangayID", "masterlistID"];
    const startIndex = fields.indexOf(startField);
    for (let i = startIndex; i < fields.length; i++) {
        beneficiary.value[fields[i]] = "";
        if (fields[i] === "municipalityID") municipalities.value = [];
        if (fields[i] === "barangayID") barangays.value = [];
        if (fields[i] === "masterlistID") masterlists.value = [];
    }
};

const addBeneficiary = async () => {
    try {
        const response = await axios.post(
            route("beneficiaries.store"),
            beneficiary.value
        );
        if (response.data.similarBeneficiaries) {
            similarBeneficiaries.value = response.data.similarBeneficiaries;
            showSimilarModal.value = true;
        } else {
            showSuccessModal.value = true;
            closeModal();
        }
    } catch (error) {
        handleError(error, "Error adding beneficiary");
    }
};

const proceedAdd = async () => {
    try {
        await axios.post(route("beneficiaries.confirmAdd"), beneficiary.value);
        showSimilarModal.value = false;
        showSuccessModal.value = true;
        closeModal();
    } catch (error) {
        handleError(error, "Error confirming beneficiary addition");
    }
};

const handleError = (error, message) => {
    console.error(message, error);
    errorMessage.value =
        error.response?.data?.message || "An unknown error occurred";
    errorAddMListMessage.value =
        error.response?.data?.message || "An unknown error occurred";
    showErrorModal.value = true;
};

const toggleSubMenu = (menu) => {
    openSubMenu.value = openSubMenu.value === menu ? null : menu;
};

const openModal = () => {
    isModalOpen.value = true;
};

const closeModal = () => {
    if (!isUploading.value) {
        isModalOpen.value = false;
        resetForm();
    }
};
const resetForm = () => {
    Object.assign(beneficiary.value, {
        lastName: "",
        firstName: "",
        middleName: "",
        extensionName: "",
        dateOfBirth: "",
        sex: "",
        provinceID: "",
        municipalityID: "",
        barangayID: "",
        masterlistID: "",
        address: "",
        contactNumber: "",
    });
};

const closeErrorModal = () => {
    showErrorModal.value = false;
    errorMessage.value = "";
    errorDetails.value = [];
    // We're not closing the import modal here anymore
};

const closeSuccessModalAndReload = () => {
    showSuccessModal.value = false;
    showImportModal.value = false; // Close the import modal as well
    window.location.reload();
};

const cancelAdd = () => {
    showSimilarModal.value = false;
};

const openImportModal = () => {
    showImportModal.value = true;
};

const closeImportModal = () => {
    if (!isUploading.value) {
        showImportModal.value = false;
        selectedFile.value = null;
        selectedProvinceId.value = "";
        selectedMunicipalityId.value = "";
        errorMessage.value = "";
        errorDetails.value = [];
    }
};

const handleFileDrop = (event) => {
    const file = event.dataTransfer.files[0];
    if (
        file &&
        (file.type ===
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" ||
            file.type === "application/vnd.ms-excel" ||
            file.type === "text/csv")
    ) {
        selectedFile.value = file;
    } else {
        alert("Please upload a valid Excel or CSV file.");
    }
};

const handleFileSelect = (event) => {
    const file = event.target.files[0];
    if (file) {
        selectedFile.value = file;
    }
};

const uploadFile = async () => {
    if (!selectedFile.value || !selectedMunicipalityId.value) {
        alert("Please select both a file and a municipality before uploading.");
        return;
    }

    const formData = new FormData();
    formData.append("file", selectedFile.value);
    formData.append("municipalityId", selectedMunicipalityId.value);

    isUploading.value = true;

    try {
        const response = await axios.post(
            route("masterlists.import"),
            formData,
            {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            }
        );

        isUploading.value = false;

        if (response.data.message) {
            showSuccessModal.value = true;
        } else {
            throw new Error("An unexpected error occurred.");
        }
    } catch (error) {
        isUploading.value = false;
        console.error("Error uploading file:", error);
        errorMessage.value =
            error.response?.data?.error ||
            "An error occurred while uploading the file.";
        errorDetails.value = error.response?.data?.details || [];
        showErrorModal.value = true;
        // We're not closing the import modal here anymore
    }
};

const confirmCreateMasterlist = async () => {
    isLoading.value = true;
    try {
        const response = await axios.post(
            route("masterlists.store"),
            newMasterlist.value
        );
        showAddMListConfirmationModal.value = false;
        showAddMListSuccessModal.value = true;
        // Reset the form
        newMasterlist.value = {
            municipalityID: "",
            masterlistName: "",
        };
    } catch (error) {
        handleError(error, "Error creating masterlist");
    } finally {
        isLoading.value = false;
    }
};

const openAddMasterlistModal = () => {
    showAddMasterlistModal.value = true;
};

const closeAddMasterlistModal = () => {
    if (!isLoading.value) {
        showAddMasterlistModal.value = false;
        newMasterlist.value = {
            municipalityID: "",
            masterlistName: "",
        };
        errors.value = {
            municipalityID: "",
            masterlistName: "",
        };
    }
};

const openAddBeneficiaryModal = () => {
    // Implement the logic to open the add beneficiary modal
    // This could involve setting a new ref to true or emitting an event to a parent component
    emit("openAddBeneficiaryModal");
};

const openExistingBeneficiaryModal = async () => {
    showExistingBeneficiaryModal.value = true;
    await fetchExistingBeneficiaries();
};

const fetchExistingBeneficiaries = async () => {
    isLoading.value = true;
    try {
        const response = await axios.get(
            route("masterlists.existing-beneficiaries"),
            {
                params: { municipalityID: newMasterlist.value.municipalityID },
            }
        );
        filteredBeneficiaries.value = response.data.beneficiaries;
    } catch (error) {
        handleError(error, "Error fetching existing beneficiaries");
    } finally {
        isLoading.value = false;
    }
};

const addExistingBeneficiary = async (beneficiaryId) => {
    isLoading.value = true;
    try {
        await axios.post(route("masterlists.add-beneficiary"), {
            beneficiaryID: beneficiaryId,
            masterlistID: newMasterlist.value.masterlistID, // Make sure you have this after creating the masterlist
        });
        showExistingBeneficiaryModal.value = false;
        // Optionally, refresh the beneficiary list or update the UI
    } catch (error) {
        handleError(error, "Error adding existing beneficiary");
    } finally {
        isLoading.value = false;
    }
};

const closeAllModals = () => {
    showAddMasterlistModal.value = false;
    showAddMListConfirmationModal.value = false;
    showAddMListSuccessModal.value = false;
    showExistingBeneficiaryModal.value = false;
    showErrorModal.value = false;
    errorAddMListMessage.value = "";
};

onMounted(fetchProvinces);

watch(
    () => beneficiary.value.lastName,
    () => validateName("lastName")
);
watch(
    () => beneficiary.value.firstName,
    () => validateName("firstName")
);
watch(
    () => beneficiary.value.middleName,
    () => validateName("middleName")
);

watch(
    () => beneficiary.value.provinceID,
    (newValue, oldValue) => {
        console.log("provinceID changed from", oldValue, "to", newValue);
        fetchMunicipalities();
    }
);

watch(
    () => beneficiary.value.municipalityID,
    (newValue, oldValue) => {
        console.log("municipalityID changed from", oldValue, "to", newValue);
        fetchMasterlists();
        fetchBarangays();
    }
);
watch(() => selectedProvinceId.value, fetchMunicipalities);
watch(() => newMasterlist.value.provinceID, fetchAddMListMunicipalities);

// Watch for changes in the search query
watch(searchQuery, (newQuery) => {
    if (newQuery) {
        filteredBeneficiaries.value = filteredBeneficiaries.value.filter(
            (beneficiary) =>
                `${beneficiary.lastName} ${beneficiary.firstName}`
                    .toLowerCase()
                    .includes(newQuery.toLowerCase())
        );
    } else {
        fetchExistingBeneficiaries(); // Reset to full list when query is empty
    }
});
</script>

<!--The controller uses the Levenshtein distance algorithm to compare names,
allowing for detection of similar names with slight differences. The similarity
threshold is set to 80%, but you can adjust this value as needed. The Vue
component now displays the similarity percentage for each similar beneficiary
found. The script has been updated to handle the new similar beneficiary
detection logic.-->
