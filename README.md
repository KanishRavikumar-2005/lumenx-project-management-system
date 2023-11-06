# Lumənɛks Project Management System

> NOTE:
>> This Project was developed as a part of [Bharat Intern](https://bharatintern.live/b/i/index.html) Task for Full Stack Development


**Lumənɛks Project Management System** is a self-hostable PMS (Project Management System) built with PHP. It features a custom-made NoSQL database called JasperDB, which can be hosted on your server. This system is designed to facilitate project and task management within your organization.

## Features

- **User Management**: The main user is the admin, who can create both managers and regular users. Managers can oversee and manage regular users.

- **Announcements**: Anyone can post announcements, making it easy to communicate important information to all users.

- **Task Management**: Admins and managers can create tasks and assign them to users. Users can also volunteer to take up tasks.

- **In-Mail**: The system includes a mailing system for communication within the PMS, enabling seamless communication among users.

## Rules for Users

- **Database Folders**: Do not modify anything in the `/database/*` folders unless you fully understand what you are doing. These folders contain critical data for the system's operation.

- **users.jdb File**: Avoid clearing the `users.jdb` file, as it is the only way to set an admin user. For safety, there is a commented piece of code in the main `index.php` file that can be used to set an admin user. If you use this code, remember to comment it out to avoid creating multiple admin user entries.

- **Default Admin User**: The default admin user comes with the following credentials:
  - Username: admin
  - Password: 123456
    
  It is highly recommended to change the password for security reasons. Changing the password can be done from the user profile.

- **Database Security**: The database system is NoSQL and undergoes two-level encryption. Changing the values of the Key, Ekey, or Iv from the `db-conf.php` folder will render the database inaccessible. Handle these values with caution.

- **Code Customization**: The code is open for modification to suit your specific needs. You can adapt it to match your organization's requirements.

- **Server Environment**: This code was developed using Xampp server. If you run it on Xampp, it is recommended to place the code files in the file system path `xampp/htdocs/`. Do not create a new folder within `htdocs` to contain the code files, as any redirection may cause errors.

## Getting Started

Follow these steps to get started with the Lumənɛks Project Management System:

1. **Server Setup**: Ensure you have a web server (like Xampp) up and running.

2. **Database Configuration**: Review the database configuration in `db-conf.php`. Be cautious when modifying encryption-related values.

3. **Admin Setup**: To set an admin user, you can use the commented code in the `index.php` file. Remember to comment it out after using it to prevent duplicate admin entries.

4. **Access**: Access the system by navigating to the appropriate URL on your web server, for example, `http://localhost/` Code must be modified if Access path needs to be changed.

5. **Log In**: Use the default admin credentials to log in, but don't forget to change the password for security.

## License

This project is open-source and is available under the [MIT License](LICENSE).

## Support and Contributions

For questions, bug reports, or feature requests, please use the [Issues section](https://github.com/KanishRavikumar-2005/lumenx-project-management-system/issues). We welcome contributions, so feel free to fork the project and create pull requests.

**Lumənɛks Project Management System** is designed to help you manage your projects efficiently and communicate effectively within your organization. Enjoy using it, and feel free to adapt the code to meet your unique needs!
