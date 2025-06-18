<script setup>
import { usePage, useForm, Link } from "@inertiajs/vue3";
import ProductStockDetails from "./ProductStockDetails.vue";
import ProductStockDetailsReport from "./ProductStockDetailsReport.vue";
import { ref, onMounted } from "vue";
import axios from "axios";

const page = usePage();
const isLoading = ref(false);
const items = ref([]);
const modal = ref(false);
const reportModal = ref(false);

const headers = [
    { text: "Name", value: "product_name" },
    { text: "Category", value: "category_name" },
    { text: "Parts No", value: "parts_no" },
    { text: "Rack", value: "rack_no" },
    { text: "Column", value: "column_no" },
    { text: "Row", value: "row_no" },
    { text: "Floor Received", value: "floor_recieve" },
    { text: "Total Received", value: "total_received" },
    { text: "Total Issue", value: "total_issue" },
    { text: "Available stock", value: "available_unit" },
    { text: "Unit Type", value: "unit_type" },
];

const searchField = ref([
    "product_name",
    "parts_no",
    "rack_no",
    "column_no",
    "row_no",
]);
const searchItem = ref("");
const category_name = ref("");
const categories = ref([]);

const form = useForm({
    fromDate:'' ,
    toDate: '',
    category_id: '' ,
});

const getAllProducts = async () => {
    isLoading.value = true;
    try {
        const res = await axios.get(`/product-stock-list`);
        items.value = res.data.allProducts.productList;
        category_name.value = res.data.allProducts.category_name;
        categories.value = res.data.allProducts.categories;
    } catch (error) {
        console.error("Failed to load product stock list", error);
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    getAllProducts();
});

const formatDate = (dateStr) => {
    if (!dateStr) return "-";
    const date = new Date(dateStr);
    return date.toLocaleDateString("en-GB", {
        day: "2-digit",
        month: "short",
        year: "numeric",
    });
};

const submitForm = async () => {
    isLoading.value = true;
    try {
        const res = await axios.get(`/product-stock-list?fromDate=${form.fromDate}&toDate=${form.toDate}&category_id=${form.category_id}`);
        items.value = res.data.allProducts.productList;
        category_name.value = res.data.allProducts.category_name;
        categories.value = res.data.allProducts.categories;
    } catch (error) {
        console.error("Failed to load product stock list", error);
    } finally {
        isLoading.value = false;
    }
};


const showModal = () => (modal.value = true);
const showReportModal = () => (reportModal.value = true);
</script>

<template>
    <ProductStockDetails
        v-model:modal="modal"
        :items="items"
        :fromDate="form.fromDate"
        :toDate="form.toDate"
    />
    <ProductStockDetailsReport
        v-model:reportModal="reportModal"
        :categories="categories"
        :fromDate="form.fromDate"
        :toDate="form.toDate"
    />

    <!-- Loading Overlay -->
    <div
        class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center z-50"
        v-if="isLoading"
    >
        <div class="flex flex-col items-center space-y-2">
            <svg
                class="animate-spin h-10 w-10 text-white"
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
                />
                <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                />
            </svg>
            <p class="text-white text-lg font-semibold animate-pulse">
                Loading...
            </p>
        </div>
    </div>

    <div class="container p-4 bg-white">
        <div class="mb-4">
            <h1 class="text-2xl font-bold">All Product Stock List</h1>
        </div>

        <div class="flex flex-wrap gap-2 mb-3">
            <input
                v-model="searchItem"
                type="text"
                class="form-control form-control-sm w-48"
                placeholder="Search here"
            />
            <select
                v-model="form.category_id"
                class="form-select form-select-sm w-48"
            >
                <option value="">Select Category</option>
                <option
                    v-for="category in categories"
                    :key="category.id"
                    :value="category.id"
                >
                    {{ category.name }}
                </option>
            </select>
            <input
                v-model="form.fromDate"
                type="date"
                class="form-control form-control-sm w-36"
            />
            <input
                v-model="form.toDate"
                type="date"
                class="form-control form-control-sm w-36"
            />
            <button @click="submitForm" class="btn btn-success btn-sm">
                Search Filter
            </button>
            <Link class="btn btn-secondary btn-sm" :href="`/product-stock-list-page`"
                >Clear</Link
            >
            <button @click="showModal" class="btn btn-primary btn-sm">
                View All
            </button>
            <button
                v-if="page.props.user.can['product-stock-report']"
                @click="showReportModal"
                class="btn btn-primary btn-sm"
            >
                Report
            </button>
        </div>

        <div class="mb-3 text-sm text-gray-700">
            <p>
                <span class="font-semibold text-purple-700"
                    >Searching Period:</span
                >
                {{ formatDate(form.fromDate) }} - {{ formatDate(form.toDate) }}
            </p>
            <p>
                <span class="font-semibold text-purple-700"
                    >Selected Category:</span
                >
                {{ category_name || "-" }}
            </p>
        </div>

        <EasyDataTable
            :headers="headers"
            :items="items"
            alternating
            :rows-per-page="50"
            :search-field="searchField"
            :search-value="searchItem"
        />
    </div>
</template>

<style scoped>
.form-control-sm,
.form-select-sm,
.btn-sm {
    padding: 4px 8px;
    font-size: 11px;
    border-radius: 0;
    line-height: 1.4;
}

.btn-sm {
    margin-right: 5px;
}

.easy-data-table td,
.easy-data-table th {
    font-size: 13px;
}
</style>
