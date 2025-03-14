<template>

    <div>
      <!-- Header -->
      <Header />
      <div class="container lg:grid lg:grid-cols-12 lg:gap-10">
        <!-- Sidebar -->
        <Sidebar class="sticky top-[65px] hidden h-[calc(100vh-65px)] py-5 lg:col-span-3 lg:block" />
                <slot />

      </div>
    </div>

  </template>
  <script >
    import Header from '../Components/Header.vue'
    import Sidebar from '../Components/Sidebar.vue'

    export default {
      components: {
        Header,
        Sidebar


      }
    }
</script>
