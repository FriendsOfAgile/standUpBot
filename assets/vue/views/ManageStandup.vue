<template>
    <div class="w-full flex flex-col">
        <div class="flex p-6">
            <h3 class="font-bold text-2xl text-gray-700">Manage Standup</h3>
        </div>
        <div class="w-full">
            {{ standupConfigs }}
        </div>
    </div>
</template>

<script>
  export default {
    name: "ManageStandup",
    data() {
      return {
        standupConfigs: []
      }
    },
    mounted() {
     return this.$store.dispatch('getStandUpConfigs')
       .then( () => {
         this.standupConfigs = this.$store.state.standupConfigs;
         console.log(this.$store.state.standupConfigs);
       })
    }
  }
</script>

<style scoped>

</style>
