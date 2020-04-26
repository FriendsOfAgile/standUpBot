<template>
    <div class="w-full flex flex-col">
        <div class="flex p-6">
            <h3 class="font-bold text-2xl text-gray-700">Standup</h3>
        </div>
    </div>
</template>

<script>
  export default {
    name: "Standup",
    mounted() {
      console.log(this.$route.params);
    }
  }
</script>

<style scoped>

</style>
