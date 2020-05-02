<template>
    <div class="loader-container">
        <div class="loader-item">
            <div class="loader-item-dot"></div>
            <div class="loader-item-dot"></div>
            <div class="loader-item-dot"></div>
            <div class="loader-item-dot"></div>
            <div class="loader-item-dot"></div>
            <div class="loader-item-dot"></div>
        </div>
    </div>
</template>

<script>
  export default {
    name: "Loader"
  }
</script>

<style scoped>
    .loader-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .loader-item {
        width: 40px;
        height: 40px;
        position: relative;
        animation: loader-item 2.5s infinite linear both;
    }

    .loader-item-dot {
        width: 100%;
        height: 100%;
        position: absolute;
        left: 0;
        top: 0;
        animation: loader-item-dot 2.0s infinite ease-in-out both;
    }

    .loader-item-dot:before {
        content: '';
        display: block;
        width: 25%;
        height: 25%;
        background-color: #7e91ff;
        border-radius: 100%;
        animation: loader-item-dot-before 2.0s infinite ease-in-out both;
    }

    .loader-item-dot:nth-child(1) { animation-delay: -1.1s; }
    .loader-item-dot:nth-child(2) { animation-delay: -1.0s; }
    .loader-item-dot:nth-child(3) { animation-delay: -0.9s; }
    .loader-item-dot:nth-child(4) { animation-delay: -0.8s; }
    .loader-item-dot:nth-child(5) { animation-delay: -0.7s; }
    .loader-item-dot:nth-child(6) { animation-delay: -0.6s; }
    .loader-item-dot:nth-child(1):before { animation-delay: -1.1s; }
    .loader-item-dot:nth-child(2):before { animation-delay: -1.0s; }
    .loader-item-dot:nth-child(3):before { animation-delay: -0.9s; }
    .loader-item-dot:nth-child(4):before { animation-delay: -0.8s; }
    .loader-item-dot:nth-child(5):before { animation-delay: -0.7s; }
    .loader-item-dot:nth-child(6):before { animation-delay: -0.6s; }

    @keyframes loader-item {
        100% { transform: rotate(360deg); }
    }

    @keyframes loader-item-dot {
        80%, 100% { transform: rotate(360deg); }
    }

    @keyframes loader-item-dot-before {
        50% {
            transform: scale(0.4);
        }
        100%, 0% {
            transform: scale(1.0);
        }
    }

</style>
