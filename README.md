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

### Using API by endpoints

This system can be used completely by API endpoints.
You can import the `PostmanEndpoints.postman_collection.json` file to your Postman to test and manage all the endpoints.
You will need this to manage users.
Use the `Market Sys > auth > [POST] login & generate token` to login as **admin** and set the returned token on `{{auth_token}}` variable in Header > Authorization.

### Routes

All the routes is in the file `core/Routes.php` separated by HTTP methods. The endpoint calls the 'Class@function' with all the parameters.

### Unit tests

- To run the PHPUnit tests, you need to install `composer`. After that, you need to run in the root folder: `composer install phpunit`;
- Now, you can run `./vendor/bin/phpunit --testdox tests` to run the tests.

### Work in Progress (not ready yet):

-  Frontend market billing page;

### Technologies

* PHP 7.4
* PostgreSQL 15
* ReactJS 17
* JWT Auth
* PHP Unit

### Developer

[Rafael Garcia - Linkedin](https://www.linkedin.com/in/rafaelrrgarcia/)

## API Endpoints

### Auth (login)

```[GET] /me```
*  **Description**: Check the token. Used by frontend
*  **Send parameters**: None. Only token in headers

```[GET] /auth```
*  **Description**: Login and retrieve JWT token
*  **Send parameters**: 

```json
{
   username: string
   password: string
}
```

### Users (admin only)

```[GET] /users```
*  **Description**: Return a list of users who can operate the cashier's POS
*  **Send parameters**: None

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

### Product Types

```[GET] /producttypes```
*  **Description**: Return a list of product types and his taxes (float 0.00 to 1.00)
*  **Send parameters**: None.

```[POST] /producttypes```
*  **Description**: Register a new type
*  **Send parameters**:

```json
{
   name: string
   tax: float
}
```

```[GET] /producttypes/:id```
*  **Description**: Get specific type data
*  **Send parameters**: None

```[POST] /producttypes/:id```
*  **Description**: Update type data (name and tax)
*  **Send parameters**:

```json
{
   name: string
   tax: float
}
```

```[DELETE] /producttypes/:id```
*  **Description**: Delete type (inactivate only to keep references)
*  **Send parameters**: None

### Products

```[GET] /products```
*  **Description**: Return a list of products and all his values (types and values)
*  **Send parameters**: None.

```[POST] /products```
*  **Description**: Register a new product
*  **Send parameters**:

```json
{
   name: string
   value: int
   id_type: int
}
```

```[GET] /products/:id```
*  **Description**: Get specific product data
*  **Send parameters**: None

```[POST] /products/:id```
*  **Description**: Update product data (name, value and type)
*  **Send parameters**:

```json
{
   name: string
   value: int
   id_type: int
}
```

```[DELETE] /products/:id```
*  **Description**: Delete product (inactivate only to keep references)
*  **Send parameters**: None

### Sales (can't update after saved)

```[GET] /sales```
*  **Description**: Return a list of sales
*  **Send parameters**: None

```[POST] /sales```
*  **Description**: Register a new sale. You need to send an array in the `data` property
*  **Send parameters**:

```json
{ 
   "data": [
      {
         "id_product": int,
         "quantity": int
      }
   ]
}
```

```[GET] /sales/:id```
*  **Description**: Get specific sale data
*  **Send parameters**: None

```[DELETE] /sales/:id```
*  **Description**: Delete sale (inactivate only to keep references)
*  **Send parameters**: None