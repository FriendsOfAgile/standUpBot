<template>
    <div class="w-full flex flex-col">
        <div class="flex p-6">
            <h3 class="font-bold text-2xl text-gray-700">Manage Standup</h3>
        </div>
        <div class="w-full">
            {{ currentStandUps }}
        </div>
    </div>
</template>

<script>
  export default {
    name: "ManageStandup",
    data() {
      return {
      }
    },
    computed: {
      currentStandUps() {
        return this.$store.getters.getStandUpConfigs;
      }
    },
  }
</script>

<style scoped>

</style>
