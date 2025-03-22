<template>
    <AdminLayout title="Masa QR Kodu">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Masa QR Kodu - {{ table.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-4 flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900">{{ table.name }} - QR Kodu</h3>
                            <div>
                                <Link 
                                    :href="route('admin.tables.index')" 
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    <i class="fas fa-arrow-left mr-2"></i> Geri
                                </Link>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-gray-100 p-6 rounded-lg">
                                <div class="flex justify-center mb-4">
                                    <div class="p-1 bg-white rounded" v-html="qrCodeImage"></div>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm text-gray-500 mb-2">Masa URL'i:</p>
                                    <p class="text-sm font-medium break-all">{{ qrCodeUrl }}</p>
                                </div>
                            </div>

                            <div>
                                <div class="mb-6">
                                    <h4 class="text-lg font-medium text-gray-900 mb-2">QR Kod Kullanımı</h4>
                                    <p class="text-gray-600">QR kodu masanın üzerine yapıştırarak, müşterilerinizin cep telefonlarından kolayca tarayarak menüye erişmelerini sağlayabilirsiniz.</p>
                                </div>
                                
                                <div class="mb-6">
                                    <h4 class="text-lg font-medium text-gray-900 mb-2">QR Kodu Yazdır</h4>
                                    <button 
                                        @click="printQrCode" 
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    >
                                        <i class="fas fa-print mr-2"></i> QR Kodu Yazdır
                                    </button>
                                </div>
                                
                                <div>
                                    <h4 class="text-lg font-medium text-gray-900 mb-2">QR Kodu İndir</h4>
                                    <a 
                                        :href="route('admin.tables.qr', table.id)" 
                                        download="masa-qr-kodu.svg"
                                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    >
                                        <i class="fas fa-download mr-2"></i> QR Kodu İndir
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    table: Object,
    qrCodeImage: String,
    qrCodeUrl: String
});

const printQrCode = () => {
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Masa QR Kodu - ${props.table.name}</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        text-align: center;
                        padding: 20px;
                    }
                    .qr-container {
                        margin: 0 auto;
                        max-width: 400px;
                    }
                    .qr-code {
                        width: 100%;
                        padding: 10px;
                        border: 1px solid #ccc;
                        border-radius: 4px;
                        background: white;
                        margin-bottom: 20px;
                    }
                    .table-name {
                        font-size: 24px;
                        font-weight: bold;
                        margin-bottom: 10px;
                    }
                    .instruction {
                        font-size: 14px;
                        color: #666;
                        margin-bottom: 5px;
                    }
                    @media print {
                        @page {
                            size: 80mm 80mm; /* Termal yazıcılar için */
                            margin: 0;
                        }
                        body {
                            padding: 5mm;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="qr-container">
                    <div class="table-name">${props.table.name}</div>
                    <div class="instruction">Menüyü görmek için QR kodu tarayın</div>
                    <div class="qr-code">${props.qrCodeImage}</div>
                    <div class="instruction">QR Cafe</div>
                </div>
                <script>
                    window.onload = function() {
                        window.print();
                        setTimeout(function() { window.close(); }, 500);
                    };
                <\/script>
            </body>
        </html>
    `);
    printWindow.document.close();
};
</script> 