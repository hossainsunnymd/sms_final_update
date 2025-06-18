<script setup>
import { usePage, Link, router } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import { ref } from "vue";
import axios from "axios";
const toaster = createToaster({});
const page = usePage();
const isLoading = ref(false);

const headers = [
    { text: "ID", value: "id" },
    { text: "Image", value: "image" },
    { text: "Product Name", value: "name" },
    { text: "Product Description", value: "description" },
    { text: "Category", value: "category.name" },
    { text: "Brand", value: "brand_name" },
    { text: "Quantity", value: "unit" },
    { text: "Unit Type", value: "unit_type" },
    //{ text: "Parts no", value: "parts_no" },
    { text: "Rack", value: "rack_no" },
    { text: "Column", value: "column_no" },
    { text: "Row", value: "row_no" },
    { text: "Action", value: "action" },
];
const items = ref(page.props.products.data);
const getAllProducts = async () => {
    isLoading.value = true;
    const res = await axios.get("/product-list");
    items.value = res.data.products;
    isLoading.value = false;
};

const searchField = ref(["id", "name", "category.name", "parts_no"]);
const searchItem = ref();

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
    <!-- Loading animatin start -->

    <!-- Loading Overlay -->
    <div
        class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center z-50"
        v-if="isLoading"
    >
        <div class="flex flex-col items-center space-y-2">
            <svg
                class="animate-spin h-10 w-10 text-white"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
            >
                <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                ></circle>
                <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                ></path>
            </svg>
            <p class="text-white text-lg font-semibold animate-pulse">
                Loading...
            </p>
        </div>
    </div>

    <!-- Loading animatin End -->
    <div class="container p-4 bg-[#ffffff]">
        <h1 class="d-flex text-2xl font-bold mb-4">Product List</h1>

        <input
            type="text"
            class="border border-gray-300 rounded-md px-4 py-2 w-[300px]"
            v-model="searchItem"
            placeholder="Search here"
        />
        <a
            v-if="page.props.user.can['product-report']"
            class="btn btn-primary btn-sm ml-1"
            href="/product-list-report"
            >Download Report</a
        >
        <button @click="getAllProducts()" class="btn btn-info btn-sm ml-1">
            Show all
        </button>

        <div v-if="page.props.user.can['create-product']" class="mt-4 mb-4">
            <Link
                v-if="page.props.user.role != 'moderator'"
                :href="`/product-save-page?product_id=${0}`"
                class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 square"
                >Add New Product</Link
            >
        </div>

        <div class="row">
            <div class="col-md-12">
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
                        <div
                            class="flex flex-wrap gap-1 justify-center pt-1 pb-1"
                        >
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
                                v-if="
                                    page.props.user.can['receive-floor-receive']
                                "
                                :href="`/floor-recieve-page?product_id=${id}`"
                                class="bg-gray-500 hover:bg-gray-600 text-white py-1 px-2 square text-xs"
                            >
                                Floor Receive
                            </Link>
                        </div>
                    </template>
                </EasyDataTable>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
