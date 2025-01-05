# Store Ecommerce Application

## Overview
This is an ecommerce application built using the Laravel framework. It allows users to create, and view products. The frontend is implemented using Blade templates, while the backend is powered by Laravel's Eloquent ORM. No APIs were used; routing is managed through the `web.php` file.

## Features

### User Registration and Authentication
- **User registration with email verification**: Users can register with email verification to ensure valid accounts.
- **Login with session management**: Manages user login with session handling and transfers cart data from the session to the user account.
- **Password reset functionality**: Ensures cart data is retained across password resets for uninterrupted cart management.
- **Logout functionality**: Clears the user's cart and session data upon logout.

### Cart Management
- **Cart data storage and transfer**: Stores and transfers cart data between sessions during user login for both authenticated and guest users.
- **Display cart items**: Retrieves and displays cart items for both authenticated and guest users, with pagination for cart items (5 per page).
- **Add products to the cart**: Allows users (authenticated or guest) to add products to their cart, creating a new cart or updating the quantity of existing items.
- **Remove products from the cart**: Enables users to remove products from their cart, ensuring proper session handling for guest users.
- **Update cart item quantity**: Increases the quantity of a product in the cart if it already exists.
- **Cart data protection**: Protects cart data from unauthorized access during cart management actions.

### Session and Cart Persistence
- **Persist cart data for guest users**: Ensures cart data for guest users is persisted between sessions after adding products to the cart.
- **Session management for guest users**: Cart items are managed using sessions for guest users and are associated with the session ID.
- **Logging of cart actions**: Logs important cart actions (adding/removing products, updating quantities, session management) for debugging and tracking.
- **Redirect to cart index**: After adding or removing products from the cart, users are redirected to the cart index page with a success message.

### Product Management
- **Display paginated products**: Displays a paginated list of products with 20 products per page.
- **Add new products to the database**: Allows the addition of new products to the database.
- **View detailed product information**: Provides detailed information about individual products.
- **Upload and store product images**: Uploads product images and stores them in the public folder for easy access.

## Requirements
- PHP >= 8.0
- Composer
- Laravel >= 10.x
- MySQL or any other supported database
- Node.js & npm (optional, for frontend build tools)

## Installation

### Step 1: Clone the Repository
```bash
git clone https://github.com/Martin888Maina/Store-Ecommerce-Application-Laravel.git
cd your-repository
```

### Step 2: Install Dependencies
```bash
composer install
```

### Step 3: Set Up the `.env` File
Copy the `.env.example` file to `.env`:
```bash
cp .env.example .env
```

Update the `.env` file with your database and application settings:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### Step 4: Generate Application Key
```bash
php artisan key:generate
```

### Step 5: Run Migrations
```bash
php artisan migrate
```

### Step 6: Serve the Application
```bash
php artisan serve
```

