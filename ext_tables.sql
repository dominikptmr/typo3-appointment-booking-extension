CREATE TABLE IF NOT EXISTS appointments (
    id int NOT NULL auto_increment,
    name varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    appointment_start datetime NOT NULL,
    appointment_end datetime NOT NULL,
    PRIMARY KEY (id)
);