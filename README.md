# Food Waste Reduction Platform

## Introduction

The Food Waste Reduction Platform is designed to minimize food waste by enabling users to donate and request food. This platform is built using PHP for the backend and HTML, CSS, and JavaScript for the frontend. It leverages XAMPP server and PHPMyAdmin for database management.

**Features**

- **User Registration and Login:** Secure user authentication system.
- **Food Donation and Request:** Users can donate or request food items.
- **Dynamic Menu Display:** The menu updates based on the donations and requests.
- **Database Management:** Efficient handling of food items using MySQL.

**Prerequisites**

- MAMP/XAMPP server
- PHP 7.4+
- MySQL
- PHPMyAdmin

**Installation**

1. **Clone the repository:**

   ```sh
   git clone https://github.com/yourusername/food-waste-reduction.git
   ```

2. **Start MAMP/XAMPP server:**
   
   - Ensure Apache and MySQL services are running.

3. **Set up the database:**
   
   - Open PHPMyAdmin.
   - Create a new database named `food_waste_db`.
   - Import the SQL file provided in the `database` folder to set up the required tables.

4. **Configure database connection:**

   - Update the database credentials in `db.php`:
   
     ```php
     $servername = "localhost";
     $username = "root";
     $password = "";
     $dbname = "food_waste_db";
     ```

5. **Run the application:**

   - Place the project folder in the `htdocs` directory of MAMP/XAMPP.
   - Open your web browser and navigate to `http://localhost/food-waste-reduction`.

**Usage**

1. **Register/Login:**

   - New users can register by providing their details.
   - Registered users can log in using their credentials.

2. **Donate Food:**

   - Navigate to the donation page.
   - Fill in the details of the food item you want to donate.
   - Submit the form to add the item to the database.

3. **Request Food:**

   - Navigate to the request page.
   - Browse through the available food items.
   - Select and request the desired item.

4. **View Menu:**

   - The menu page displays all available food items, updating dynamically based on donations and requests.

**Contact**

For any questions or support, please contact [aimendurraniii@gmail.com](aimendurraniii@gmail.com).
