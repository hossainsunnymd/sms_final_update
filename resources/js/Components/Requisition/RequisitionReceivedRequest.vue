<script setup>
import { usePage,Link,router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster({ });
const page = usePage();
const headers = [
    //{text:'ID',value:'id'},
    {text:'Requisition No',value:'requisition_product.requisition.id'},
    {text:'Product Name',value:'product.name'},
    {text:'Total Requistion',value:'requisition_product.total_requisition'},
    {text:'Received Qty',value:'received_qty'},
    {text:'Unit Type',value:'product.unit_type'},
    {text:'Status',value:'status'},
    {text:'Action',value:'action'},
]
const items=ref(page.props.recievedRequests);



function approvedRequest(received_id) {
    if(confirm('Are you sure you want to approved this request?')){
        router.visit(`/requisition-approve-request?received_id=${received_id} & status=approved`);
    }
}
function cancelRequest(received_id) {
    if(confirm('Are you sure you want to Cancel this request?')){
        router.visit(`/requisition-approve-request?received_id=${received_id} & status=cancel`);
    }
}

if(page.props.flash.status==true){
    toaster.success(page.props.flash.message);
}else if(page.props.flash.status==false){
    toaster.error(page.props.flash.message);

}
</script>

<template>

    <div class="container p-4 bg-[#ffffff]">
        <div class="row">
            <div class="col-md-12">
                <h1 class="d-flex text-2xl font-bold mb-4">Approve Requisition</h1>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <EasyDataTable :headers="headers" :items="items" alternating :rows-per-page="50">
                    <template #item-action="{ id }">
                        <div class="flex flex-wrap gap-1 justify-center pt-1 pb-1">
                            <Link :href="`/edit-requisition-request-page?id=${id}`"  class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 square text-xs"><i class="bi bi-pencil"></i></Link>
                            <button @click="approvedRequest(id)" class="bg-green-500 hover:bg-green-700 text-white py-1 px-2 square text-xs">Approve</button>
                            <button @click="cancelRequest(id)" class="bg-red-500 hover:bg-red-700 text-white py-1 px-2 square text-xs"><i class="bi bi-x"></i></button>

                        </div>

                    </template>
                </EasyDataTable>
            </div>
        </div>
    </div>






</template>

<style scoped>

</style>
