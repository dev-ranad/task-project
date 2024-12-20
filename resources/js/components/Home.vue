<template>
    <v-app>
        <v-app-bar color="primary header">
            <div class="d-flex justify-space-between align-items-center">
                <div class="d-flex align-items-center">
                    <v-img
                        src="https://png.pngtree.com/png-vector/20221122/ourmid/pngtree-dp-letter-logo-icon-illustration-vector-alphabet-art-initial-vector-png-image_34736094.png"
                        width="75"></v-img>
                </div>
            </div>

        </v-app-bar>
        <v-card class="layout-wrapper">
            <v-layout class="sidebar">
                <v-navigation-drawer width="85" class="custom-nav">
                    <v-list dense nav>
                        <v-list-item-group active-class="active-item">
                            <v-list-item>
                                <v-layout column justify="center">
                                    <v-icon small-medium>mdi-layers-triple-outline</v-icon>
                                    <span class="list-item-title">Dispatch</span>
                                </v-layout>
                            </v-list-item>

                            <v-list-item>
                                <v-layout column justify="center">
                                    <v-icon small-medium>mdi-cube-outline</v-icon>
                                    <span class="list-item-title">Batches</span>
                                </v-layout>
                            </v-list-item>

                            <v-list-item>
                                <v-layout column justify="center">
                                    <v-icon small-medium>mdi-monitor</v-icon>
                                    <span class="list-item-title">Kiosk</span>
                                </v-layout>
                            </v-list-item>

                            <v-list-item>
                                <v-layout column justify="center">
                                    <v-icon small-medium>mdi-selection-ellipse</v-icon>
                                    <span class="list-item-title">Tracking</span>
                                </v-layout>
                            </v-list-item>

                            <v-list-item value="products" @click="changeTab('products')">
                                <v-layout column justify="center">
                                    <v-icon small-medium>mdi-package-variant-closed</v-icon>
                                    <span class="list-item-title">Products</span>
                                </v-layout>

                            </v-list-item>

                            <v-list-item>
                                <v-layout column justify="center">
                                    <v-icon small-medium>mdi-currency-usd</v-icon>
                                    <span class="list-item-title">Rate Checker</span>
                                </v-layout>
                            </v-list-item>

                            <v-list-item>
                                <v-layout column justify="center">
                                    <v-icon small-medium>mdi-cog-outline</v-icon>
                                    <span class="list-item-title">Settings</span>
                                </v-layout>
                            </v-list-item>
                            <v-list-item>
                                <v-layout column justify="center">
                                    <v-icon small-medium>mdi-swap-horizontal-circle</v-icon>
                                    <span class="list-item-title">Stock Transfer</span>
                                </v-layout>
                            </v-list-item>

                            <v-divider></v-divider>

                            <v-list-item link>
                                <v-layout column justify="center">
                                    <v-icon>mdi-checkbox-multiple-blank-outline</v-icon>
                                </v-layout>
                            </v-list-item>
                            <v-list-item link>
                                <v-layout column justify="center">
                                    <v-icon>mdi-help-circle-outline</v-icon>
                                </v-layout>
                            </v-list-item>
                            <v-list-item class="px-2">
                                <v-layout column justify="center">
                                    <v-img src="https://randomuser.me/api/portraits/women/85.jpg" max-height="150"
                                        max-width="250" class="rounded-circle"></v-img>
                                </v-layout>
                            </v-list-item>
                        </v-list-item-group>
                    </v-list>
                </v-navigation-drawer>
            </v-layout>
            <v-layout class="main-content">
                <ProductList v-if="selectedTab === 'products'" />
            </v-layout>
        </v-card>
    </v-app>
</template>


<script>
import ProductList from "@/components/ProductList.vue";
export default {
    name: 'Home',
    components: {
        ProductList,
    },
    data() {
        return {
            selectedTab: '',
            unshippedCount: 112,
            inBatchCount: 112,
            unbatchedCount: 112,
            shipTodayCount: 112,
        };
    },
    methods: {
        changeTab(tab) {
            this.selectedTab = tab;
        },
    },
};

</script>

<style scoped>
.header {
    max-height: 75px;
}

.layout-wrapper {
    display: flex;
    height: 100vh;
}

.sidebar {
    display: flex;
    flex-direction: column;
    flex: none;
}

.main-content {
    background-color: #ffffff;
    overflow-y: scroll;
    padding: 30px;
    scrollbar-width: none;
    -ms-overflow-style: none;
}

.main-content::-webkit-scrollbar {
    display: none;
}

.custom-nav {
    background-color: #edf9ff;
    border-right: 1px solid #edf9ff;
}

.v-list-item {
    padding: 10px;
}

.v-list-item-icon {
    color: #696a6a;
}

.list-item-title {
    margin-top: 8px;
    font-size: 10px;
    color: #3c3c3c;
    text-align: center;
}

.active-item {
    background-color: #e3f2fd;
    color: #1565c0;
    border-left: 4px solid #1565c0;
}

.active-item .v-icon {
    color: #1565c0;
}
</style>
