# Tablentor – Smart Table Builder for Elementor

[![WordPress Plugin Version](https://img.shields.io/badge/WordPress-5.0%2B-blue.svg)](https://wordpress.org/)
[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-purple.svg)](https://php.net/)
[![Elementor](https://img.shields.io/badge/Elementor-Required-pink.svg)](https://elementor.com/)
[![License](https://img.shields.io/badge/License-GPLv2%2B-green.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

Create beautiful, responsive tables effortlessly in Elementor with manual table building or dynamic CSV data import capabilities.

## 🚀 Overview

**Tablentor** is the ultimate table builder addon for Elementor that empowers you to create stunning, responsive tables with ease. Whether you need to build tables manually or import data from CSV files, Tablentor provides all the tools you need to create professional-looking tables that perfectly match your website's design.

## ✨ Key Features

### 🎯 Core Functionality
- **Two Powerful Widgets**: Basic Table Builder and CSV Table Import
- **Manual Table Creation**: Build tables row by row with unlimited customization
- **CSV Data Import**: Import tables directly from CSV files or paste CSV data
- **Advanced Search Functionality**: Enable live search within your tables
- **Responsive Design**: Tables automatically adapt to all screen sizes

### 🎨 Design & Styling
- **Rich Styling Options**: Complete control over colors, typography, borders, and spacing
- **Professional Typography**: Full typography controls for headers and content
- **Image Support**: Include images in your table cells with height controls
- **Mobile-First Design**: Tables look great on all devices

### ⚡ Advanced Features
- **DataTables Integration**: Advanced features like pagination, sorting, and filtering
- **Up to 20 Columns**: Support for complex data structures
- **Unlimited Rows**: No restrictions on table size
- **Cache Compatible**: Works perfectly with Elementor's caching system

## 🎯 Perfect Use Cases

| Use Case | Description |
|----------|-------------|
| **Data Presentation** | Display product comparisons, pricing tables, specifications |
| **CSV Data Visualization** | Import and display data from spreadsheets |
| **Business Reports** | Create professional reports and dashboards |
| **Educational Content** | Present structured information clearly |
| **E-commerce** | Product comparison tables and feature lists |
| **Portfolio Showcases** | Organize project details and specifications |

## 📋 Requirements

- **WordPress**: 5.0 or higher
- **PHP**: 7.4 or higher
- **Elementor**: Free version (required)
- **Browser Support**: All modern browsers

## 🚀 Installation

### Automatic Installation (Recommended)

1. Log in to your WordPress admin dashboard
2. Navigate to **Plugins > Add New**
3. Search for "Tablentor"
4. Click **Install Now** and then **Activate**
5. The Tablentor widgets will appear in your Elementor editor under the **Basic** category

### Manual Installation

1. Download the plugin zip file
2. Log in to your WordPress admin dashboard
3. Navigate to **Plugins > Add New > Upload Plugin**
4. Choose the downloaded zip file and click **Install Now**
5. Activate the plugin through the **Plugins** menu

### Getting Started

1. Edit any page/post with Elementor
2. Search for "Basic Table" or "Table CSV" in the widget panel
3. Drag and drop the widget to your desired location
4. Configure your table settings and content
5. Publish your page to see your beautiful table in action

## 🎮 Usage Examples

### Basic Table Widget

```php
// Example of manual table creation
1. Add the Basic Table widget to your page
2. Set the number of columns (up to 20)
3. Add rows and content for each cell
4. Customize styling options
5. Enable search functionality if needed
```

### CSV Table Widget

```csv
// Example CSV data format
Name, Age, City, Country
John Doe, 25, New York, USA
Jane Smith, 30, London, UK
Mike Johnson, 35, Toronto, Canada
```

## 🔧 Configuration Options

### Basic Table Settings
- **Column Count**: Set up to 20 columns
- **Row Management**: Add unlimited rows
- **Search Functionality**: Enable/disable live search
- **Responsive Behavior**: Automatic mobile adaptation

### CSV Table Settings
- **Data Source**: Text input or file URL
- **File Support**: CSV format only
- **DataTables Features**: Pagination, sorting, filtering
- **Performance**: Optimized for large datasets

### Styling Controls
- **Typography**: Font family, size, weight, color
- **Colors**: Background, text, border colors
- **Spacing**: Padding, margins, cell spacing
- **Borders**: Style, width, color, radius

## 🎨 Customization

### CSS Classes
```css
/* Target specific table elements */
.ct-basic-table { /* Basic table container */ }
.tablentor-table-csv { /* CSV table container */ }
.tablentor-bt-search { /* Search input container */ }
```

### Hooks and Filters
```php
// Example filter for customizing table output
add_filter('tablentor_table_output', 'custom_table_modifications');
```

## 🔍 Troubleshooting

### Common Issues

**Q: Tables not displaying correctly on mobile**
A: Ensure your theme supports responsive design and check for CSS conflicts.

**Q: CSV import not working**
A: Verify the CSV file format and ensure the URL is accessible.

**Q: Search functionality not working**
A: Check if JavaScript is enabled and there are no console errors.

**Q: Styling not applying**
A: Clear cache and check for CSS specificity issues.

## 🤝 Contributing

We welcome contributions to Tablentor! Here's how you can help:

### Development Setup

1. Clone the repository
2. Install dependencies: `composer install`
3. Set up a local WordPress environment
4. Activate the plugin for testing

### Contribution Guidelines

- Follow WordPress coding standards
- Write clear, documented code
- Test thoroughly before submitting
- Include relevant documentation updates

### Reporting Issues

- Use the [GitHub Issues](https://github.com/jakariaistauk/tablentor/issues) page
- Provide detailed reproduction steps
- Include WordPress and plugin version information
- Attach screenshots if applicable

## 📄 License

This project is licensed under the GPL v2 or later - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Author

**Jakaria Istauk**
- Website: [jakaria.com.bd](https://jakaria.com.bd)
- WordPress Profile: [@jakariaistauk](https://profiles.wordpress.org/jakariaistauk/)
- Email: jakariamd35@gmail.com

## 🙏 Acknowledgments

- Thanks to the Elementor team for creating an amazing page builder
- WordPress community for continuous support and feedback
- All contributors who help improve this plugin

## 📞 Support

- **Plugin Support**: [WordPress.org Support Forum](https://wordpress.org/support/plugin/tablentor/)
- **Documentation**: [Plugin Documentation](https://github.com/jakariaistauk/tablentor/wiki)
- **Feature Requests**: [GitHub Issues](https://github.com/jakariaistauk/tablentor/issues)

---

⭐ **If you find Tablentor helpful, please consider giving it a star on GitHub and a review on WordPress.org!**
