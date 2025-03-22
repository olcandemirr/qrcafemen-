<template>
    <CustomerLayout :table="table">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Sipariş Durumu
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-4 border-b">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-800">Sipariş #{{ order.id }}</h3>
                            <div>
                                <span 
                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                                    :class="{
                                        'bg-yellow-100 text-yellow-800': order.status === 'pending',
                                        'bg-blue-100 text-blue-800': order.status === 'processing',
                                        'bg-green-100 text-green-800': order.status === 'completed',
                                        'bg-red-100 text-red-800': order.status === 'cancelled'
                                    }"
                                >
                                    {{ orderStatusText }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="mb-6">
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div 
                                    class="h-2.5 rounded-full transition-all duration-500"
                                    :class="{
                                        'w-1/4 bg-yellow-500': order.status === 'pending',
                                        'w-2/4 bg-blue-500': order.status === 'processing',
                                        'w-full bg-green-500': order.status === 'completed',
                                        'bg-red-500': order.status === 'cancelled'
                                    }"
                                ></div>
                            </div>
                            <div class="mt-4 grid grid-cols-4 text-center text-xs">
                                <div class="text-green-600 font-semibold">
                                    <i class="fas fa-check-circle text-lg"></i>
                                    <p class="mt-1">Sipariş Alındı</p>
                                </div>
                                <div :class="{'text-green-600 font-semibold': order.status !== 'pending', 'text-gray-400': order.status === 'pending'}">
                                    <i class="fas fa-utensils text-lg"></i>
                                    <p class="mt-1">Hazırlanıyor</p>
                                </div>
                                <div :class="{'text-green-600 font-semibold': order.status === 'completed', 'text-gray-400': order.status !== 'completed'}">
                                    <i class="fas fa-check-double text-lg"></i>
                                    <p class="mt-1">Tamamlandı</p>
                                </div>
                                <div class="text-gray-400">
                                    <i class="fas fa-star text-lg"></i>
                                    <p class="mt-1">Değerlendirme</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h4 class="text-base font-semibold text-gray-700 mb-3">Sipariş Detayları</h4>
                            <div class="border rounded-md overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Ürün
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Miktar
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Fiyat
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Toplam
                                            </th>
                                            <th v-if="order.status === 'completed'" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Değerlendirme
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="item in order.products" :key="item.id">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ item.name }}</div>
                                                <div v-if="item.pivot.notes" class="text-xs text-gray-500">
                                                    <span class="font-semibold">Not:</span> {{ item.pivot.notes }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ item.pivot.quantity }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ formatPrice(item.pivot.price) }} TL
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ formatPrice(item.pivot.price * item.pivot.quantity) }} TL
                                            </td>
                                            <td v-if="order.status === 'completed'" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <Link 
                                                    :href="route('customer.product.rate', { table_id: table.id, order_id: order.id, product_id: item.id })"
                                                    class="text-indigo-600 hover:text-indigo-900"
                                                >
                                                    <i class="fas fa-star mr-1"></i> Değerlendir
                                                </Link>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right" colspan="3">
                                                Toplam:
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-base font-bold text-gray-900">
                                                {{ formatPrice(order.total_amount) }} TL
                                            </td>
                                            <td v-if="order.status === 'completed'"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div v-if="order.notes" class="mb-6">
                            <h4 class="text-base font-semibold text-gray-700 mb-2">Sipariş Notu</h4>
                            <div class="bg-gray-50 p-3 rounded-md text-sm text-gray-700">
                                {{ order.notes }}
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <Link 
                                :href="route('customer.menu', table.id)" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                <i class="fas fa-utensils mr-2"></i> Menüye Dön
                            </Link>
                            <Link 
                                :href="route('customer.order.history', table.id)" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                <i class="fas fa-history mr-2"></i> Tüm Siparişlerim
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

const props = defineProps({
    table: Object,
    order: Object,
});

const orderStatusText = computed(() => {
    switch (props.order.status) {
        case 'pending':
            return 'Beklemede';
        case 'processing':
            return 'Hazırlanıyor';
        case 'completed':
            return 'Tamamlandı';
        case 'cancelled':
            return 'İptal Edildi';
        default:
            return props.order.status;
    }
});

const formatPrice = (price) => {
    return Number(price).toFixed(2);
};
</script> 