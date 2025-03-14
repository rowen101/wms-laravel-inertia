<template>
    <div class="flex flex-col">
        <template v-for="(l, i) in links" :key="i">
            <Link
                v-if="!l.items"
                to="/"
                class="inline-flex items-center gap-4 p-3 px-4 text-left text-[15px]"
            >
                <Icon
                    v-if="l.icon"
                    :icon="l.icon"
                    class="h-5 w-5 text-muted-foreground"
                />
                <p class="truncate">
                    {{ l.title }}
                </p>
            </Link>

            <Disclosure as="div" class="w-full" v-slot="{ open }" v-else>
                <DisclosureButton
                    class="inline-flex w-full items-center justify-between p-3 px-4 text-left text-[15px]"
                >
                    <div class="flex items-center gap-4">
                        <Icon
                            v-if="l.icon"
                            :icon="l.icon"
                            class="h-5 w-5 text-muted-foreground"
                        />
                        <p class="truncate">
                            {{ l.title }}
                        </p>
                    </div>
                    <div>
                        <Icon
                            icon="heroicons:chevron-down"
                            :class="[open && 'rotate-180']"
                            class="h-4 w-4 text-muted-foreground transition"
                        />
                    </div>
                </DisclosureButton>
                <TransitionExpand>
                    <DisclosurePanel class="mx-6 flex flex-col border-l px-3">
                        <template v-for="(i, j) in l.items" :key="j">
                            <Link :href="i.title" class="rounded-md px-3 py-1.5 text-sm hover:bg-muted">
                                {{ i.title }}
                             </Link>

                        </template>
                    </DisclosurePanel>
                </TransitionExpand>
            </Disclosure>
        </template>
    </div>
</template>

<script setup lang="ts">
import {ref} from "vue"
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { TransitionExpand } from "@morev/vue-transitions";
import { Icon } from "@iconify/vue";
import { Link } from '@inertiajs/vue3';
const props = defineProps({
    links: {
        type: Array as () => any[], // Define the type explicitly
        required: true, // Make the prop required
    },
});
const activeLink = ref('');

const setActiveLink = (linkTitle: string) => {
  activeLink.value = linkTitle;
};
</script>

<style>
.active {
  background-color: #d1d5db; /* Change to your desired active background color */
}
</style>