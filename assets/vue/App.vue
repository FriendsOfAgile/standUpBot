<template>
  <div id="app">
    <router-view/>
  </div>
</template>


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
</style>
