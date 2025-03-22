<template>
    <CustomerLayout :table="table">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Sipariş Geçmişim
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="orders.length === 0" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-8 text-center">
                        <i class="fas fa-history text-gray-400 text-5xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-700">Henüz bir siparişiniz bulunmuyor</h3>
                        <p class="mt-2 text-gray-500">Menüden sipariş vererek listenizi oluşturabilirsiniz.</p>
                        <Link 
                            :href="route('customer.menu', table.id)" 
                            class="mt-6 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            <i class="fas fa-utensils mr-2"></i> Menüye Git
                        </Link>
                    </div>
                </div>

                <div v-else>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-4 border-b">
                            <h3 class="text-lg font-medium text-gray-800">Siparişlerim</h3>
                        </div>
                        <div class="divide-y">
                            <div v-for="order in orders" :key="order.id" class="p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <h4 class="text-base font-medium text-gray-900">Sipariş #{{ order.id }}</h4>
                                            <span 
                                                class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                :class="{
                                                    'bg-yellow-100 text-yellow-800': order.status === 'pending',
                                                    'bg-blue-100 text-blue-800': order.status === 'processing',
                                                    'bg-green-100 text-green-800': order.status === 'completed',
                                                    'bg-red-100 text-red-800': order.status === 'cancelled'
                                                }"
                                            >
                                                {{ orderStatusText(order) }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ formatDate(order.created_at) }}
                                        </p>
                                        <p class="text-sm font-semibold text-gray-700 mt-2">
                                            Toplam: {{ formatPrice(order.total_amount) }} TL
                                        </p>
                                    </div>
                                    <Link 
                                        :href="route('customer.order.status', { table_id: table.id, order_id: order.id })" 
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    >
                                        <i class="fas fa-eye mr-1"></i> Detaylar
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <Link 
                            :href="route('customer.menu', table.id)" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            <i class="fas fa-utensils mr-2"></i> Menüye Dön
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

const props = defineProps({
    table: Object,
    orders: Array,
});

const orderStatusText = (order) => {
    switch (order.status) {
        case 'pending':
            return 'Beklemede';
        case 'processing':
            return 'Hazırlanıyor';
        case 'completed':
            return 'Tamamlandı';
        case 'cancelled':
            return 'İptal Edildi';
        default:
            return order.status;
    }
};

const formatPrice = (price) => {
    return Number(price).toFixed(2);
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleString('tr-TR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script> 