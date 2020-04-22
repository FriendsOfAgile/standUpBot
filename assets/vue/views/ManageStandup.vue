<template>
    <div class="w-full flex">
        <DashboardLeftMenu />
        <div class="flex">
            <h3>Manage Standup</h3>
        </div>
    </div>
</template>

<script>
    import DashboardLeftMenu from "../components/DashboardLeftMenu.vue";
  export default {
    name: "ManageStandup",
    components: {
      DashboardLeftMenu
    }
  }
</script>

<style scoped>

</style>
