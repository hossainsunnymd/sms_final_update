<script setup>
import { ref } from "vue";
const props = defineProps({
    products: Object,
    modal: Boolean,
});


const emit = defineEmits(["update:modal"]);
const printModal = () => {
    const printContents = document.getElementById("modal-content").innerHTML;
    const originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();
};
</script>

<template>
    <div
        v-if="modal"
        class="fixed inset-0 z-50 flex items-center justify-center"
    >
        <div id="modal-content"
            class="bg-white w-[1000px] max-w-full rounded-xl shadow-2xl p-6 relative print:w-full print:shadow-none print:p-0 h-[500px] overflow-auto"
        >
            <!-- Close Button -->
            <!-- <button
                type="button"
                class="btn btn-sm btn-danger position-absolute top-0 end-0 m-3 print-hidden"
                @click="$emit('update:modal', false)"
            >
                ×
            </button> -->


            <!-- Print Button -->
            <!-- <button
                @click="printModal"
                class="absolute top-3 left-3 text-sm bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition print:hidden"
            >
                🖨️ Print
            </button> -->

            <div class="flex justify-between mb-4">
                <div class="mt-4">
                    <h1 class="text-xl font-bold">Store Purchase Requisition</h1>
                    <p>Date: {{ new Date(props.products.created_at).toLocaleString().split(",")[0] }}</p>
                </div>
                <div class="font-bold">
                    <img class="h-[90px]" src="../../Assets/img/logo.jpg" alt="Logo" />
                </div>
            </div>


            <div class="mb-4 text-xl">
              <p><strong>Requisition No:</strong> {{ props.products.id }}</p>
            </div>

            <!-- Title -->
            <!-- <h1 class="text-2xl font-bold text-center pb-2">
                Ideal Fibre Industries Ltd.
            </h1>
            <h1 class="text-sm font-bold text-center pb-2">
                Madya Norshingpur, Kashipur,Fatullah,Nayaranganj
            </h1>
            <h1 class="text-1xl font-bold text-center mb-6 border-b pb-2">
                Store PURCHASE REQUISITION
            </h1>
            <div class="flex flex-col items-end mb-2">
                <h1 class="w-[150px] border">Indent No:</h1>
                <h1 class="w-[150px] border print:text-left">Date:</h1>
            </div> -->

            <!-- Table -->
            <div class="overflow-x-auto overflow-y-auto">
                <table class="w-full mb-4 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">
                                Name of Item
                            </th>
                             <th class="text-center">
                               Product Description
                            </th>
                            <th class="text-center">Size</th>
                            <th class="text-center">Unit</th>
                            <th class="text-center">
                                Required Quantity
                            </th>
                            <th class="text-center">
                                Present Stock
                            </th>
                            <th class="text-center">
                                Store Code
                            </th>
                            <th class="text-center">
                                Where to be use
                            </th>
                            <th class="text-center">
                                Remarks
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(product, index) in props.products
                                .requisition_products"
                            :key="product.id"
                            class="hover:bg-gray-50 border-1 print:border-2"
                        >
                            <td class="text-center">{{ index + 1 }}</td>
                            <td class="text-center">
                                {{ product.product.name }}
                            </td>
                               <td class="text-center">
                                {{ product.product.description }}
                            </td>
                            <td class="text-center">{{product.size}}</td>
                            <td class="text-center">
                                {{ product.product.unit_type }}
                            </td>
                            <td class="text-center">
                                {{ product.total_requisition }}
                            </td>
                            <td class="text-center">
                                {{ product.product.unit }}
                            </td>
                            <td class="text-center">
                                {{ product.store_code }}
                            </td>
                            <td class="text-center">
                                {{ product.where_to_use }}
                            </td>
                            <td class="text-center">
                                {{ product.remarks }}
                            </td>
                        </tr>
                    </tbody>
                </table>


                <!-- Action Buttons -->
                <div class="flex justify-between mt-6">
                    <button
                        @click="$emit('update:modal', false)"
                        class="bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600 print:hidden"
                    >
                        Close
                    </button>
                    <button
                       @click="printModal"
                        class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 print:hidden"
                    >
                        Print
                    </button>
                </div>


            </div>

            <!-- Footer -->
            <div class="mt-6 text-center text-xs text-gray-500 print:hidden">
                Press the Print button or Ctrl+P to print this summary.
            </div>
            <div class="absolute bottom-2 text-sm font-bold w-full hidden print:block">
                <div class="flex justify-between">
                    <p class="border-t-1">Prepared by</p>
                    <p class="border-t-1">Manager(store)</p>
                    <p class="border-t-1">Manager(Maintenence)</p>
                    <p class="border-t-1">Manager(Prodt.)</p>
                    <p class="border-t-1">G M(F & A)</p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.fixed {
    position: fixed;
}
.bg-opacity-50 {
    background-color: rgba(0, 0, 0, 0.5);
}
.z-50 {
    z-index: 50;
}
.bg-white {
    background-color: white;
}
.border {
    border: 1px solid black;
}
.rounded-lg {
    border-radius: 8px;
}
.shadow-lg {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
.p-6 {
    padding: 1.5rem;
}
.mb-4 {
    margin-bottom: 1rem;
}
.font-bold {
    font-weight: bold;
}
.text-xl {
    font-size: 1.25rem;
}
.text-left {
    text-align: left;
}
.text-center {
    text-align: center;
}
.text-right {
    text-align: right;
}
.flex {
    display: flex;
}
.justify-between {
    justify-content: space-between;
}
.items-center {
    align-items: center;
}

.hover\:bg-red-600:hover {
    background-color: #e53e3e;
}
.hover\:bg-blue-600:hover {
    background-color: #3182ce;
}
table {
    width: 100%;
    border-collapse: collapse;
}
th,td,tr {
    padding: 8px;
    border: 1px solid black;
    text-align: left;
}
</style>
