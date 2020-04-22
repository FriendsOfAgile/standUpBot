<template>
  <div id="app" class="w-full flex">
      <DashboardLeftMenu />
      <transition name="component-fade" mode="out-in">
          <router-view/>
      </transition>
  </div>
</template>



<script>
    import DashboardLeftMenu from "./components/DashboardLeftMenu";
    export default {
      components: {DashboardLeftMenu}
    }
</script>


<style>
    @font-face {
        font-family: "Rubik";
        src: url("../fonts/Rubik-Regular.ttf") format("ttf");
    }
    body {
        min-height: 100%;
        display: flex;
        flex-direction: column;
        font-family: 'Rubik', 'Roboto', sans-serif;
    }

    .router-link-active > svg {
        color: #7e91ff;
    }

    /* transitions */
    .component-fade-enter-active {
        transition: opacity .3s ease;
    }
    .component-fade-enter, .component-fade-leave-to
        /* .component-fade-leave-active below version 2.1.8 */ {
        opacity: 0;
    }
</style>
