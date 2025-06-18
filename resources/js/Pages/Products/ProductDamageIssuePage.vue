<script setup>
import SideNav from '../../Layout/SideNav.vue';
import { useForm,usePage,router, Link } from '@inertiajs/vue3';
import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster({ });
const page = usePage();
const product_id=new URLSearchParams(window.location.search).get('product_id');
const form = useForm({
    issue: '',
    damage: '',
    machine_name: '',
    product_id:product_id ,
})

const submitForm = () => {
    if(confirm('Are you sure to issue this product?')){
       form.post('/issue-product',{
           preserveScroll: true,
           onSuccess: () => {
               if(page.props.flash.status==false){
                   toaster.error(page.props.flash.message);
               }else if(page.props.flash.status==true){
                   toaster.success(page.props.flash.message);
                   router.visit('/list-product');
               }
           }
       });
    }

}
</script>

<template>
<SideNav>
    <!-- component -->
<div class="  flex items-center justify-center p-4">
  <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">

    <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Issue / Damage</h2>

    <div class="float-end">
          <Link href="/list-product" class="btn btn-success mx-3 btn-sm">
          Back
          </Link>
      </div>

    <div class="mb-4 text-center">
      <p><span style="color: blueviolet;">Product Name:</span> {{ page.props.product.name }}</p>
      <p><span style="color: blueviolet;">Available Stock:</span> {{ page.props.product.unit }} {{ page.props.product.unit_type }}</p>
    </div>

    <form @submit.prevent="submitForm" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Issue</label>
        <input v-model="form.issue"
          type="text"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Damage</label>
        <input v-model="form.damage"
          type="text"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
        />
      </div>

         <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Machine Name</label>
        <input v-model="form.machine_name"
          type="text"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
        />
      </div>
      <button type="submit" class="mt-4 w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-lg transition-colors">
        Confirm
      </button>
    </form>
  </div>
</div>
</SideNav>

</template>

<style scoped>

</style>
