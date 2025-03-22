<template>
    <div class="min-h-screen bg-gradient-to-b from-indigo-500 to-purple-600 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <h2 class="mt-6 text-4xl font-extrabold text-white">
                    QR Cafe'ye Hoş Geldiniz
                </h2>
                <p class="mt-2 text-sm text-indigo-200">
                    Sipariş vermek için lütfen masanızdaki QR kodu taratın veya masa numaranızı seçin
                </p>
            </div>
            
            <div class="mt-8 bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Kolay Sipariş İçin
                    </h3>
                    
                    <div v-if="tables.length > 0" class="grid grid-cols-3 gap-3">
                        <Link 
                            v-for="table in tables" 
                            :key="table.id" 
                            :href="route('customer.menu', table.id)"
                            class="col-span-1 flex items-center justify-center py-3 border border-gray-300 bg-white rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            <i class="fas fa-utensils mr-2"></i> {{ table.name }}
                        </Link>
                    </div>
                    
                    <div v-else class="text-center py-4 text-gray-500">
                        Masa bulunamadı. Lütfen QR kodu taratın.
                    </div>
                    
                    <div class="mt-6 border-t border-gray-200 pt-4">
                        <div class="flex items-center justify-center">
                            <div class="text-center">
                                <p class="text-sm text-gray-500">
                                    Veya telefonunuzla QR kodu taratın
                                </p>
                                <i class="fas fa-qrcode text-indigo-500 text-5xl mt-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <p class="text-xs text-indigo-200">
                    &copy; {{ new Date().getFullYear() }} QR Cafe. Tüm hakları saklıdır.
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    tables: Array
});
</script> 