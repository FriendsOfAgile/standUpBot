<template>
    <div class="w-full flex flex-col">
        <div class="flex p-6">
            <h3 class="font-bold text-2xl text-gray-700">Standup {{ standUpData.name }}</h3>
        </div>
        <div class="flex p-6">
            {{ standUpData }}
        </div>
    </div>
</template>

<script>
  export default {
    name: "Standup",
    data() {
      return {
        standUpData: {}
      }
    },
    mounted() {
      console.log(this.$route.params.id);
      if(this.$store.getters.getStandUpConfigs.length) {
        this.standUpData = this.$store.getters.getStandUpConfigData(Number(this.$route.params.id));
      } else {
        this.$store.dispatch('GET_STANDUP_CONFIGS').then( () => {
          this.standUpData = this.$store.getters.getStandUpConfigData(Number(this.$route.params.id));
        })
      }
    },
  }
</script>

<style scoped>

</style>