Visit the application at [http://localhost:8000](http://localhost:8000).


## Email Configuration

To enable email features such as user registration email verification and password reset functionality, you will need to configure Gmail SMTP settings. Specifically, you will need to generate an app password for your Gmail account.

### Steps to Generate an App Password for Gmail:

1. **Enable 2-Step Verification:**
   - Go to [Google Account Security](https://myaccount.google.com/security).
   - Under the "Signing in to Google" section, enable **2-Step Verification** if you haven't already.

2. **Generate an App Password:**
   - After enabling 2-Step Verification, navigate to the **App passwords** section (under "Security" settings).
   - Select the app you are using (for example, "Mail") and the device (for example, "Windows Computer").
   - Click **Generate** to create an app password.

3. **Update `.env` File with App Password:**
   - In your Laravel project, open the `.env` file and update the following SMTP settings with your Gmail email and the generated app password:

   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your-email@gmail.com
   MAIL_PASSWORD=your-generated-app-password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=your-email@gmail.com
   MAIL_FROM_NAME="${APP_NAME}"

## Usage

### User Registration
1. Navigate to the `/register` route to access the user registration form.
2. Fill in the registration details (name, email, password, and password confirmation).
3. Submit the form to create a new user.
4. The user's data, including cart data from the session (if any), will be saved to the database.
5. A verification email will be sent to the provided email address.
6. After submitting the registration form, you will be redirected to a verification notice page instructing you to verify your email.

### User Login
1. Navigate to the `/login` route to access the login form.
2. Fill in your email and password.
3. Submit the form to log in.
4. If you were previously adding items to your cart as a guest, your cart items will be transferred to your user account after a successful login.
5. After logging in, you will be redirected to the intended page (either the checkout page or the previous page).

### Logging Out
1. To log out, navigate to the `/logout` route.
2. Your session will be cleared, and any cart data will be deleted from the database.
3. You will be redirected to the landing page after logging out.

### Password Reset Request
1. Navigate to the `/password/reset` route to request a password reset.
2. Enter the email associated with your account and submit the form.
3. If the email exists in the system, a password reset link will be sent to your email.
4. After receiving the reset email, click the link to reset your password.

### Resetting Your Password
1. Follow the password reset link sent to your email to access the password reset form.
2. Enter your new password (and confirmation) along with the reset token.
3. Submit the form to update your password.
4. If you had cart data saved during the password reset process, it will be restored to your session after a successful password reset.

### Adding Products
1. Navigate to the `/products/create` route to access the product creation form.
2. Fill in the product details (name, description, price, and image) and submit the form.
3. The product will be saved to the database and displayed on the products listing page.

### Viewing Products
- The landing page displays a paginated list of products (20 per page).
- Click on a product to view detailed information.

### Viewing Cart
1. Navigate to the `/cart` route to view the cart page.
2. The cart will display the products added to the cart for both authenticated and guest users.
3. The cart items are paginated with 5 items per page. If there are more than 5 items, pagination will be displayed to allow navigation through additional items.

### Displaying Cart Data
1. Navigate to the `/cart/display` route to view the detailed cart information.
2. The cart page will display all the items in the cart, including the product details, and calculate the grand total based on the selected products and quantities.
3. The cart will also paginate items with 5 items per page.
4. If the user is authenticated, the cart items will be retrieved from the database; if the user is a guest, the cart data will be retrieved from the session.

### Adding Products to the Cart
1. To add a product to the cart, navigate to the `/cart/add/{productId}` route, replacing `{productId}` with the ID of the product to add.
2. If the user is authenticated, the cart data will be saved in the database under the user's ID.
3. If the user is a guest, the cart data will be stored in the session and updated accordingly.
4. If the product already exists in the cart, the quantity will be incremented by one; otherwise, the product will be added to the cart with a quantity of 1.
5. After adding a product, you will be redirected back to the cart page with a success message confirming the addition.

### Removing Products from the Cart
1. To remove a product from the cart, navigate to the `/cart/remove/{cartItemId}` route, replacing `{cartItemId}` with the ID of the cart item to remove.
2. If the user is authenticated, the cart item will be removed from the database.
3. If the user is a guest, the cart item will be removed from the session.
4. After removing a product, you will be redirected back to the cart page with a success message confirming the removal.

### Notes
- For **authenticated users**, cart data is stored in the database and associated with the user's account.
- For **guest users**, cart data is temporarily stored in the session, which allows users to continue shopping even if they navigate away from the site. Upon login, the cart data is transferred to the user's account.
- The cart is protected against unauthorized access, ensuring that only the correct user can modify their own cart.
- Cart actions such as adding, updating, and removing products are logged for debugging and tracking purposes.

## Folder Structure
- **`app/Http/Controllers/ProductController.php`**: Manages product-related operations.
- **`resources/views/products`**: Contains Blade templates for displaying products.
- **`app/Http/Controllers/AuthController.php`**: Manages authentication operations.
- **`resources/views/auth`**: Contains Blade templates for handling user authentication.
- **`app/Http/Controllers/CartController.php`**: Manages the user's cart management operations.
- **`resources/views/cart`**: Contains Blade templates for handling user cart and its management.
- **`routes/web.php`**: Defines the web routes.

## Key Files
- **ProductController.php**: Handles product creation, storage, and retrieval.
- **AuthController.php**: Handles user authentication.
- **CartController.php**: Handles cart operations and management.
- **.env**: Environment-specific configurations (not committed to Git).
- **.env.example**: Template for environment configuration.

## Notes
- Ensure the `storage` and `bootstrap/cache` directories are writable.
- Images are stored in the `public/storage/products` directory.

## License
This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
