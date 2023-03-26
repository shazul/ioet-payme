# IOET Technical Exercise

## Disclaimer

Throughout this README, it is noted that the solution is incomplete (there is an almost missing planned section) but **the given problem was successfully solved** nonetheless. The reason for this Work In Progress state is lack of time.

## Overview

Payme is a PHP payroll calculator capable of reading a .txt file from a folder with a given format. After processing the file contents, it returns the amount that should be paid to each of the employees. It runs in a web browser and uses Nginx, Postgres and PHP in Docker containers.

### Features

- Reads a .txt file
- Can process time ranges from one minute to 24 hours
- Can handle time ranges that spand more than one hourly rate

## Architecture

![ioet (1)](https://user-images.githubusercontent.com/10780956/227805380-2f416dc0-9c78-40e9-9448-0bc3eb6adf92.jpg)

This free convention diagram (Not UML) shows the architecture of this solution. The solution was slightly inspired by two techniques: Layered architecture and DDD (Domain Driven Design). This is why there are some Core and Support components which could be grouped as business logic and support layer and data layer too.

There is a FileHandler that will read a text file stored in the server and convert it to an array which can be interpreted by the DataFormatter. This component will call the validators to check that data meets the requirement input format and then will prepare everything for the calculator component. Once the time ranges are received in the Calculator, it gets the corresponding hourly rates (could be more than one per time range) from the Database and returns the amount to be paid to each employee.

This architecture uses the following design patterns:

- **Singleton**: Used to get a single database instance
- **Adapter**: Used to connect the Database and the Calculator
- **Dependency Injection**: Used to decrease coupling between a few components
- **Strategy**: Used to be able to switch from PostgreSQL to MySQL or to any other DB Engine if needed (MySQL not fully implemented though)
- **Front Controller**: Not really implemented but the idea was to handle all the incoming requests in a central point. There was supposed to be a POST and a GET request to be handled
- **Service Layer**: The business logic is exposed as services and separated from the rest of the components


## Approach

The idea to solve this problem was to create a flexible solution that could be adapted as much as possible to any external component or even input, like the database engine or the text file. SOLID principles were also on top of the list.

Following those ideas, the aim of this project was to process a text file with multiple lines as stated in the exercise but adding the feature of handling an employee's time range that spans on several hourly rates:

Let's suppose an employee reports that he/she worked on Wednesday from 10:00 to 20:00. These time range will have an 8-hour range corresponding to the hourly rate of 15 (10:00-18:00) and 2 hours from the hourly rate of 20 (18:00-20:00). Payme would calculate this and would return 160 USD in this case.

Given the format of the input the idea was to validate this data first in terms of syntax and end time in every time range (End time should be bigger always). Afterwards a formatter would adapt it in a way that a simple calculator could give the total for every included time range and add it to a total for every employee. An iterator would be needed for this as well. The total for an employee report would be printed before moving to the next one.

The "complex" part was to calculate the total for a single employee's time range that would span more than one hourly rate range. The next flow diagram shows how it was solved:

![ioet-flow](https://user-images.githubusercontent.com/10780956/227808229-6e4cd075-3b72-4694-b7f6-8836b6c0415c.jpg)

## Methodology

With all these ideas in hands, a PostgreSQL Database was used to store the hourly rates and its corresponding weekdays and times. A type was created to store the weekdays as ENUM. The only table created was used to store the weekdays (an array of the type previously created) and the starting and ending time for a given hourly rate.

In order to avoid bugs in calculations, the starting hours were modified to remove that additional minute, like this: 08:01 was modified to 08:00. Also the 00:00 ending time in a couple of ranges was modified to 24:00 to make everything easier.

Since the location of the file and its name could be modified eventually, a config file was put in place to control this without touching the code. Same for the database configuration, including its connection credentials and the selected driver.

When it comes to the code structure, an autoloader was created to reduce the use of require / require_once everywhere and favor the aesthetic side of it (easier to read the namespaces and use keywords). The main entry point is index.php and the idea was to create an API that would call the File Controller (for files already stored) and the Not-implemented Upload Controller (to receive uploaded files). The File Controller then uses a common class from BaseController to launch the whole process.

There is a support layer (inspired by DDD) that holds the most generic and reusable items, like interfaces, database adapters and utils (which could be understood like big helpers). There is also a Database class that acts like a Database Manager.

The controller calls the formatting service first (maybe the validation should be called before) to validate and transform the file data into an associative array whose keys are the employees' names and the values are nested arrays with the time ranges from the report one by one (each of them is an array).

Finally the Payroll Calculator Service iterates through this array to get one by one the total for all time ranges included in the report and adds its result to the employee's amount that should be paid. Once the calculations are finished for an employee, his/her result is printed and the next employee report begins to be processed.

Unit tests were also included to test mostly business logic-related components.

## How to run it

### Requirements

To run this project you need Docker Compose installed on your machine. **You may also need to make sure that ports 8000 and 5432 are available to be used.** Finally you need a web browser to see it in action.

### Steps

1. Clone the project from GitHub
2. Execute `docker-compose up` on the project root directory
3. In a browser tab, go to `http://localhost:8000`. Now you should see the calculation results

If you want to run the Unit Tests, then you should follow these steps:

1. Make sure all containers are running (see step 2 from the list above)
2. Check the name of the php container. Use this command: `docker ps`
3. Open a bash inside the php container. Use this command: `docker exec -it <container> bash`
4. Once the bash shows up, make sure you are in `/var/www/html` folder
5. Run `phpunit tests/`

## Text File provided

This release includes a sample1.txt file with 5 lines of Employee reports.

Calculation results for the included are shown below (They should be displayed like that in the browser tab):

![image](https://user-images.githubusercontent.com/10780956/227805714-ce3e8e92-a7e1-423c-9acc-4c1a1c6e91b0.png)

## To Do

- Implement an API to handle uploaded files via POST as well
- Improve and increase test cases
- Improve exception/error handling
- Support folder could become a seedwork (DDD)
- We could read all files in the uploads folder, or maybe the most recent one
- Maybe Validators should be part of Support namespace too
- Decouple Validators from Formatting stage

## Known bugs

- If two time ranges are not separated by a comma, validation passes on the first one and the second one is ignored. Example: In the string `MO10:00-12:00TU08:00-09:00`, the first range is accepted and passes validation, but `TU08:00-09:00` is ignored.