CREATE TABLE IF NOT EXISTS guest
(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email varchar(255) NOT NULL,
    phone varchar(255) NOT NULL
);

create table IF NOT EXISTS data
(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    guest_id INT NOT NULL,
    data TEXT,
    FOREIGN KEY(`guest_id`) REFERENCES guest(`id`) ON UPDATE CASCADE ON DELETE RESTRICT
);