<script setup>
import IssueDetails from "./IssueDetails.vue";
import { usePage, useForm, Link } from "@inertiajs/vue3";
import { ref } from "vue";
const page = usePage();

const headers = [
    { text: "Id", value: "id" },
    { text: "Product Name", value: "product.name" },
    { text: "Issue Qty", value: "unit" },
    { text: "Unit Type", value: "product.unit_type" },
    { text: "Machine Name", value: "machine_name" },
    { text: "Issue Date", value: "issue_date" },
];
const items = ref(page.props.issueProducts.data);

const searchField = ref(["id", "product.name"]);
const searchItem = ref();
const formatDate = (date) => {
    const d = new Date(date).toLocaleString();
    return d;
};

const modal = ref(false);
const fromDate = new URLSearchParams(window.location.search).get("fromDate");
const toDate = new URLSearchParams(window.location.search).get("toDate");
const form = useForm({
    fromDate: fromDate,
    toDate: toDate,
});
function submitForm() {
    form.get("/issue-product-list");
}

function showModal() {
    modal.value = true;
}
</script>

<template>
    <IssueDetails
        v-model:modal="modal"
        :issueProduct="items"
        :fromDate="form.fromDate"
        :toDate="form.toDate"
    />
    <div class="p-4 bg-[#ffffff]">
        <div class="row">
            <div class="col-md-6">
                <h1 class="d-flex text-2xl font-bold mb-4">
                    Product Issue List
                </h1>
            </div>
        </div>

        <div class="row mb-1">
            <div class="col-md-2 mb-1">
                <input
                    type="text"
                    class="mb-2 form-control form-control-sm"
                    v-model="searchItem"
                    placeholder="Search here"
                />
            </div>

            <div class="col-md-2 mb-1">
                <input
                    v-model="form.fromDate"
                    type="date"
                    class="form-control form-control-sm"
                />
            </div>

            <div class="col-md-2 mb-1">
                <input
                    v-model="form.toDate"
                    type="date"
                    class="form-control form-control-sm"
                />
            </div>

            <div class="col-md-4 mb-2">
                <button
                    @click="submitForm()"
                    class="btn btn-success btn-sm mr-1"
                >
                    Search Filter
                </button>
                <Link
                    class="btn btn-secondary btn-sm mr-1"
                    :href="`/issue-product-list`"
                    >Clear Search</Link
                >
                <button
                    @click="showModal()"
                    class="btn btn-primary btn-sm mr-1"
                >
                    View All
                </button>
            </div>
        </div>

        <EasyDataTable
            :headers="headers"
            :items="items"
            alternating
            :rows-per-page="50"
            :search-field="searchField"
            :search-value="searchItem"
        >
            <template #item-issue_date="{ created_at }">
                {{ formatDate(created_at) }}
            </template>
        </EasyDataTable>
        <!-- Pagination Buttons -->
        <div class="flex justify-center gap-4 mt-6">
            <Link
                v-if="page.props.issueProducts.prev_page_url"
                :href="page.props.issueProducts.prev_page_url"
                class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 transition duration-300"
            >
                ⬅️ Previous
            </Link>
            <Link
                v-if="page.props.issueProducts.next_page_url"
                :href="page.props.issueProducts.next_page_url"
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
.btn-group-sm > .btn,
.btn-group-sm > .btn-group {
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
