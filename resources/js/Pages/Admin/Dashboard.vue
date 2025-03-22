<template>
    <AdminLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Masalar -->
                            <div class="bg-blue-100 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-blue-800">Masalar</h3>
                                <p class="text-3xl font-bold text-blue-600">{{ stats.tables_count }}</p>
                            </div>

                            <!-- Ürünler -->
                            <div class="bg-green-100 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-green-800">Ürünler</h3>
                                <p class="text-3xl font-bold text-green-600">{{ stats.products_count }}</p>
                            </div>

                            <!-- Kategoriler -->
                            <div class="bg-yellow-100 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-yellow-800">Kategoriler</h3>
                                <p class="text-3xl font-bold text-yellow-600">{{ stats.categories_count }}</p>
                            </div>

                            <!-- Siparişler -->
                            <div class="bg-purple-100 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-purple-800">Siparişler</h3>
                                <p class="text-3xl font-bold text-purple-600">{{ stats.orders_count }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineProps({
    stats: {
        type: Object,
        default: () => ({
            tables_count: 0,
            products_count: 0,
            categories_count: 0,
            orders_count: 0
        })
    }
});
</script> 