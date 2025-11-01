# 🛍️ KSET Dynamic Shop Filters

[![WordPress](https://img.shields.io/badge/WordPress-5.0+-blue.svg)](https://wordpress.org)
[![WooCommerce](https://img.shields.io/badge/WooCommerce-5.0+-purple.svg)](https://woocommerce.com)
[![PHP](https://img.shields.io/badge/PHP-7.4+-777BB4.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-GPL%20v2-green.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![React](https://img.shields.io/badge/React-19.1+-61DAFB.svg)](https://reactjs.org)

A dynamic filtering system for WooCommerce shops with real-time AJAX filtering by categories, attributes, price range, and more. Features a modern React-based frontend interface with smooth animations and responsive design.

---

## ✨ Features

- 🔍 **Real-time AJAX Filtering** - Instant results without page reloads
- 🎨 **Modern React Frontend** - Smooth animations and responsive design
- 📱 **Mobile Optimized** - Perfect experience on all devices
- 🏷️ **Multiple Filter Types**:
  - Categories
  - Product attributes
  - Price range
  - Tags
  - Custom taxonomies
- ⚡ **High Performance** - Optimized database queries
- 🌐 **Translation Ready** - Full i18n support
- 🎛️ **Admin Dashboard** - Easy configuration and management
- 🔧 **MVC Architecture** - Clean, maintainable code structure

---

## 📋 Requirements

| Component | Version |
|-----------|---------|
| WordPress | 5.0+ |
| WooCommerce | 5.0+ |
| PHP | 7.4+ |
| Node.js | 16+ (for development) |

---

## 🚀 Installation

### From WordPress Admin

1. Download the plugin ZIP file
2. Go to **Plugins > Add New > Upload Plugin**
3. Upload the ZIP file and activate
4. Configure settings in **WooCommerce > Shop Filters**

### Manual Installation

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate through the **Plugins** menu in WordPress
3. Configure settings in **WooCommerce > Shop Filters**

### Development Setup

```bash
# Clone the repository
git clone https://github.com/yourusername/kset-dynamic-shop-filters.git
cd kset-dynamic-shop-filters

# Install dependencies
npm install

# Build for production
npm run build

# Start development with watch mode
npm run start
```

---

## 🛠️ Development

### Project Structure

```
kset-dynamic-shop-filters/
├── 📄 kset-dynamic-shop-filters.php    # Main plugin file
├── 📄 README.md                        # This file
├── 📁 includes/                        # PHP Backend
│   ├── 📄 class-init.php              # Main initialization
│   ├── 📁 controllers/                # MVC Controllers
│   ├── 📁 models/                     # Data models
│   ├── 📁 routes/                     # API routes
│   ├── 📁 helpers/                    # Utility functions
│   ├── 📁 admin/                      # Admin interface
│   └── 📁 database/                   # Database schema
├── 📁 assets/                         # Static assets
│   ├── 📁 css/                       # Stylesheets
│   ├── 📁 js/dist/                   # Compiled JavaScript
│   └── 📁 img/                       # Images
├── 📁 src/                           # React source code
│   ├── 📁 frontend/                  # Shop frontend
│   └── 📁 backend/                   # Admin interface
└── 📁 languages/                     # Translation files
```

### Build Scripts

```bash
# Production build
npm run build

# Development with file watching
npm run start

# Run tests (when implemented)
npm test
```

---

## 🎨 Usage

### Basic Setup

1. **Activate the Plugin**: Ensure WooCommerce is installed and active
2. **Configure Filters**: Go to WooCommerce > Shop Filters
3. **Add to Shop Page**: Use shortcode `[kset_shop_filters]` or enable auto-display
4. **Customize Appearance**: Modify styles in the admin panel

### Shortcodes

```php
// Display filters on any page
[kset_shop_filters]

// Display with specific attributes
[kset_shop_filters categories="true" price="true" attributes="color,size"]
```

### PHP Integration

```php
// Display filters programmatically
if (function_exists('kset_dsf_display_filters')) {
    kset_dsf_display_filters([
        'categories' => true,
        'price_range' => true,
        'attributes' => ['color', 'size']
    ]);
}
```

---

## 🔧 Configuration

### Admin Settings

Navigate to **WooCommerce > Shop Filters** to configure:

- ✅ **Filter Types**: Enable/disable specific filter types
- 🎨 **Appearance**: Colors, animations, layout options
- ⚡ **Performance**: Caching and optimization settings
- 📱 **Responsive**: Mobile-specific configurations

### Filter Options

| Option | Description | Default |
|--------|-------------|---------|
| Categories | Show product categories | ✅ Enabled |
| Price Range | Price slider filter | ✅ Enabled |
| Attributes | Product attributes (color, size, etc.) | ✅ Enabled |
| Tags | Product tags | ❌ Disabled |
| Stock Status | In stock / Out of stock | ❌ Disabled |

---

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. **Fork** the repository
2. **Create** a feature branch (`git checkout -b feature/amazing-feature`)
3. **Commit** your changes (`git commit -m 'Add amazing feature'`)
4. **Push** to the branch (`git push origin feature/amazing-feature`)
5. **Open** a Pull Request

### Development Guidelines

- Follow WordPress coding standards
- Write PHPDoc comments for all functions
- Test on multiple PHP versions (7.4, 8.0, 8.1, 8.2)
- Ensure responsive design
- Add unit tests for new features

---

## 📝 Changelog

### Version 1.0.0 (2025-11-01)

- 🎉 **Initial Release**
- ✨ Real-time AJAX filtering
- 🎨 React-based frontend interface
- 🏗️ MVC architecture implementation
- 🌐 Translation ready
- 📱 Mobile responsive design

---

## 📄 License

This project is licensed under the **GPL v2 or later** - see the [LICENSE](https://www.gnu.org/licenses/gpl-2.0.html) file for details.

---

## 👨‍💻 Author

**Juan Carlos Garcia**
- 🌐 Website: [werock.space](https://werock.space)
- 📧 Email: contact@werock.space
- 🐙 GitHub: [@yourusername](https://github.com/yourusername)

---

## 🙏 Acknowledgments

- WordPress community for excellent documentation
- WooCommerce team for robust e-commerce platform
- React team for the amazing frontend library
- All contributors who help improve this plugin

---

## 📞 Support

- 📚 **Documentation**: [Plugin Wiki](https://github.com/yourusername/kset-dynamic-shop-filters/wiki)
- 🐛 **Bug Reports**: [GitHub Issues](https://github.com/yourusername/kset-dynamic-shop-filters/issues)
- 💬 **Community**: [WordPress Forums](https://wordpress.org/support/plugin/kset-dynamic-shop-filters)
- 📧 **Email**: support@werock.space

---

<p align="center">
  <strong>⭐ If you find this plugin helpful, please consider giving it a star on GitHub! ⭐</strong>
</p>

<p align="center">
  Made with ❤️ for the WordPress community
</p>