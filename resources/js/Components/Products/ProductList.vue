<script setup>
import { usePage, Link, router } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import { ref, watch } from "vue";

const toaster = createToaster({});
const page = usePage();


const url = new URL(window.location.href);
const searchItem = ref(url.searchParams.get("search") || "");


const headers = [
    { text: "ID", value: "id" },
    { text: "Image", value: "image" },
    { text: "Product Name", value: "name" },
    { text: "Product Description", value: "description" },
    { text: "Category", value: "category.name" },
    { text: "Brand", value: "brand_name" },
    { text: "Quantity", value: "unit" },
    { text: "Unit Type", value: "unit_type" },
    { text: "Rack", value: "rack_no" },
    { text: "Column", value: "column_no" },
    { text: "Row", value: "row_no" },
    { text: "Action", value: "action" },
];

const items = ref(page.props.products.data);

const searchField = ref(["id", "name", "category.name", "parts_no"]);


watch(searchItem, (value) => {
    router.visit(window.location.pathname + `?search=${value}`, {
        preserveScroll: true,
        preserveState: true,
    });
});



function deleteProduct(porduct_id) {
    if (confirm("Are you sure you want to delete this product?")) {
        router.visit(`/delete-product?product_id=${porduct_id}`);
    }
}


if (page.props.flash.status == true) {
    toaster.success(page.props.flash.message);
} else if (page.props.flash.status == false) {
    toaster.error(page.props.flash.message);
}
</script>

<template>
    <div class="container p-4 bg-[#ffffff]">
        <h1 class="text-2xl font-bold mb-4">Product List</h1>


        <input
            type="text"
            class="border border-gray-300 rounded-md px-4 py-2 w-[300px]"
            v-model="searchItem"
            placeholder="Search here"
        />

        <!--  Download Report Button -->
        <a
            v-if="page.props.user.can['product-report']"
            class="btn btn-primary btn-sm ml-1"
            href="/product-list-report"
        >
            Download Report
        </a>

        <!--  Add Product Button -->
        <div v-if="page.props.user.can['create-product']" class="mt-4 mb-4">
            <Link
                v-if="page.props.user.role != 'moderator'"
                :href="`/product-save-page?product_id=0`"
                class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 square"
            >
                Add New Product
            </Link>
        </div>

        <!--  Product Table -->
        <EasyDataTable
            :headers="headers"
            :items="items"
            alternating
            :rows-per-page="50"
            :search-field="searchField"
            :search-value="searchItem"
        >
            <template #item-image="{ image }">
                <div class="py-2">
                    <img
                        v-if="image"
                        :src="`/uploads/${image}`"
                        :alt="image"
                        width="40px"
                        class="object-cover"
                    />
                </div>
            </template>

            <template #item-action="{ id }">
                <div class="flex flex-wrap gap-1 justify-center pt-1 pb-1">
                    <Link
                        v-if="page.props.user.can['update-product']"
                        :href="`/product-save-page?product_id=${id}`"
                        class="bg-green-500 hover:bg-green-700 text-white py-1 px-2 square text-xs"
                    >
                        <i class="bi bi-pencil"></i>
                    </Link>
                    <button
                        v-if="page.props.user.can['delete-product']"
                        @click="deleteProduct(id)"
                        class="bg-red-500 hover:bg-red-600 text-white py-1 px-2 square text-xs"
                    >
                        <i class="bi bi-trash"></i>
                    </button>
                    <Link
                        v-if="page.props.user.can['issue-product']"
                        :href="`/product-issue-page?product_id=${id}`"
                        class="bg-indigo-500 hover:bg-indigo-600 text-white py-1 px-2 square text-xs"
                    >
                        Issue
                    </Link>
                    <Link
                        v-if="page.props.user.can['receive-floor-receive']"
                        :href="`/floor-recieve-page?product_id=${id}`"
                        class="bg-gray-500 hover:bg-gray-600 text-white py-1 px-2 square text-xs"
                    >
                        Floor Receive
                    </Link>
                </div>
            </template>
        </EasyDataTable>

        <!--  Pagination Buttons with Search Query -->
        <div class="flex justify-center gap-4 mt-6">
            <Link
                preserve-scroll
                v-if="page.props.products.prev_page_url"
                :href="`${page.props.products.prev_page_url}&search=${searchItem}`"
                class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 transition duration-300"
            >
                ⬅️ Previous
            </Link>
            <Link
                preserve-scroll
                v-if="page.props.products.next_page_url"
                :href="`${page.props.products.next_page_url}&search=${searchItem}`"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300"
            >
                Next ➡️
            </Link>
        </div>
    </div>
</template>
