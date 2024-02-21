<script setup>
import {
    ref,
    watch,
} from "vue";

import debounce from "lodash/debounce";
import moment from "moment";
import Swal from "sweetalert2";

import DateMaxInput from "@/Components/DateMaxInput.vue";
import DateMinInput from "@/Components/DateMinInput.vue";
import DeleteBtn from "@/Components/DeleteBtn.vue";
import EditBtn from "@/Components/EditBtn.vue";
import Pagination from "@/Components/Pagination.vue";
import SearchInput from "@/Components/SearchInput.vue";
import SwalCom from "@/Components/Swal.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import {
    Head,
    Link,
    router,
    useForm,
} from "@inertiajs/vue3";

router.on('success', (event) => {
   
})
const props = defineProps({
    articles: {
        type: Object,
        default: () => ({}),
    },
    search: {
        type: String,

    },

    perPage: {
        type: String,

    }
});
const form = useForm({});

function destroy(id) {

    Swal.fire({
        title: "Are you sure you want to Delete?",
        showDenyButton: false,
        showCancelButton: true,
        confirmButtonText: "Yes, Delete it!",
        denyButtonText: `Don't Delete it!`
    }).then((result) => {



        const page = form.$route

        // Use the parameters as needed
        console.log('param1:', page);
        if (result.isConfirmed) {

            form.delete(route("article.destroy",
                id));
        }
    });
}


let search = ref(props.search);
const perPage = ref(props.perPage !== null ? props.perPage : '20');

watch([search, perPage ], debounce(function([searchVal, perPageVal]) {


  router.get('/articles', {
    perPage: perPageVal,
    search: searchVal,
 
  }, {
    preserveState: true,replace : true
  });
},650 ))
</script>



<template>
     <AppLayout title="Dashboard">

        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
               Articles
            </h2>
        </template>
    <SwalCom v-if="$page?.props.flash?.status" :status="$page?.props.flash?.status" />

    




    <div class="mx-32 my-6 overflow-x-auto shadow-md sm:rounded-lg">
        <div class="m-6">
            <div class="flex items-center justify-between">
                <h3
                    class="text-lg font-semibold text-left text-gray-900 rtl:text-right dark:text-white dark:bg-gray-800">
                    All Articles
                </h3>
                <div class="ml-auto">
                    <Link   v-if="$page.props.auth.user.permissions.includes('create')" :href="route('article.create')" type="button"
                        class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 shadow-lg shadow-green-500/50 dark:shadow-lg dark:shadow-green-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                    Add New
                    </Link>
                </div>
            </div>
        </div>


        <SearchInput v-model="search" placeholder="Search article name , description, users name and  category name " />

        <!-- date select -->
        <!-- date select -->
        <div class="z-50 flex mb-2 justify-evenly">
      
        </div>



        <!-- end date select -->




        <table id="datatable" class="w-full text-sm text-left text-gray-500 rtl:text-right dark:text-gray-400">



            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Description
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Category
                    </th>
                    <th scope="col" class="px-6 py-3">
                        User_name
                    </th>
                   
                    <th scope="col" class="px-6 py-3">
                        Image
                    </th>

                    <th scope="col" class="px-6 py-3">
                        Created At
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <span class="sr-only">Edit</span>

                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(article, index) in articles.data " :key="index"
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ article.id }}
                    </th>
                    <td class="px-6 py-4">
                        {{ article.name }}
                    </td>
                    <td class="px-6 py-4">
                        {{ article.description }}
                    </td>
                    <td class="px-6 py-4">
                        {{ article.category_name }}
                    </td>
                    <td class="px-6 py-4">
                        {{ article.user_name }}
                    </td>
                  
                    <td class="px-6 py-4">
                     
                        <img class="w-8 h-8 rounded-full" :src="article.image" alt="Article_image">

                    </td>
                    <td class="px-6 py-4">
                        {{ moment(article.created_at).format('YYYY-MM-DD') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex">

                            <EditBtn   v-if="$page.props.auth.user.permissions.includes('edit')" :href="route('article.edit', article.id)"/>

                            
                            <DeleteBtn   v-if="$page.props.auth.user.permissions.includes('delete')"  @click="destroy(article.id)"/>

                            
                        </div>

                    </td>
                </tr>

            </tbody>
        </table>
        <div class="flex justify-between m-2">
            <div class="flex">
                <select id="articlesSelect" v-model="perPage"
                    class="h-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="20">20</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                    <option value="500">500</option>
                    <option value="ALL">ALL</option>
                </select>
            </div>
            <div class="flex">
                <pagination class="" :links="articles.links" />
            </div>
        </div>




    </div>
</AppLayout>
</template>
