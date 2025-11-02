/**
 * KSET Dynamic Shop Filters - API Test Examples
 * 
 * Este archivo contiene ejemplos de cómo usar la API REST del plugin
 * desde JavaScript/React para implementar filtros dinámicos.
 */

class KSETFilterAPI {
    constructor(baseURL = '') {
        this.baseURL = baseURL || window.location.origin;
        this.apiBase = '/wp-json/kset/v1';
    }

    /**
     * Realizar petición HTTP a la API
     */
    async makeRequest(endpoint, params = {}) {
        try {
            const url = new URL(`${this.baseURL}${this.apiBase}${endpoint}`);
            
            // Añadir parámetros a la URL
            Object.keys(params).forEach(key => {
                if (params[key] !== null && params[key] !== undefined && params[key] !== '') {
                    url.searchParams.append(key, params[key]);
                }
            });

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.error?.message || 'API request failed');
            }

            return data;
        } catch (error) {
            console.error('API Request Error:', error);
            throw error;
        }
    }

    /**
     * Obtener todas las categorías de productos
     */
    async getCategories() {
        return this.makeRequest('/products/categories');
    }

    /**
     * Obtener atributos disponibles basados en filtros actuales
     */
    async getAttributes(filters = {}) {
        return this.makeRequest('/products/attributes', filters);
    }

    /**
     * Filtrar productos
     */
    async filterProducts(filters = {}, page = 1, perPage = 12) {
        const params = {
            ...filters,
            page,
            per_page: perPage,
        };
        return this.makeRequest('/products/filter', params);
    }

    /**
     * Obtener valores de un atributo específico
     */
    async getAttributeValues(attribute, filters = {}) {
        const params = {
            ...filters,
            attribute,
        };
        return this.makeRequest('/products/attributes', params);
    }
}

/**
 * Clase para manejar filtros dinámicos paso a paso
 */
class DynamicFilterManager {
    constructor() {
        this.api = new KSETFilterAPI();
        this.currentFilters = {};
        this.callbacks = {};
    }

    /**
     * Registrar callback para cuando cambien los filtros
     */
    onFilterChange(callback) {
        this.callbacks.filterChange = callback;
    }

    /**
     * Registrar callback para cuando cambien los productos
     */
    onProductsChange(callback) {
        this.callbacks.productsChange = callback;
    }

    /**
     * Registrar callback para cuando cambien los atributos disponibles
     */
    onAttributesChange(callback) {
        this.callbacks.attributesChange = callback;
    }

    /**
     * Establecer un filtro y actualizar dependencias
     */
    async setFilter(filterName, value) {
        // Actualizar filtro actual
        this.currentFilters[filterName] = value;

        // Limpiar filtros dependientes según jerarquía
        this.clearDependentFilters(filterName);

        // Notificar cambio de filtros
        if (this.callbacks.filterChange) {
            this.callbacks.filterChange(this.currentFilters);
        }

        // Actualizar atributos disponibles
        await this.updateAvailableAttributes();

        // Actualizar productos si hay filtros suficientes
        if (this.shouldUpdateProducts()) {
            await this.updateProducts();
        }
    }

    /**
     * Limpiar filtros dependientes según jerarquía
     */
    clearDependentFilters(changedFilter) {
        const hierarchy = this.getFilterHierarchy();
        const changedIndex = hierarchy.indexOf(changedFilter);
        
        if (changedIndex >= 0) {
            // Limpiar todos los filtros posteriores en la jerarquía
            for (let i = changedIndex + 1; i < hierarchy.length; i++) {
                delete this.currentFilters[hierarchy[i]];
            }
        }
    }

    /**
     * Obtener jerarquía de filtros según categoría
     */
    getFilterHierarchy() {
        const category = this.currentFilters.category?.toLowerCase();
        
        const hierarchies = {
            'bushings': ['category', 'system_of_measurement', 'inside_diameter', 'outside_diameter', 'length', 'type'],
            'seals': ['category', 'system_of_measurement', 'inside_diameter', 'outside_diameter', 'width', 'type'],
            'shims': ['category', 'system_of_measurement', 'inside_diameter', 'outside_diameter', 'width', 'type'],
        };

        return hierarchies[category] || ['category', 'system_of_measurement', 'inside_diameter', 'outside_diameter', 'length', 'width', 'type'];
    }

    /**
     * Actualizar atributos disponibles
     */
    async updateAvailableAttributes() {
        try {
            const response = await this.api.getAttributes(this.currentFilters);
            
            if (this.callbacks.attributesChange) {
                this.callbacks.attributesChange(response.attributes);
            }
        } catch (error) {
            console.error('Error updating attributes:', error);
        }
    }

    /**
     * Verificar si debe actualizar productos
     */
    shouldUpdateProducts() {
        // Actualizar productos si al menos hay una categoría seleccionada
        return this.currentFilters.category;
    }

