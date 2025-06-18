<script setup>
import { usePage, useForm } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
import { router, Link } from "@inertiajs/vue3";
import { computed } from "vue";
const errors = computed(() => page.props.flash.errors || {});

const toaster = createToaster({});
const page = usePage();
const vendorId = new URLSearchParams(window.location.search).get(
    "vendor_id"
);
const vendor = page.props.vendor;

const form = useForm({
    vendor_id: vendorId,
    name: "",
    phone: "",
    address: "",

});
let URL = "/create-vendor";
if (vendorId != 0 && vendor != null) {
    form.name = vendor.name;
    form.phone = vendor.phone;
    form.address = vendor.address;
    URL = "/update-vendor";
}

function submitForm() {
    form.post(URL, {
        preserveScroll: true,
        onSuccess: () => {
            if (page.props.flash.status == false) {
                toaster.error(page.props.flash.message);
            } else if (page.props.flash.status == true) {
                toaster.success(page.props.flash.message);
                router.visit("/list-vendor");
            }
        },
    });
}
</script>

<template>
    <div class="container mx-auto py-8">


        <form @submit.prevent="submitForm" class="w-full max-w-lg mx-auto bg-white p-8 rounded-md shadow-md">



            <div class="float-end">
                <Link href="/list-vendor" class="btn btn-success mx-3 btn-sm">
                Back
                </Link>
            </div>

            <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                {{ vendorId == 0 ? "Add Vendor" : "Update Vendor" }}
            </h2>


            <div>
                <label
                    for="name"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Name</label
                >
                <input
                    v-model="form.name"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p v-if="errors.name" class="text-red-500">{{ errors.name[0] }}</p>
            </div>

             <div>
                <label
                    for="phone"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Phone</label
                >
                <input
                    v-model="form.phone"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p v-if="errors.phone" class="text-red-500">{{ errors.phone[0] }}</p>
            </div>

             <div>
                <label
                    for="address"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Address</label
                >
                <input
                    v-model="form.address"
                    type="text"
                    class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <p v-if="errors.address" class="text-red-500">{{ errors.address[0] }}</p>
            </div>

            <div class="pt-3">
                <button
                    type="submit"
                    class="w-full bg-green-600 text-white py-2 rounded-xl hover:bg-green-700 transition duration-300"
                >
                    {{ vendorId == 0 ? "Add Vendor" : "Update Vendor" }}
                </button>
            </div>
        </form>
    </div>
</template>

<style scoped></style>
