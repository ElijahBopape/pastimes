# Pastimes Clothing Store

Pastimes is a full-stack web-based clothing marketplace designed to connect buyers and sellers through a structured and user-friendly platform. The system demonstrates core web development concepts including authentication, role-based access control, product management, and basic e-commerce functionality.

---

## Project Demo

You can view a walkthrough of the system here:
YouTube Demo: https://youtu.be/9QjVI3HP1Lo

---

## Repository

Access the full source code here:
GitHub Repository: https://github.com/ElijahBopape/pastimes.git

---

## Usage Walkthrough

### Buyers

1. Register as a Buyer (all fields are required).
2. Wait for administrator verification.
3. Log in using your username or email and password.
4. Browse available products and add items to the cart (a price confirmation popup is displayed).
5. Manage the cart by updating quantities or removing items.
6. Proceed to checkout, enter delivery details, and place the order.
7. Use the messaging system to communicate with sellers.

---

### Sellers

1. Register as a Seller (requires administrator approval).
2. Log in after approval has been granted.
3. Upload a new clothing item by completing the required form.
4. Submit the item and wait for administrator approval.
5. Once approved, the item becomes visible in the product listing.

---

### Administrators

1. Access the admin panel via:
   admin_login.php

   Default credentials:
   Email: [admin@pastimes.com](mailto:admin@pastimes.com)
   Username: admin
   Password: password

2. Administrator capabilities include:

   * Verifying pending users by updating the is_verified field
   * Adding new customers
   * Editing customer details
   * Deleting customers

3. Product approval:

   * Currently handled manually via the database (tblProduct.admin_approved)
   * Can be integrated into the admin panel in future versions

---

## Known Limitations and Future Improvements

* Product approval requires manual database modification
* Image upload functionality is not fully implemented (images must be placed manually in the assets folder)
* Password hashing uses MD5, which is not secure for production environments

  * Recommended improvement: use password_hash() and password_verify()
* No payment gateway integration
* Limited validation and error handling

---

## Technology Stack

Frontend: HTML, CSS, JavaScript
Backend: PHP
Database: MySQL
Server Environment: XAMPP

---

## Setup Instructions

1. Install XAMPP and start the following services:

   * Apache
   * MySQL

2. Place the project folder inside:
   htdocs/

3. Open phpMyAdmin and:

   * Create a database named ClothingStore
   * Import the provided SQL file or run:
     loadClothingStore.php

4. Access the project in your browser:
   http://localhost/your-project-folder

---

## Support and Troubleshooting

Ensure the following if issues occur:

* Apache and MySQL services are running
* The database is correctly created and connected
* All PHP files are located in the correct directory
* File permissions allow proper access
* Asset files (images, CSS, JavaScript) are correctly linked

---

## Design

The interface uses a natural color palette inspired by wood, sand, and neutral tones. The design prioritizes simplicity, readability, and ease of navigation.

---

## References

Development Tools:

* PHP Documentation: https://www.php.net/docs.php
* MySQL Documentation: https://dev.mysql.com/doc/
* XAMPP: https://www.apachefriends.org/
* phpMyAdmin: https://www.phpmyadmin.net/

Design and UI Resources:

* Coolors (color palettes): https://coolors.co/
* Dribbble (design inspiration): https://dribbble.com/
* Font Awesome (icons): https://fontawesome.com/
* Placeholder Images: https://via.placeholder.com/

Learning Resources:

* W3Schools: https://www.w3schools.com/
* MDN Web Docs: https://developer.mozilla.org/
* CSS-Tricks: https://css-tricks.com/

---

## License

This project is intended for educational purposes only. It is not licensed for commercial use without permission.

---

## Credits

Developed as part of a web development assignment to demonstrate full-stack system design and implementation.

---

Pastimes – Where style meets sustainability.
