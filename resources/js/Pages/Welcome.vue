<script setup>
import {Head, useForm} from '@inertiajs/vue3';
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {inject, ref} from "vue";
import Select from "@/Components/Select.vue";
import Table from "@/Components/Domain/Table.vue";

const swal = inject('$swal');
const form = useForm({
    domain: "amazon.com",
    type: ""
});

const initialDomainInfo = {
    heads: [
        'Domain Name',
        'Registrar',
        'Registration Date',
        'Expiration Date',
        'Estimated Domain Age',
        'Hostnames',
    ],
    keys: [
        'domainName',
        'registrarName',
        'createdDate',
        'expiresDate',
        'estimatedDomainAge',
        'nameServers'
    ],
    data: {}
};
const initialContactInfo = {
    heads: [
        'Registrant Name',
        'Technical Contact Name',
        'Administrative Contact Name',
        'Contact Email'
    ],
    keys: [
        'registrant',
        'technicalContact',
        'administrativeContact',
        'contactEmail'
    ],
    data: {}
};

const domainData = ref({});
const domainInfoData = ref({...initialDomainInfo.data});
const contactInfoData = ref({...initialContactInfo.data});

const typeOptions = [
    {
        label: "Domain Information",
        value: "domain_info"
    },
    {
        label: "Contact Information",
        value: "contact_info"
    }
];

/**
 * Search for the domain data.
 */
async function searchDomain() {
    // Reset some state values
    form.clearErrors();
    domainInfoData.value = {...initialDomainInfo.data};
    contactInfoData.value = {...initialContactInfo.data};

    const maxRetries = 3;
    let attempt = 0;
    let success = false;
    let res;

    // Create a non-closeable loading modal
    const loading = swal.fire({
        title: 'Processing request...',
        allowOutsideClick: false,
        showCloseButton: false,
        allowEscapeKey: false,
        didOpen: () => {
            swal.showLoading();
        }
    });

    while (attempt < maxRetries && ! success) {
        try {
            res = await axios.post(route('fetch.domain'), form);
            success = true;

            if (res.data.hasOwnProperty('dataError')) {
                await swal.fire({
                    icon: 'info',
                    title: 'Missing domain data',
                    text: 'Please check the domain for typo'
                });
            } else {
                domainData.value = res.data;
                prepareDomainData(form.type);
            }
        } catch (e) {
            attempt++;

            if (e.hasOwnProperty('response') && e.response.status === 500) {
                if (attempt >= maxRetries) {
                    await swal.fire({
                        icon: 'error',
                        title: 'Request Failed',
                        text: 'Failed to fetch domain data after multiple attempts. Please try again later.'
                    });
                }
            } else if (e.hasOwnProperty('response') && e.response.status === 422) {
                const errors = e.response.data.errors;

                Object.keys(errors).forEach(err => {
                    form.setError(err, errors[err][0]);
                });
                break;
            } else {
                // For other errors or if retry limit is reached
                await swal.fire({
                    icon: 'error',
                    title: 'An Error Occurred',
                    text: 'An unexpected error occurred. Please refresh the page and try again!'
                });
                break;
            }
        }
    }

    loading.close();
}

/**
 * Prepare the processed domain data
 *
 * @param {string} type
 */
function prepareDomainData(type) {
    (type === 'domain_info') ? processDomainInfo() : processContactInfo();
}

/**
 * Process the domain information data.
 */
function processDomainInfo() {
    const data = {};
    const keys = initialDomainInfo.keys;

    keys.forEach(key => {
        const tempValue = domainData.value[key];
        let value = '';

        switch (key) {
            case 'createdDate':
            case 'expiresDate':
                value = formatDate(tempValue);
                break;
            case 'nameServers':
                value = formatNameServers(tempValue.hostNames)
                break;
            default:
                value = tempValue;
                break;
        }

        data[key] = value;
    });

    domainInfoData.value = data;
    console.log('Processed Domain Info:', domainInfoData.value);
}

/**
 * Process the contact information data.
 */
function processContactInfo() {
    const data = {};
    const keys = initialContactInfo.keys;

    keys.forEach(key => {
        const tempValue = domainData.value[key];
        let value = '';

        switch (key) {
            case 'registrant':
            case 'technicalContact':
            case 'administrativeContact':
                value = tempValue.rawText;
                break;
            default:
                value = tempValue;
                break;
        }

        data[key] = value;
    });

    contactInfoData.value = data;
}

/**
 * Format the date strings into readable format.
 *
 * @param dateString
 * @returns {string}
 */
function formatDate(dateString) {
    // Create a Date object from the string
    const date = new Date(dateString);

    // Format the date to a readable format
    const formattedDate = date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });

    // Format the date time to a readable format
    const formattedTime = date.toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: true,
    });

    return `${formattedDate} \n ${formattedTime}`;
}

/**
 * Format the hostnames into truncated comma separated value.
 *
 * @param hostnames
 * @returns {string}
 */
function formatNameServers(hostnames) {
    const value = hostnames.toString()

    if (value.length > 25) {
        return value.substring(0, 22) + '...';
    }

    return value;
}

/**
 * Check if domainInfo has a property of data.
 *
 * @returns {boolean}
 */
function showInfo(type) {
    const data = (type === 'domain_info') ? domainInfoData.value : contactInfoData.value;
    return Object.keys(data).length > 0;
}

</script>

<template>
    <Head title="Domain Info"/>
    <div
        class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white"
    >
        <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
            <main class="mt-6">
                <div class="w-full text-center">
                    <h1 class="text-2xl">TLV300 - Domain Info Search</h1>
                </div>

                <form @submit.prevent="searchDomain" class="mt-6 space-y-6 w-4/6 m-auto bg-gray-400 rounded-xl p-3">
                    <div class="grid gap-4 grid-cols-6">
                        <div class="col-span-3">
                            <InputLabel for="domain" value="Domain" color="text-black-400" size="text-lg" />

                            <TextInput
                                id="domain"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.domain"
                                required
                                autofocus
                                autocomplete="domain"
                            />

                            <InputError class="mt-2" :message="form.errors.domain"/>
                        </div>

                        <div class="col-span-2">
                            <InputLabel for="type" value="Type of information" color="text-black-400" size="text-lg" />

                            <Select
                                id="type"
                                class="mt-1 block w-full"
                                v-model="form.type"
                                :options="typeOptions"
                                required
                            />

                            <InputError class="mt-2" :message="form.errors.type"/>
                        </div>

                        <div class="col-span-1 flex justify-center items-end">
                            <PrimaryButton>Search</PrimaryButton>
                        </div>
                    </div>
                </form>

                <div v-if="showInfo('domain_info')" class="mt-10">
                    <h2 class="text-2xl mb-4">Domain Information</h2>

                    <Table
                        :heads="initialDomainInfo.heads"
                        :rows="domainInfoData"
                        :keys="initialDomainInfo.keys"
                    />
                </div>

                <div v-if="showInfo('contact_info')" class="mt-10">
                    <h2 class="text-2xl mb-4">Contact Information</h2>

                    <Table
                        :heads="initialContactInfo.heads"
                        :rows="contactInfoData"
                        :keys="initialContactInfo.keys"
                    />
                </div>
            </main>
        </div>
    </div>
</template>
