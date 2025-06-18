<script setup>
import { Link, usePage,router} from '@inertiajs/vue3';
import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster({ });
const page = usePage();
function deleteRole(id){
    if(confirm("Are you sure you want to delete this role?")){
        router.get(`delete-role?role_id=${id}`);
    }
}

if(page.props.flash.status==true){
    toaster.success(page.props.flash.message);
}else if(page.props.flash.status==false){
    toaster.error(page.props.flash.message);

}
</script>

<template>
  <div class="p-6">
   <div class="flex justify-between">
        <h2 class="text-2xl font-bold mb-4">Role & Permissions</h2>
        <h1 v-if="page.props.user.can['create-role']"> <Link :href="`/role-save-page?role_id=${0}`" class="bg-green-500 hover:bg-green-700 text-white py-2 px-4 r rounded-sm">Create</Link></h1>
   </div>

    <table class="min-w-full bg-white rounded-lg shadow-md overflow-hidden">
      <thead class="bg-gray-100 text-left">
        <tr>
          <th class="px-4 py-3">Role ID</th>
          <th class="px-4 py-3">Role Name</th>
          <th class="px-4 py-3">Permissions</th>
          <th class="px-4 py-3">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr class="border-t" v-for="role in page.props.roles" :key="role.id">
          <td class="px-4 py-3">{{ role.id }}</td>
          <td class="px-4 py-3 font-medium">{{ role.name }}</td>
          <td class="px-4 py-3">
            <div class="flex flex-wrap gap-2">
              <span
                v-for="perm in role.permissions"
                :key="perm.id"
                class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full"
              >
                {{ perm.name }}
              </span>
            </div>
          </td>
          <td class="px-4 py-3 font-medium flex">
            <Link v-if="page.props.user.can['update-role']" :href="`/role-save-page?role_id=${role.id}`" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 r rounded-sm">Edit</Link>
            <button v-if="page.props.user.can['delete-role']" @click="deleteRole(role.id)" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded-sm ml-1">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<style scoped>

</style>
