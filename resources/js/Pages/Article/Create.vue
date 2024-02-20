

<script setup>



import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import { Head, useForm } from "@inertiajs/vue3"


const props = defineProps({
    company: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm({
    name: "",
    email: "",
    logo: "",
    website: "",
});

const submit = () => {
    form.post(route("company.store"));
};
</script>

<template>
    <Head title="New Company" />




    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

        <h3
            class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
            New Company</h3>

        <div>


            <!-- Your form goes here -->
            <div class="py-12">
                <div class="mx-auto max-w-7xl">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <form @submit.prevent="submit"  enctype="multipart/form-data">
                                <div class="mt-2">
                                    <InputLabel for="name" value="Name" />

                                    <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required
                                        autofocus />

                                    <InputError class="mt-2" :message="form.errors.name" />
                                </div>
                                <div class="mt-2">
                                    <InputLabel for="email" value="Email" />

                                    <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email"
                                        required autofocus />

                                    <InputError class="mt-2" :message="form.errors.email" />
                                </div>
                                <div class="mt-2">
                                    <InputLabel for="website" value="Website" />

                                    <TextInput id="website" type="text" class="mt-1 block w-full" v-model="form.website"
                                        required autofocus />

                                    <InputError class="mt-2" :message="form.errors.website" />
                                </div>
                                <div class="mt-2">
                                    <InputLabel for="logo" value="Logo" />

                                    <input type="file" @input="form.logo = $event.target.files[0]" />
                                    <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                                        {{ form.progress.percentage }}%
                                    </progress>

                                    <InputError class="mt-2" :message="form.errors.logo" />
                                </div>

                                <PrimaryButton class="mt-2" type="submit" :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing">
                                    Submit
                                </PrimaryButton>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>
