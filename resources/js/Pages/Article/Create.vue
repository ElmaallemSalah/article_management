

<script setup>



import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import { Head, useForm } from "@inertiajs/vue3"

import AppLayout from "@/Layouts/AppLayout.vue";
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
    name: "",
    description: "",
    image: "",
    category: props.categories[0].id,
});

const submit = () => {
    form.post(route("article.store"));
};
</script>

<template>
   
   <AppLayout title="Dashboard">
    
    <template #header>
          <h2 class="text-xl font-semibold leading-tight text-gray-800">
              Edit article
          </h2>
      </template>
  



    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

     
        <div>


            <!-- Your form goes here -->
            <div class="py-12">
                <div class="mx-auto max-w-7xl">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <form @submit.prevent="submit" enctype="multipart/form-data">
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
                                    <InputLabel for="image" value="image" />

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
</AppLayout>
</template>
