<template>
    <header class="sticky top-0 z-20 border-b bg-background/80 backdrop-blur">
    <!-- Container of flex -->
    <div class="container flex h-16 items-center justify-between">
      <!-- Logo and page title -->
      <div class="flex items-center gap-3">
        <button
          @click="isOpen = true"
          aria-label="Open menu"
          class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border bg-background lg:hidden"
        >
          <span class="sr-only">Button used to open menu</span>
          <Icon icon="mdi-light:home" />
        </button>
        <!-- Logo -->
        <img
          src="https://img.logoipsum.com/296.svg"
          alt="Analytics logo"
          class="h-7 w-7 object-contain"
        />
        <!-- Page title -->
        <NavLink class="text-xl font-bold" to="/dashbarod">WMS </NavLink>
      </div>

      <!-- Right side of header -->
      <div class="flex items-center gap-5">
        <button
          @click="toggleTheme"
          class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border bg-background"
        >
          <Icon icon="heroicons:sun" class="h-5 w-5" />

        </button>

        <!-- Profile Dropdown menu -->
        <Menu as="div" class="relative">
          <MenuButton
            class="flex h-9 w-9 items-center justify-center overflow-hidden rounded-full border bg-background"
          >
            <img
              src="https://randomuser.me/api/portraits/med/men/75.jpg"
              alt="Logged in user"
              class="h-full w-full"
            />
          </MenuButton>
          <TransitionScale :scale="0.8" origin="top right">
            <MenuItems
              class="absolute right-0 z-10 mt-3 w-48 rounded-md border bg-background focus:outline-none focus-visible:ring-2 focus-visible:ring-ring"
            >
              <div class="border-b px-3 py-1.5 text-sm">
                <p class="font-semibold">Hello  {{ $page.props.auth.user.name }}</p>

              </div>
              <div class="p-1">
                <template v-for="(p, i) in profileMenuOptions">
                  <MenuItem v-if="!p.divider" :key="i" v-slot="{ active }">
                    <button
                      :class="[active && 'bg-muted']"
                      class="inline-flex w-full items-center rounded-md p-2 text-sm font-medium"
                      @click="handleMenuItemClick(p)"
                      >
                      {{ p.title }}
                    </button>
                  </MenuItem>
                  <hr v-if="p.divider" :key="i" class="my-1" />
                </template>
              </div>
            </MenuItems>
          </TransitionScale>
        </Menu>
      </div>
    </div>
    <!-- Mobile menu -->
    <MobileMenu v-model="isOpen" />
  </header>
</template>

  <script setup lang="ts">
  import { ref } from "vue";
import MobileMenu from "./MobileMenu.vue"
  import { useColorMode } from '@vueuse/core'
  import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
  import { TransitionScale} from '@morev/vue-transitions';
  import { Icon } from '@iconify/vue';

  const mode = useColorMode();
  const toggleTheme = () => {
    mode.value = mode.value === "dark" ? "light" : "dark";
  };

  // Items that will be displayed in menu
  const profileMenuOptions = [
    { title: "Profile" },
    { title: "Billing" },
    { title: "Settings" },
    { title: "Team members" },
    { title: "Sales" },
    { divider: true },
    { title: "Logout" },
  ];
  const handleMenuItemClick = (item: { title: string }) => {
  if (item.title === "Logout") {


  } else if (item.title === "Profile") {
    // Handle profile navigation or action

  }
}
  // Used to open/close menu
  const isOpen = ref(false);

  </script>
function useColorMode() {
    throw new Error("Function not implemented.");
  }
