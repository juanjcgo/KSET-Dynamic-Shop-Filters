# KSET Dynamic Shop Filters - API REST Documentation

## Endpoints Disponibles

### 1. Filtrar Productos
**Endpoint:** `GET|POST /wp-json/kset/v1/products/filter`

**Parámetros:**
- `category` (string): Categoría del producto (ej: "Bushings", "Seals", "Shims")
- `system_of_measurement` (string): Sistema de medición ("Metric" o "Imperial")
- `inside_diameter` (string): Diámetro interior (ej: "20mm", "0.5in")
- `outside_diameter` (string): Diámetro exterior (ej: "30mm", "1.2in")
- `length` (string): Longitud (ej: "36mm", "1.5in")
- `width` (string): Ancho (ej: "5mm", "0.2in")
- `type` (string): Tipo de producto (ej: "AA", "BB")
- `page` (int): Número de página (default: 1)
- `per_page` (int): Productos por página (default: 12, max: 100)

**Ejemplo de Respuesta:**
```json
{
  "success": true,
  "filters": {
    "category": "Bushings",
    "system_of_measurement": "Metric",
    "inside_diameter": "20mm",
    "outside_diameter": "30mm",
    "length": "36mm",
    "width": null,
    "type": "AA"
  },
  "results": [
    {
      "id": 123,
      "name": "Bushing Metric 20x30x36",
      "sku": "B30-20-36",
      "price": "25.00",
      "image": "https://example.com/wp-content/uploads/2025/11/product.jpg",
      "category": "Bushings",
      "url": "https://example.com/product/bushing-metric-20x30x36",
      "attributes": {
        "System of Measurement": "Metric",
        "Inside Diameter (ID)": "20.03mm",
        "Outside Diameter (OD)": "30.06mm",
        "Length": "36mm",
        "Width": null,
        "Type": "AA"
      }
    }
  ],
  "pagination": {
    "page": 1,
    "per_page": 12,
    "total": 45,
    "pages": 4
  },
  "timestamp": "2025-11-01 15:30:45"
}
```

### 2. Obtener Atributos Disponibles
**Endpoint:** `GET|POST /wp-json/kset/v1/products/attributes`

**Parámetros:**
- `category` (string): Categoría para filtrar atributos
- `system_of_measurement` (string): Sistema de medición para filtrar
- `inside_diameter` (string): Diámetro interior para filtrar siguientes atributos
- `outside_diameter` (string): Diámetro exterior para filtrar siguientes atributos
- `attribute` (string): Atributo específico a obtener valores

**Ejemplo de Respuesta:**
```json
{
  "success": true,
  "filters": {
    "category": "Bushings",
    "system_of_measurement": "Metric",
    "inside_diameter": null,
    "outside_diameter": null,
    "length": null,
    "width": null,
    "type": null
  },
  "attributes": {
    "system_of_measurement": [
      {"value": "Metric", "label": "Metric", "slug": "metric"},
      {"value": "Imperial", "label": "Imperial", "slug": "imperial"}
    ],
    "inside_diameter": [
      {"value": "20mm", "label": "20mm"},
      {"value": "25mm", "label": "25mm"},
      {"value": "30mm", "label": "30mm"}
    ],
    "outside_diameter": [
      {"value": "30mm", "label": "30mm"},
      {"value": "35mm", "label": "35mm"},
      {"value": "40mm", "label": "40mm"}
    ],
    "length": [
      {"value": "10mm", "label": "10mm"},
      {"value": "15mm", "label": "15mm"},
      {"value": "20mm", "label": "20mm"}
    ],
    "width": [],
    "type": [
      {"value": "AA", "label": "AA"},
      {"value": "BB", "label": "BB"}
    ]
  },
  "timestamp": "2025-11-01 15:30:45"
}
```

### 3. Obtener Categorías
**Endpoint:** `GET /wp-json/kset/v1/products/categories`

**Ejemplo de Respuesta:**
```json
{
  "success": true,
  "categories": [
    {
      "id": 15,
      "name": "Bushings",
      "slug": "bushings",
      "count": 150
    },
    {
      "id": 16,
      "name": "Seals",
      "slug": "seals",
      "count": 85
    },
    {
      "id": 17,
      "name": "Shims",
      "slug": "shims",
      "count": 120
    }
  ],
  "timestamp": "2025-11-01 15:30:45"
}
```

## Ejemplos de Uso

### 1. Obtener todas las categorías
```javascript
fetch('/wp-json/kset/v1/products/categories')
  .then(response => response.json())
  .then(data => console.log(data));
```

### 2. Filtrar productos por categoría
```javascript
fetch('/wp-json/kset/v1/products/filter?category=Bushings&page=1&per_page=12')
  .then(response => response.json())
  .then(data => console.log(data));
```

### 3. Obtener atributos disponibles para una categoría específica
```javascript
fetch('/wp-json/kset/v1/products/attributes?category=Bushings')
  .then(response => response.json())
  .then(data => console.log(data));
```

### 4. Filtrado dinámico por pasos
```javascript
// Paso 1: Seleccionar categoría
const step1 = await fetch('/wp-json/kset/v1/products/attributes?category=Bushings');

// Paso 2: Seleccionar sistema de medición
const step2 = await fetch('/wp-json/kset/v1/products/attributes?category=Bushings&system_of_measurement=Metric');

// Paso 3: Seleccionar diámetro interior
const step3 = await fetch('/wp-json/kset/v1/products/attributes?category=Bushings&system_of_measurement=Metric&inside_diameter=20mm');

// Paso 4: Obtener productos finales
const products = await fetch('/wp-json/kset/v1/products/filter?category=Bushings&system_of_measurement=Metric&inside_diameter=20mm&outside_diameter=30mm&length=36mm');
```

## Flujo de Filtrado Jerárquico

1. **Primer nivel:** Seleccionar Range (categoría)
2. **Segundo nivel:** System of Measurement (solo si la categoría tiene mediciones)
3. **Tercer nivel:** Inside Diameter (ID)
4. **Cuarto nivel:** Outside Diameter (OD)
5. **Quinto nivel:** Length o Width (dependiendo del tipo de producto)

### Jerarquía por Categoría:
- **Bushings:** System → Inside Diameter → Outside Diameter → Length → Type
- **Seals:** System → Inside Diameter → Outside Diameter → Width → Type
- **Shims:** System → Inside Diameter → Outside Diameter → Width → Type

## Atributos del Producto

### Atributos Globales (Taxonomías)
- `pa_system-of-measurement`: Sistema de medición (Metric/Imperial)

### Atributos Personalizados (Meta Fields)
- `_inside_diameter`: Diámetro interior
- `_outside_diameter`: Diámetro exterior
- `_length`: Longitud
- `_width`: Ancho
- `_type`: Tipo de producto

## Manejo de Errores

### Errores Comunes:
```json
{
  "success": false,
  "error": {
    "code": "woocommerce_inactive",
    "message": "WooCommerce is not active"
  },
  "timestamp": "2025-11-01 15:30:45"
}
```

```json
{
  "success": false,
  "error": {
    "code": "invalid_measurement_system",
    "message": "Invalid system of measurement. Must be Metric or Imperial."
  },
  "timestamp": "2025-11-01 15:30:45"
}
```

## Notas de Implementación

1. **Compatibilidad:** WordPress 6.7+ y WooCommerce 9+
2. **Seguridad:** Todos los parámetros son sanitizados con `sanitize_text_field()`
3. **Paginación:** Soporta paginación estándar con `page` y `per_page`
4. **Cache:** Considera implementar cache para consultas frecuentes
5. **Performance:** Las consultas están optimizadas para grandes catálogos de productos