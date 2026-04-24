# User Documentation

## The User/Contributor logic

In the core you will have two models related to the user part: User and Contributor.

This system prevents deletion of datas and keeps separate datas for login and user.

### The User
The User model is the one who is logged in. It represents the authenticated user and contains information such as username, email, password and roles.

### The Contributor
The Contributor represents account datas and is linked to a User. All functionnalities that are used in the application and needs 
user datas are linked to a Contributor. Not to a User.

## Roles & Permissions

When you create a new Resource you can add permissions that will be linked to it.

The default logic is :

1. `view resource` to access list and show page
2. `edit resource` to access create/edit page
3. `delete resource` to process deletion

Permissions are linked to a resource by the Policies 

## Commands

1. Create a new user
```bash
    php artisan gingerminds:create:user
```