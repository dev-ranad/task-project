<template>
    <v-app>
        <v-card elevation="2" class="px-5 mb-5">
            <template class="m-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <input @input="fetchProducts" class="form-control me-5" type="text" v-model="params.keyword"
                            placeholder="Enter Search Text" />
                        <v-switch inset>Test</v-switch>
                    </div>
                    <div>
                        <v-btn depressed color="secondary">
                            <v-icon class="me-1">mdi mdi-tray-arrow-down</v-icon>
                            Download
                        </v-btn>
                    </div>
                </div>
            </template>
        </v-card>
        <template>
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div class="d-flex align-items-center">
                    <v-select @change="fetchProducts" item-color="black" item-text="black" v-model="params.sortBy"
                        :items="keys" menu-props="auto" label="Sort by" class="me-2" hide-details
                        prepend-icon="mdi-sort-ascending" single-line></v-select>
                    <v-btn color="white">
                        <v-icon class="me-1">mdi mdi-trackpad</v-icon>
                        Manage Table
                    </v-btn>
                </div>
                <div>
                    Showing {{ meta.from }} - {{ meta.to }} of {{ meta.total }} entries
                </div>
            </div>
        </template>
        <template>
            <div class="table-area">
                <v-simple-table class="border">
                    <template v-slot:default>
                        <thead>
                            <tr>
                                <th v-for="item in headers" :key="item.id" class="text-left th-bg">
                                    {{ item.text }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in products" :key="item.id">
                                <td>
                                    <v-checkbox v-model="selected" />
                                </td>
                                <td>{{ item.name }}</td>
                                <td>{{ item.sku }}</td>
                                <td>{{ item.amazon }}</td>
                                <td>{{ item.ebay }}</td>
                                <td>{{ item.product_type }}</td>
                                <td>{{ item.stock_location }}</td>
                                <td>{{ item.quantity }}</td>
                                <td>{{ item.price }}</td>
                                <td>{{ item.ean }}</td>
                            </tr>
                        </tbody>
                    </template>
                </v-simple-table>
                <template>
                    <div class="text-center my-5">
                        <v-pagination v-model="params.page" :length="totalPages"
                            @input="fetchProducts"></v-pagination>
                    </div>
                </template>
            </div>
        </template>
        <template>
            <div class="d-flex justify-content-between align-items-center footer">
                <div class="d-flex align-items-center">
                    <p>Last Build : 20/12/24</p>
                </div>
                <div>
                    <p>Copyright © {{ new Date().getFullYear() }} Parker Products Ltd. All Rights Reserved.</p>
                </div>
            </div>
        </template>
    </v-app>
</template>

<script>
import axios from 'axios';

export default {
    name: 'ProductList',
    data() {
        return {
            selected: [],
            headers: [
                {
                    text: '',
                    align: 'start',
                    sortable: false,
                    value: 'checkbox',
                },
                {
                    text: 'Title',
                    align: 'start',
                    sortable: false,
                    value: 'name',
                },
                { text: 'SKU', value: 'sku' },
                { text: 'Amazon', value: 'amazon' },
                { text: 'Ebay', value: 'ebay' },
                { text: 'Product Type', value: 'product_type' },
                { text: 'Stock Location', value: 'stock_location' },
                { text: 'Quantity', value: 'quantity' },
                { text: 'Price', value: 'price' },
                { text: 'Ean', value: 'ean' },
            ],
            keys: [
                'Newest',
                'Oldest',
                'A to Z',
                'Z to A',
                'Last Updated',
            ],
            products: [],
            search: '',
            params: {
                keyword: '',
                child: true,
                parent: true,
                allData: true,
                sortBy: 'Newest',
                page: 1,
                perPage: 10
            },
            meta: '',
        };
    },
    computed: {
        totalPages() {
            return Math.ceil(this.meta.total / this.params.perPage) || 1;
        },
    },
    mounted() {
        this.fetchProducts();
    },
    methods: {
        fetchProducts() {
            axios
                .get("/api/inventory/product", { params: this.params })
                .then((response) => {
                    this.products = response.data.data.results.product;
                    this.meta = response.data.data.meta;

                    if (this.params.page > this.totalPages) {
                        this.params.page = this.totalPages;
                    }
                })
                .catch((error) => {
                    console.error("Error fetching products:", error);
                });
        },
    },
    watch: {
        "params.page"(newPage) {
            this.fetchProducts();
        },
    },

}
</script>

<style>
.product-wrapper {
    width: 100%;
    display: flex;
    float: left;
}

.footer {
    padding: 10px;
    /* position: fixed; */
    bottom: 0;
    width: 100%;
    border-top: 1px solid #ccc;
}

.table-area {
    border: 1px solid rgba(0, 0, 0, .12);
    border-radius: 5px;
    height: 85vh;
    position: relative
}


.th-bg {
    background-color: #e7f0ff;
}

.layout {
    display: block !important;
}

.layout.column {
    text-align: center;
}

.theme--light.v-card {
    background-color: #fff;
    color: rgba(0, 0, 0, .87);
    box-shadow: none !important;
    border: 1px solid #d8d8d8;
    border-radius: 3px;
}
</style>
