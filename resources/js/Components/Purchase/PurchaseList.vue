<script setup>
import PurchaseDetails from './PurchaseDetails.vue';
 import { ref } from 'vue'
 import {router, usePage,Link,useForm} from '@inertiajs/vue3'
 const page=usePage();

 const headers = [
     {text:'ID',value:'id'},
     {text:'Product Name',value:'product.name'},
     { text: "Category", value: "product.category.name" },
     {text:'Reqisition no',value:'reqisition_no'},
     {text:'Vendor Name',value:'vendor.name'},
     {text:'Phone',value:'vendor.phone'},
     {text:'Address',value:'vendor.address'},
     {text:'Brand Name',value:'brand_name'},
     {text:'Price',value:'price'},
     {text:'Unit',value:'unit'},
     {text:'Unit Type',value:'product.unit_type'},
     {text:'Created Date',value:'created_at'},
     {text:'Remarks',value:'remarks'},
     {text:'Action',value:'action'},
 ]

 const items=ref(page.props.purchases.data);
 const searchField = ref(["id", "product_name", "category.name", "reqisition_no", "vendor_name", "brand_name", "remarks"]);
 const searchItem=ref();
 const modal = ref(false);

 const formatDate = (date) => {
    const d = new Date(date).toLocaleString();
    return d;
};

 function deletePurchase(purchase_id){
     if(confirm("Are you sure you want to delete this purchase?")){
        router.get(`/delete-purchase?purchase_id=${purchase_id}`);
     }
 }

const fromDate=new URLSearchParams(window.location.search).get('fromDate');
const toDate=new URLSearchParams(window.location.search).get('toDate');
const form=useForm({
    fromDate: fromDate,
    toDate: toDate,
});

const showModal = () => {
    modal.value = true;

}
function submitForm() {
    form.get('/list-purchase');
}
</script>

<template>
    <PurchaseDetails :products="items" v-model:modal="modal" :fromDate="form.fromDate" :toDate="form.toDate"/>
    <div class="p-4 bg-[#f8f8f8] ">
        <div class="row">
            <div class="col-md-6">
                <h1 class="d-flex text-2xl font-bold mb-4">Purchase List</h1>
            </div>

        </div>

        <div class="row mb-2">
            <div class="col-md-2 mb-1">
                <input type="text" class="mb-2 form-control form-control-sm" v-model="searchItem" placeholder="Search here"/>
            </div>

            <div class="col-md-2 mb-1">
                <input v-model="form.fromDate" type="date" class="form-control form-control-sm"/>
            </div>

            <div class="col-md-2 mb-1">
                <input v-model="form.toDate" type="date" class="form-control form-control-sm"/>
            </div>


            <div class="col-md-4 mb-2">
                <button @click="submitForm()" class="btn btn-success btn-sm mr-1">Search Filter</button>
                <Link class="btn btn-secondary btn-sm mr-1" :href="`/list-purchase`">Clear Search</Link>
                <button @click="showModal()" class="btn btn-primary btn-sm mr-1">View All</button>

            </div>

        </div>

        <div class="row mb-4">
            <div v-if="page.props.user.can['create-purchase']" class="col-md-12">
                <Link :href="`/purchase-save-page?purchase_id=${0}`" class="bg-green-500 text-white  py-2 px-4 square">Add Purchase</Link>
            </div>

        </div>


        <div class="row">
            <div class="col-md-12">
                <EasyDataTable :headers="headers" :items="items" alternating :rows-per-page="50" :search-field="searchField" :search-value="searchItem">
                    <template #item-created_at="{ created_at }">
                        {{ formatDate(created_at) }}
                    </template>
                    <template #item-action="{ id }">
                    <div class="flex flex-wrap gap-1 justify-center pt-1 pb-1">
                        <Link v-if="page.props.user.can['update-purchase']" :href="`/purchase-save-page?purchase_id=${id}`" class="bg-green-500 hover:bg-green-700 text-white py-1 px-2 square text-xs"><i class="bi bi-pencil"></i></Link>
                        <button v-if="page.props.user.can['delete-purchase']" @click="deletePurchase(id)" class="bg-red-500 hover:bg-red-600 text-white py-1 px-2 square text-xs"><i class="bi bi-trash"></i></button>
                    </div>

                    </template>
                </EasyDataTable>
            </div>
        </div>
           <!-- Pagination Buttons -->
        <div class="flex justify-center gap-4 mt-6">
            <Link preserve-scroll
                v-if="page.props.purchases.prev_page_url"
                :href="page.props.purchases.prev_page_url"
                class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 transition duration-300"
            >
                ⬅️ Previous
            </Link>
            <Link preserve-scroll
                v-if="page.props.purchases.next_page_url"
                :href="page.props.purchases.next_page_url"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300"
            >
                Next ➡️
            </Link>
        </div>

    </div>

</template>

<style scoped>
/* General input styles for making elements smaller */
.form-control-sm,
.form-select-sm,
.btn-sm {
    padding: 4px 8px;
    /* Smaller padding */
    font-size: 10px;
    /* Reduce font size */
    line-height: 18px;
    /* Adjust line height */
    border-radius: 0px;
    /* Smaller border radius */
}

/* Smaller select dropdown */
.form-select-sm {
    padding-right: 28px;
    /* Adjust padding for the dropdown icon */
    font-size: 10px;
}

/* Reduce margin around buttons */
.btn-sm {
    margin-right: 3px;
    margin-top: 0px;
    margin-bottom: 2px;
}

/* Custom styling for the button groups */
.btn-group-sm>.btn,
.btn-group-sm>.btn-group {
    padding: 4px 8px;
    font-size: 14px;
}

/* Adjust column layout to make space more compact */
.col-md-2,
.col-md-4 {
    padding-left: 15px;
    /* Reduce padding between columns */
    padding-right: 8px;
    max-width: 100%;
    /* Ensure inputs fit into grid */
}

/* Input fields should have less width for compact design */
input[type="date"],
select {
    max-width: 100%;
}

/* Specific button styling */
.btn-success.btn-sm,
.btn-secondary.btn-sm,
.btn-primary.btn-sm {
    padding: 3px 10px;
    font-size: 11px;
}

/* Adjust margin for the "View all" button */
.btn-primary.my-1 {
    margin-top: 4px;
    margin-bottom: 4px;
}

/* Adjust EasyDataTable padding */
.easy-data-table {
    padding: 8px;
}

/* Optional: Add a custom smaller font size for table items */
.easy-data-table td,
.easy-data-table th {
    font-size: 14px;
}
</style>
