<template>
    <div class="w-full flex">
        <div class="flex p-6">
            <h3 class="font-bold text-2xl text-gray-700">Dashboard</h3>
        </div>
    </div>

</template>

<script>

import DashboardLeftMenu from "../components/DashboardLeftMenu.vue";
export default {
  name: 'Home',
  components: {
    DashboardLeftMenu
  }
}
</script>
