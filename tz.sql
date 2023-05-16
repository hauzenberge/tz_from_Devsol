CREATE TABLE `items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` CHAR(255),
  `phone` CHAR(15),
  `item_key` CHAR(25) NOT NULL,
  `created_at` DATETIME,
  `updated_at` DATETIME
);

CREATE TABLE `item_history` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `item_id` INT NOT NULL,
  `name` CHAR(255),
  `phone` CHAR(15),
  `item_key` CHAR(25) NOT NULL,
  `created_at` DATETIME,
  `updated_at` DATETIME,
  FOREIGN KEY (`item_id`) REFERENCES items(`id`)
);

