import React from 'react';
import { createRoot } from 'react-dom/client';
import FilterShopProducts from './selectors/FilterShopProducts';
import FilterSearchResults from './selectors/FilterSearchResults';

// Use WordPress React if available
const { createElement, render } = wp.element || React;

document.addEventListener('DOMContentLoaded', function () {
    const appShopFilters = document.getElementById('kset-shop-filters');
    const appShopResults = document.getElementById('kset-search-results');
    
    if (appShopFilters) {
        // Try WordPress way first, fallback to React 18+
        if (wp.element && wp.element.render) {
            wp.element.render(
                createElement(FilterShopProducts),
                appShopFilters
            );
        } else {
            const appRoot = createRoot(appShopFilters);
            appRoot.render(<FilterShopProducts />);
        }
    } if(appShopResults) {
        // Try WordPress way first, fallback to React 18+
        if (wp.element && wp.element.render) {
            wp.element.render(
                createElement(FilterSearchResults),
                appShopResults
            );
        } else {
            const appRoot = createRoot(appShopResults);
            appRoot.render(<FilterSearchResults />);
        }
    }
});