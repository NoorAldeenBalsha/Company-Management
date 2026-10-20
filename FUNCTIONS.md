Function Name              |     Where I used it    |        What it does in my own words   |  Official Documentation Link                 |
___________________________|________________________|_______________________________________|______________________________________________|
                           | In all Repository files|It readies the SQL query before running|           [PDO::prepare]                     |
                           | like findById & create.| it. I used it so hackers can't do SQL | https://www.php.net/manual/en/pdo.prepare.php|
    `PDO::prepare()`       |                        | injection because it separates the    |                                              |
                           |                        | SQL code from the user input.         |                                              |
___________________________|________________________|_______________________________________|______________________________________________|
                           |In all Repository files.| It actually runs the query that we    |           [PDOStatement::execute]            |
                           |                        | prepared. It takes the array of       |www.php.net/manual/en/pdostatement.execute.php|
`PDOStatement::execute()`  |                        | variables and puts them in the query  |                                              |
                           |                        | safely.                               |                                              |
___________________________|________________________|_______________________________________|______________________________________________|
                           | In findById() functions| It brings only one row from the       |            [PDOStatement::fetch]             |
  `PDOStatement::fetch()`  |                        | database. I use it when I need to find|www.php.net/manual/en/pdostatement.fetch.php  |
                           |                        | just one employee or one laptop by ID.|                                              |
___________________________|________________________|_______________________________________|______________________________________________|
                           | In findAll() functions | It gets all the rows from the database|           [PDOStatement::fetchall]           |
                           |                        | and gives them back as an array. I use|www.php.net/manual/en/pdostatement.fetchall.php
`PDOStatement::fetchAll()` |                        | it for list pages to show everything  |                                              |
                           |                        | in tables.                            |                                              |
___________________________|________________________|_______________________________________|______________________________________________|
                           | In`update() & delete() | It tells me how many rows were changed|          [PDOStatement::rowcount]            |
`PDOStatement::rowCount()` | functions.             | or deleted. I check this to see if my |www.php.net/manual/en/pdostatement.rowcount.php
                           |                        | update or delete actually worked.     |                                              |
___________________________|________________________|_______________________________________|______________________________________________|
                           | In addProject() inside | It gives back the ID of the last thing|            [PDO::lastinsertid]               |
                           | ProjectRepository.     | I added to the database. I used it    |  www.php.net/manual/en/pdo.lastinsertid.php  |
 `PDO::lastInsertId()`     |                        | when creating a project to get its new|                                              |
                           |                        | ID so I can link the leader to it.    |                                              |
___________________________|________________________|_______________________________________|______________________________________________|
                           | In Service files       | It stops the database from saving     |             [PDO::begintransaction]          |
                           |like adding an employee.| immediately. It starts a safe area so |                                              |
`PDO::beginTransaction()`  |                        | I can do many queries together without|www.php.net/manual/en/pdo.begintransaction.php|
                           |                        | breaking data.                        |                                              |
___________________________|________________________|_______________________________________|______________________________________________|
                           | In Service files at the| It saves all the queries I did after  |             [PDO::commit]                    |
                           | end of `try`.          |beginTransaction. If everything is good| https://www.php.net/manual/en/pdo.commit.php |
    `PDO::commit()`        |                        |, it pushes them to the database.      |                                              |
___________________________|________________________|_______________________________________|______________________________________________|
`PDO::rollBack()`          | In Service files inside| If something goes wrong in the try    |                [PDO::rollback]               |
                           | `catch`.               | block, this cancels all the queries so|https://www.php.net/manual/en/pdo.rollback.php|
                           |                        | the database doesn't get messed up.   |                                              |
___________________________|________________________|_______________________________________|______________________________________________|
