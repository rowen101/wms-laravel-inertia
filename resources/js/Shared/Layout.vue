<template>
  <div>
    <div id="dropdown" />
    <div class="md:flex md:flex-col">
      <div class="md:flex md:flex-col md:h-screen">
        <div class="md:flex md:shrink-0">
          <div class="flex items-center justify-between px-6 py-4 bg-indigo-900 md:shrink-0 md:justify-center md:w-56">
            <Link class="mt-1" href="/">
              <logo class="fill-white" width="120" height="28" />
            </Link>
            <dropdown class="md:hidden" placement="bottom-end">
              <template #default>
                <svg class="w-6 h-6 fill-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" /></svg>
              </template>
              <template #dropdown>
                <div class="mt-2 px-8 py-4 bg-indigo-800 rounded shadow-lg">
                  <main-menu />
                </div>
              </template>
            </dropdown>
          </div>
          <div class="md:text-md flex items-center justify-between p-4 w-full text-sm bg-white border-b md:px-12 md:py-0">
            <div class="mr-4 mt-1">{{ auth.user.account.name }}</div>
            <dropdown class="mt-1" placement="bottom-end">
              <template #default>
                <div class="group flex items-center cursor-pointer select-none">
                  <div class="mr-1 text-gray-700 group-hover:text-indigo-600 focus:text-indigo-600 whitespace-nowrap">
                    <span>{{ auth.user.first_name }}</span>
                    <span class="hidden md:inline">&nbsp;{{ auth.user.last_name }}</span>
                  </div>
                  <icon class="w-5 h-5 fill-gray-700 group-hover:fill-indigo-600 focus:fill-indigo-600" name="cheveron-down" />
                </div>
              </template>
              <template #dropdown>
                <div class="mt-2 py-2 text-sm bg-white rounded shadow-xl">
                  <Link class="block px-6 py-2 hover:text-white hover:bg-indigo-500" :href="`/users/${auth.user.id}/edit`">My Profile</Link>
                  <Link class="block px-6 py-2 hover:text-white hover:bg-indigo-500" href="/users">Manage Users</Link>
                  <Link class="block px-6 py-2 w-full text-left hover:text-white hover:bg-indigo-500" href="/logout" method="delete" as="button">Logout</Link>
                </div>
              </template>
            </dropdown>
          </div>
        </div>
        <div class="md:flex md:grow md:overflow-hidden">
          <main-menu class="hidden shrink-0 p-12 w-56 bg-indigo-800 overflow-y-auto md:block" />
          <div class="px-4 py-8 md:flex-1 md:p-12 md:overflow-y-auto" scroll-region>
            <flash-messages />
            <slot />
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="flex h-screen">
    <!-- Overlay for mobile (appears when sidebar is open) -->
    <div
      v-if="sidebarOpen"
      class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"
      @click="closeSidebar"
    ></div>

    <!-- Sidebar -->
    <aside
      :class="`bg-indigo-900 text-white w-64 space-y-6 px-4 py-7 absolute inset-y-0 left-0 transform ${
        sidebarOpen ? 'translate-x-0' : '-translate-x-full'
      } md:relative md:translate-x-0 transition-transform duration-300 ease-in-out z-40`"
    >
      <!-- Logo -->
      <a href="/" class="text-white flex items-center space-x-2 px-4">
        <svg
          class="w-8 h-8"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M12 4v16m8-8H4"
          ></path>
        </svg>
        <span class="text-2xl font-extrabold">Dashboard</span>
      </a>

      <!-- Nav Links -->

      <main-menu class="shrink-0 p-5 w-56  overflow-y-auto md:block" />
      <nav>
        <a
          href="#"
          class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-700"
        >
          Dashboard
        </a>
        <a
          href="#"
          class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-700"
        >
          Team
        </a>
        <a
          href="#"
          class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-700"
        >
          Projects
        </a>
        <a
          href="#"
          class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-700"
        >
          Calendar
        </a>
        <a
          href="#"
          class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-700"
        >
          Documents
        </a>
        <a
          href="#"
          class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-700"
        >
          Reports
        </a>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
      <!-- Top Navigation -->
      <header class="flex justify-between items-center bg-white shadow p-4">
        <!-- Hamburger Menu for Mobile -->
        <button
          @click="toggleSidebar"
          class="text-indigo-500 focus:outline-none md:hidden"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16"
            />
          </svg>
        </button>

        <!-- Search Bar -->
        <div class="flex-1 px-4">
          <input
            type="text"
            placeholder="Search"
            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-indigo-300"
          />
        </div>

        <!-- Profile -->
        <div class="flex items-center space-x-4">
          <button
            class="text-gray-500 hover:text-gray-600 focus:outline-none focus:ring focus:ring-indigo-300"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 17h5l-1.405-1.405M19 13h-4m4 0H9m6 0V7m0 0h2m0 0V5a2 2 0 10-4 0v2m6 0a2 2 0 00-2-2m0 4H9m6 0H9"
              />
            </svg>
          </button>
          <img
            src="https://via.placeholder.com/40"
            alt="profile"
            class="rounded-full w-10 h-10"
          />
        </div>
      </header>

      <!-- Content Area -->
      <main class="flex-1 bg-gray-100 p-6">
        <div class=" h-full">
          <slot />
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import Logo from '@/Shared/Logo.vue'
import Dropdown from '@/Shared/Dropdown.vue'
import MainMenu from '@/Shared/MainMenu.vue'
import FlashMessages from '@/Shared/FlashMessages.vue'

export default {
  components: {
    Dropdown,
    FlashMessages,
    Icon,
    Link,
    Logo,
    MainMenu,
  },
  props: {
    auth: Object,
  },
  data() {
    return {
      sidebarOpen: false, // Controls the visibility of the sidebar
    };
  },
  methods: {
    toggleSidebar() {
      // Toggles the sidebar visibility
      this.sidebarOpen = !this.sidebarOpen;
    },
    closeSidebar() {
      // Closes the sidebar when clicking outside
      this.sidebarOpen = false;
    },
  },
}
</script>
