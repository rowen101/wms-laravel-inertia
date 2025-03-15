
<script setup>
import StatusCard from "@/Components/StatusCard.vue"
import ProfileInfo from '@/Components/ProfileInfo.vue'
import Reminders from '@/Components/Reminders.vue'

import NewCustomers from '@/Components/NewCustomers.vue'
import RecentOrders from '@/Components/RecentOrders.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>


<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <main class="py-5 lg:col-span-5">

        <StatusCard/>
        <NewCustomers/>
        <RecentOrders/>
    </main>
    <div class="hidden py-5 lg:col-span-3 lg:block">

            <ProfileInfo />
            <hr class="my-5" />

            <Reminders />
          </div>
    </AuthenticatedLayout>

</template>


