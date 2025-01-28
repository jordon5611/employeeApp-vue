<template>
    <div class="overflow-x-auto">
      <table class="table-auto w-full border-collapse border border-white text-left">
        <slot></slot>
      </table>
    </div>
  </template>
  
  <script>
  export default {
    name: "Table",
  };
  </script>
  