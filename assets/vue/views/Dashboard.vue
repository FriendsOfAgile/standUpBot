<template>
    <div class="w-full flex">
        <DashboardLeftMenu />
        <div class="flex">
            <h3>Dashboard</h3>
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
