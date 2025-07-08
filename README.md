# Westway Express - Delivery & Logistics Management System

A modern, fully functional website for Westway Express delivery and logistics company with comprehensive admin and user functionality.

## Features

### User Features
- **Package Tracking**: Real-time shipment tracking with detailed status updates
- **Quote Generation**: Instant shipping quotes with multiple service options
- **User Registration & Authentication**: Secure user accounts
- **Responsive Design**: Mobile-friendly interface

### Admin Features
- **Dashboard**: Overview of operations with key statistics
- **Shipment Management**: Create, track, and update shipment status
- **Quote Management**: View and manage customer quotes
- **Invoice Generation**: Automatic invoice creation with printable format
- **User Management**: Admin and regular user roles

### Technical Features
- **Modern UI**: Built with Tailwind CSS for responsive design
- **Secure Authentication**: Password hashing and session management
- **Database Integration**: MySQL database with proper relationships
- **XAMPP Compatible**: Ready to run in XAMPP environment
- **Print-Friendly Invoices**: Professional invoice layout for printing

## Installation Instructions

### Prerequisites
- XAMPP (Apache, MySQL, PHP)
- Web browser

### Setup Steps

1. **Install XAMPP**
   - Download and install XAMPP from https://www.apachefriends.org/
   - Start Apache and MySQL services

2. **Database Setup**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `westway_express`
   - Import the SQL file from `scripts/database-setup.sql`
   - Or run the SQL commands manually to create tables and sample data

3. **File Installation**
   - Copy all project files to your XAMPP htdocs directory
   - Example: `C:\xampp\htdocs\westway-express\`

4. **Configuration**
   - Update database credentials in `config/database.php` if needed
   - Default settings work with standard XAMPP installation

5. **Access the Application**
   - Open your web browser
   - Navigate to `http://localhost/westway-express/`

## Default Login Credentials

### Admin Account
- **Username**: admin
- **Password**: admin123
- **Email**: admin@westwayexpress.com

### Test User Account
You can register a new user account or create one through the registration page.

## File Structure

\`\`\`
westway-express/
├── config/
│   ├── database.php          # Database connection
│   └── auth.php             # Authentication functions
├── admin/
│   ├── dashboard.php        # Admin dashboard
│   ├── shipments.php        # Shipment management
│   ├── quotes.php           # Quote management
│   ├── create-shipment.php  # Create new shipments
│   └── invoice.php          # Invoice generation
├── api/
│   ├── track.php           # Tracking API endpoint
│   └── quote.php           # Quote generation API
├── scripts/
│   └── database-setup.sql  # Database schema and sample data
├── index.php               # Homepage
├── login.php              # User login
├── register.php           # User registration
├── logout.php             # Logout functionality
└── README.md              # This file
\`\`\`

## Key Features Explained

### Tracking System
- Unique tracking IDs generated automatically (format: WE + 9 digits)
- Real-time status updates (Pending, Picked Up, In Transit, Delivered, Cancelled)
- Detailed shipment information display

### Quote System
- Automatic cost calculation based on weight and service type
- Multiple service levels: Standard, Express, Overnight
- Quote validity tracking (30 days default)

### Invoice System
- Professional invoice layout
- Print-friendly design
- Automatic generation upon shipment creation
- Includes all shipment details and costs

### Admin Dashboard
- Key performance indicators
- Recent shipments and quotes overview
- Quick access to all management functions

## Service Types & Pricing

- **Standard Delivery**: Base rate $15.99 + $2/kg (3-5 business days)
- **Express Delivery**: 2.5x base rate + $2/kg (1-2 business days)
- **Overnight Delivery**: 5x base rate + $2/kg (Next business day)

## Security Features

- Password hashing using PHP's password_hash()
- Session-based authentication
- Role-based access control
- SQL injection prevention with prepared statements
- XSS protection with htmlspecialchars()

## Browser Compatibility

- Chrome (recommended)
- Firefox
- Safari
- Edge

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Ensure MySQL is running in XAMPP
   - Check database credentials in `config/database.php`
   - Verify database exists and tables are created

2. **Login Issues**
   - Use default admin credentials: admin/admin123
   - Clear browser cache and cookies
   - Check if sessions are working

3. **Tracking Not Working**
   - Ensure sample data is imported
   - Try tracking ID: WE001234567 or WE001234568

4. **Invoice Not Displaying**
   - Check if shipment exists
   - Verify admin permissions
   - Ensure proper tracking ID format

## Support

For technical support or questions about the system, please refer to the code comments or contact the development team.

## License

This project is developed for Westway Express and is proprietary software.
