CREATE TABLE states (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    color VARCHAR(50) NOT NULL
);

CREATE TABLE users (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    username VARCHAR(24) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    image VARCHAR(240) NOT NULL,
    state_id INT,
    CONSTRAINT fk_user_state FOREIGN KEY (state_id) REFERENCES states(id) ON UPDATE CASCADE ON DELETE CASCADE
);
