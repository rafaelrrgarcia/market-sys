## Market Sys [Work in Progress]

Simple software to manage stores, sales and products.

### How to install

- Clone this repository;
- Copy the environment variables example file with `cp env.php.example env.php`;
- Edit `env.php` with the Title, URL, Base URL, Auth KEY and database settings;
- Copy the PostgreSQL `db.sql` file to your server;
- Run `php -S localhost:8080` and the API is ready to use.
- The frontend is a React application (build only) in the **frontend** folder. To run, first install **npm serve** globally with ```npm install -g serve```
- Change **frontend/.env** file with your URL (localhost by default)
- Run the frontend with ```serve -s frontend```
- Your frontend will start in the browser locally (example: http://localhost:3000/)
- Only admin users can manage other users and have access to **Users endpoints**. They are identified by the **admin = true** column in the database.
- By default, the **administrator user** is pre-registered in the system with the following credentials:
  *  USER: admin
  *  PASS: 123456

### Routes

All the routes is in the file `core/Routes.php` separated by HTTP methods. The endpoint calls the 'Class@function' with all the parameters.

### Work in Progress (not ready yet):

-  Frontend market billing page;
-  Unit tests with PHP Unit;
-  Product and Billing endpoints;
-  Save sale on database

### Technologies

* PHP 7.4
* PostgreSQL 15
* ReactJS 17
* JWT Auth

### Developer

[Rafael Garcia - Linkedin](https://www.linkedin.com/in/rafaelrrgarcia/)

### API Endpoints

#### Users (admin only)

```[GET] /users```
*  **Description**: Return a list of users who can operate the cashier's POS
*  **Send parameters**: None.

```[POST] /users```
*  **Description**: Register a new user to operate the POS at the cashier
*  **Send parameters**:

```json
{
   username: string
   password: string
}
```

```[GET] /users/:id```
*  **Description**: Get specific user data
*  **Send parameters**: None

```[POST] /users/:id```
*  **Description**: Update user data (password)
*  **Send parameters**:

```json
{
   password: string
}
```

```[DELETE] /users/:id```
*  **Description**: Delete user (inactivate only to keep references)
*  **Send parameters**: None