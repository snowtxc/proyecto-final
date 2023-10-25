<template>
    <div class="static-arrow-spinner" :class="{'left': direction === 'left', 'hidden': !show}">
      <div class="arrow">
        <div class="body"></div>
        <div class="pointer"></div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: "StaticArrowSpinner",
    props: {
      direction: {
        type: String,
        default: "right",
      },
      pulse: {
        type: Boolean,
        default: false,
      },
      show: {
        type: Boolean,
        default: true,
      },
    },
  };
  </script>
  
  <style scoped>
  .static-arrow-spinner {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 50px;
  }
  
  .arrow {
    display: flex;
    align-items: center;
    width: 40px;
    height: 30px;
    position: relative;
  }
  
  .body {
    background: currentColor;
    height: 30%;
    width: 40%;
  }
  
  .pointer {
    background: currentColor;
    width: 40%;
    height: 70%;
    clip-path: polygon(0% 0%, 100% 50%, 0% 100%);
  }
  
  .arrow.left {
    flex-direction: row-reverse;
  }
  
  .pointer.left {
    clip-path: polygon(100% 0%, 0% 50%, 100% 100%);
  }
  
  .hidden {
    display: none;
  }
  
  </style>
  