    /**
     * Actualizar productos
     */
    async updateProducts(page = 1, perPage = 12) {
        try {
            const response = await this.api.filterProducts(this.currentFilters, page, perPage);
            
            if (this.callbacks.productsChange) {
                this.callbacks.productsChange(response.results, response.pagination);
            }
        } catch (error) {
            console.error('Error updating products:', error);
        }
    }

    /**
     * Limpiar todos los filtros
     */
    clearAllFilters() {
        this.currentFilters = {};
        
        if (this.callbacks.filterChange) {
            this.callbacks.filterChange(this.currentFilters);
        }
        
        if (this.callbacks.productsChange) {
            this.callbacks.productsChange([], { total: 0, pages: 0 });
        }
    }

    /**
     * Obtener filtros actuales
     */
    getCurrentFilters() {
        return { ...this.currentFilters };
    }
}

/**
 * Ejemplos de uso
 */

// Ejemplo 1: Uso básico de la API
async function basicAPIExample() {
    const api = new KSETFilterAPI();

    try {
        // Obtener categorías
        const categories = await api.getCategories();
        console.log('Categorías:', categories.categories);

        // Filtrar productos por categoría
        const products = await api.filterProducts({ category: 'Bushings' });
        console.log('Productos de Bushings:', products.results);

        // Obtener atributos para una categoría específica
        const attributes = await api.getAttributes({ category: 'Bushings' });
        console.log('Atributos disponibles:', attributes.attributes);

    } catch (error) {
        console.error('Error en ejemplo básico:', error);
    }
}

// Ejemplo 2: Filtrado dinámico paso a paso
async function dynamicFilterExample() {
    const filterManager = new DynamicFilterManager();

    // Configurar callbacks
    filterManager.onFilterChange((filters) => {
        console.log('Filtros actualizados:', filters);
        // Aquí actualizarías la UI con los filtros actuales
    });

    filterManager.onAttributesChange((attributes) => {
        console.log('Atributos disponibles:', attributes);
        // Aquí actualizarías los selectores de filtros en la UI
    });

    filterManager.onProductsChange((products, pagination) => {
        console.log('Productos:', products);
        console.log('Paginación:', pagination);
        // Aquí actualizarías la lista de productos en la UI
    });

    // Simular selección de filtros paso a paso
    try {
        // Paso 1: Seleccionar categoría
        await filterManager.setFilter('category', 'Bushings');

        // Paso 2: Seleccionar sistema de medición
        await filterManager.setFilter('system_of_measurement', 'Metric');

        // Paso 3: Seleccionar diámetro interior
        await filterManager.setFilter('inside_diameter', '20mm');

        // Paso 4: Seleccionar diámetro exterior
        await filterManager.setFilter('outside_diameter', '30mm');

        // Paso 5: Seleccionar longitud
        await filterManager.setFilter('length', '36mm');

    } catch (error) {
        console.error('Error en filtrado dinámico:', error);
    }
}

// Ejemplo 3: Integración con React (pseudocódigo)
function ReactFilterComponent() {
    const [filterManager] = useState(() => new DynamicFilterManager());
    const [categories, setCategories] = useState([]);
    const [attributes, setAttributes] = useState({});
    const [products, setProducts] = useState([]);
    const [pagination, setPagination] = useState({});
    const [currentFilters, setCurrentFilters] = useState({});

    useEffect(() => {
        // Configurar callbacks
        filterManager.onFilterChange(setCurrentFilters);
        filterManager.onAttributesChange(setAttributes);
        filterManager.onProductsChange((products, pagination) => {
            setProducts(products);
            setPagination(pagination);
        });

        // Cargar categorías iniciales
        loadCategories();
    }, []);

    const loadCategories = async () => {
        try {
            const response = await filterManager.api.getCategories();
            setCategories(response.categories);
        } catch (error) {
            console.error('Error loading categories:', error);
        }
    };

    const handleFilterChange = async (filterName, value) => {
        await filterManager.setFilter(filterName, value);
    };

    // JSX del componente...
}

// Ejemplo 4: Búsqueda con autocompletado
async function searchWithAutocomplete(searchTerm, filters = {}) {
    const api = new KSETFilterAPI();
    
    try {
        // Filtrar productos que coincidan con el término de búsqueda
        const searchFilters = {
            ...filters,
            search: searchTerm, // Nota: necesitarías añadir soporte para búsqueda en la API
        };
        
        const results = await api.filterProducts(searchFilters, 1, 5);
        return results.results.map(product => ({
            id: product.id,
            name: product.name,
            sku: product.sku,
            category: product.category,
        }));
    } catch (error) {
        console.error('Error en búsqueda:', error);
        return [];
    }
}

// Exportar para uso en módulos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        KSETFilterAPI,
        DynamicFilterManager,
        basicAPIExample,
        dynamicFilterExample,
        searchWithAutocomplete,
    };
}