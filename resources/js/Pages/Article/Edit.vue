

<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import {
    Head,
    useForm,
} from "@inertiajs/vue3";

const props = defineProps({
    article: {
        type: Object,
        default: () => ({}),
    },
    categories: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm({
    id: props.article.id,
    name: props.article.name,
    description: props.article.description,

    category: props.article.category_id,
    image: props.article.image,
});

const submit = () => {
    form.post(route("article.update", props.article.id));
};
</script>

<template>
    <Head title="Edit article" />




    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

        <h3
            class="p-5 text-lg font-semibold text-left text-gray-900 bg-white rtl:text-right dark:text-white dark:bg-gray-800">
            Edit article</h3>

        <div>


            <!-- Your form goes here -->
            <div class="py-12">
                <div class="mx-auto max-w-7xl">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="mb-2">
                                <img class="rounded-full h-60" :src="form.image" alt="user photo">
                            </div>

                            <form @submit.prevent="submit">
                                <div class="mt-2">
                                    <InputLabel for="name" value="Name" />

                                    <TextInput id="name" type="text" class="block w-full mt-1" v-model="form.name" 
                                        autofocus />

                                    <InputError class="mt-2" :message="form.errors.name" />
                                </div>
                                <div class="mt-2">
                                    <InputLabel for="description" value="Description" />

                                    <TextInput id="description" type="text" class="block w-full mt-1" v-model="form.description"
                                          />

                                    <InputError class="mt-2" :message="form.errors.description" />
                                </div>
                                <div class="mt-2">
                                    <InputLabel for="category" value="Category" />

                                    <select id="articlesSelect" v-model="form.category"
                                        class="h-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option v-for="(cat, index) in categories" :key="index"  :value="cat.id">{{ cat.name }}</option>
                                    </select>

                                    <InputError class="mt-2" :message="form.errors.category" />
                                </div>
                                <div class="mt-2">
                                    <InputLabel for="image" value="Image" />

                                    <input type="file" @input="form.image = $event.target.files[0]" />
                                    <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                                        {{ form.progress.percentage }}%
                                    </progress>

                                    <InputError class="mt-2" :message="form.errors.image" />
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
