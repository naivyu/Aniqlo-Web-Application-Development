
-- Creating the database
CREATE DATABASE aniqlo_db CHARACTER SET utf8 COLLATE utf8_general_ci;

USE aniqlo_db;

-- creating tables
CREATE TABLE users(
    user_id INT(10) PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    phone_num VARCHAR(15) NOT NULL,
    bday DATE,
    gender VARCHAR(20),
    cart_id INT(10)
);


CREATE TABLE enquiries(
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL, 
	email VARCHAR(255) NOT NULL, 
	phone_num VARCHAR(11) NOT NULL, 
	type VARCHAR(15) NOT NULL, 
	subject TEXT NOT NULL, 
	datetime DATETIME
);

CREATE TABLE products (
  prod_id INT(11) PRIMARY KEY AUTO_INCREMENT,
  prod_name VARCHAR(255) NOT NULL,
  prod_desc TEXT NOT NULL,
  prod_price DECIMAL(10, 2) NOT NULL,
  prod_category VARCHAR(255)
);

CREATE TABLE product_image (
  img_id INT(11) PRIMARY KEY AUTO_INCREMENT,
  prod_id INT(11),
  img_url VARCHAR(255),
  FOREIGN KEY (prod_id) REFERENCES products(prod_id)
);

CREATE TABLE product_variants (
  variant_id INT(11) PRIMARY KEY AUTO_INCREMENT,
  prod_id INT(11),
  img_id INT(11), 
  size VARCHAR(10),
  color VARCHAR(50),
  stock INT(11),
  FOREIGN KEY (prod_id) REFERENCES products(prod_id),
  FOREIGN KEY (img_id) REFERENCES product_image(img_id)
);

CREATE TABLE cart_details (
  cart_details_id INT(11) PRIMARY KEY AUTO_INCREMENT,
  cart_id INT(11),
  prod_id INT(11),
  variant_id INT(11),
  qty INT,
  FOREIGN KEY (prod_id) REFERENCES products(prod_id),
  FOREIGN KEY (cart_id) REFERENCES carts(cart_id),
  FOREIGN KEY (variant_id) REFERENCES product_variants(variant_id)
);

CREATE TABLE orders (
  order_id INT(11) PRIMARY KEY AUTO_INCREMENT,
  user_id VARCHAR(255),
  address_id INT(11),
  total DECIMAL(10, 2),
  payment_ref_no VARCHAR(255),
  status VARCHAR(20),
  order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (address_id) REFERENCES address(address_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE order_details (
  order_details_id INT(11) PRIMARY KEY AUTO_INCREMENT,
  order_id INT(11),
  variant_id INT(11),
  qty INT,
  unit_price DECIMAL(10, 2),
  FOREIGN KEY (order_id) REFERENCES orders(order_id),
  FOREIGN KEY (variant_id) REFERENCES product_variants(variant_id)
);

CREATE TABLE address (
  address_id INT(11) PRIMARY KEY AUTO_INCREMENT,
  street_address VARCHAR(255),
  unit_no_address VARCHAR(255),
  city VARCHAR(255),
  postal_code INT(5),
  state VARCHAR(255)
);

-- Users Entries
INSERT INTO users(email, password, name, phone_num, cart_id) VALUES 
('john@gmail.com', 'pass123', 'John Doe', '0114562315',1),
('alice@gmail.com', 'securePW', 'Alice Smith', '0127834582',2),
('david@hotmail.com', 'mypassword', 'David Johnson', '0188762398',3),
('emilyyy@gmail.com', 'letmein', 'Emily Brown', '0198976643',4),
('michael@hotmail.com', 'password123', 'Michael Davis', '0112675644',5);


-- Products Entries
INSERT INTO products (prod_name,prod_desc,prod_price,prod_category) VALUES 
('AIRism Cotton Jersey Short Sleeve Skipper Polo Shirt','Smooth AIRism fabric with the look of cotton. Combines the light feel of a T-shirt and the elegance of a polo.',99.90,'Men'), 
('SUPIMA COTTON Crew Neck Short Sleeve T-Shirt','100% SUPIMA® cotton with a premium texture. Designed with meticulous attention to detail.',59.90,'Men'),
('AIRism Cotton Sleeveless T-Shirt','Smooth AIRism fabric with the look of cotton. Versatile basic design and cut.',49.90,'Men'),
('Washable Milano Striped Ribbed Crew Neck Long Sleeve Sweater','Stylish and elegant, yet as easy to wear as a sweatshirt. With added stretch for easy movement.',149.90,'Men'),
('Crew Neck Short Sleeve T-Shirt','A versatile piece that styles as a T-shirt or as an inner layer.',49.90,'Women'),
('Pleated Sleeveless T-Shirt','Elegant pleats. Sleek boat neck design.',59.90,'Women'),
('Mini Short Sleeve T-Shirt','Distinctive compact fit and short length. For a 90s look.',49.90,'Women'),
('Linen Blend Open Collar Short Sleeve Shirt','A versatile layering piece with a natural feel.',99.90,'Women'),
('KIDS Cotton Open Collar Short Sleeve Shirt','Smooth and comfortable texture. Relaxed silhouette that can also be worn as an outer layer.',79.90,'Kids'),
('KIDS DRY Pique Striped Short Sleeve Polo Shirt','Versatile, classic design and cut. Soft fabric with DRY technology for stay-fresh comfort.',59.90,'Kids'),
('KIDS AIRism Cotton Graphic Short Sleeve T-Shirt','Smooth AIRism fabric with the look of cotton. Relaxed silhouette with a raglan sleeve design.',39.90,'Kids'),
('KIDS Ultra Stretch DRY-EX Crew Neck Short Sleeve T-Shirt','Quick-drying DRY-EX keeps kids feeling fresh. Incredibly stretchy for easy movement.',39.90,'Kids');

-- Product Image Entries
INSERT INTO product_image (prod_id ,img_url) VALUES
(1, 'img1.jpeg'),
(1, 'img2.jpeg'),
(2, 'img3.jpeg'),
(2, 'img4.jpeg'),
(3, 'img5.jpeg'),
(3, 'img6.jpeg'),
(4, 'img7.jpeg'),
(4, 'img8.jpeg'),
(5, 'img9.jpeg'),
(5, 'img10.jpeg'),
(6, 'img11.jpeg'),
(6, 'img12.jpeg'),
(7, 'img13.jpeg'),
(7, 'img14.jpeg'),
(8, 'img15.jpeg'),
(8, 'img16.jpeg'),
(9, 'img17.jpeg'),
(9, 'img18.jpeg'),
(10, 'img19.jpeg'),
(10, 'img20.jpeg'),
(11, 'img21.jpeg'),
(12, 'img22.jpeg'),
(12, 'img23.jpeg');

-- Product Variant Entries
INSERT INTO product_variants (prod_id, img_id, size, color, stock) VALUES
(1, 1, 'S', 'pink', 30), (1, 1, 'M', 'pink', 10), (1, 1, 'L', 'pink', 0), (1, 2, 'S', 'green', 40), (1, 2, 'M', 'green', 54), (1, 2, 'L', 'green', 35),
(2, 3, 'S', 'green', 20), (2, 3, 'M', 'green', 15), (2, 3, 'L', 'green', 8), (2, 4, 'S', 'blue', 25), (2, 4, 'M', 'blue', 18), (2, 4, 'L', 'blue', 12),
(3, 5, 'S', 'white', 30), (3, 5, 'M', 'white', 20), (3, 5, 'L', 'white', 15), (3, 6, 'S', 'pink', 25), (3, 6, 'M', 'pink', 18), (3, 6, 'L', 'pink', 10),
(4, 7, 'S', 'navy', 30), (4, 7, 'M', 'navy', 20), (4, 7, 'L', 'navy', 15), (4, 8, 'S', 'white', 25), (4, 8, 'M', 'white', 18), (4, 8, 'L', 'white', 10),
(5, 9, 'S', 'grey', 30), (5, 9, 'M', 'grey', 25), (5, 9, 'L', 'grey', 20), (5, 10, 'S', 'beige', 35), (5, 10, 'M', 'beige', 30), (5, 10, 'L', 'beige', 25), 
(6, 11, 'S', 'yellow', 20), (6, 11, 'M', 'yellow', 15), (6, 11, 'L', 'yellow', 10), (6, 12, 'S', 'blue', 25), (6, 12, 'M', 'blue', 20), (6, 12, 'L', 'blue', 15), 
(7, 13, 'S', 'blue', 30), (7, 13, 'M', 'blue', 25), (7, 13, 'L', 'blue', 20), (7, 14, 'S', 'green', 35), (7, 14, 'M', 'green', 30), (7, 14, 'L', 'green', 25), 
(8, 15,  'S', 'yellow', 20), (8, 15, 'M', 'yellow', 15), (8, 15, 'L', 'yellow', 10), (8, 16, 'S', 'green', 25), (8, 16, 'M', 'green', 20), (8, 16, 'L', 'green', 15), 
(9, 17, 'S', 'black', 30), (9, 17, 'M', 'black', 25), (9, 17, 'L', 'black', 20), (9, 18, 'S', 'green', 35), (9, 18, 'M', 'green', 30), (9, 18, 'L', 'green', 25), 
(10, 19, 'S', 'white', 20), (10, 19, 'M', 'white', 15), (10, 19, 'L', 'white', 10), (10, 20, 'S', 'green', 25), (10, 20, 'M', 'green', 20), (10, 20, 'L', 'green', 15), 
(11, 21, 'S', 'blue', 20), (11, 21, 'M', 'blue', 19), (11, 21, 'L', 'blue', 10), 
(12, 22, 'S', 'blue', 25), (12, 22, 'M', 'blue', 19), (12, 22, 'L', 'blue', 15), (12, 23, 'S', 'pink', 29), (12, 23, 'M', 'pink', 21), (12, 23, 'L', 'pink', 15);

-- Orders Entries
INSERT INTO orders(user_id, address_id, total, order_date, payment_ref_no, status) VALUES 
(1, 1, 259.70, '2024-04-10 04:32:47' , 'TNG10042024043247', 'COMPLETED'),
(1, 2, 49.90, '2024-04-11 08:32:47','TNG11042024083247', 'COMPLETED'),
(2, 3, 149.70, '2024-04-12 10:32:47','TNG12042024103247', 'COMPLETED'),
(3, 4, 59.90, '2024-04-13 12:32:47','TNG13042024123247', 'COMPLETED'),
(5, 5, 199.80, '2024-04-14 04:32:47','TNG14042024043247', 'COMPLETED');
-- Order Details Entries
INSERT INTO order_details (order_id, variant_id, qty, unit_price) VALUES 
(1, 1, 2, 99.90), (1, 8, 1, 59.90), 
(2, 45, 1, 49.90), 
(3, 29, 1, 49.90), (3, 26, 1, 49.90), (3, 24, 1, 49.90),
(4, 58, 1, 59.90), 
(5, 69, 1, 39.90), (5, 20, 1, 149.90);

-- Address Entries
INSERT INTO address (street_address, unit_no_address, city, postal_code, state) VALUES 
('Jalan Lading 51','No 43','Ulu Tiram','81800','Johor'),
('Jalan SL2/3','No 20','Cheras','43200','Selangor'), 
('Jalan Batu Pahat 5','No 43','Batu Pahat','83000','Johor'), 
('Jalan Lading 51','No 43','Ulu Tiram','81800','Johor'), 
('Jalan Balakong 28','No 7','Cheras','43200','Selangor');

-- Cart Details Enteries
INSERT INTO cart_details (cart_id, prod_id, variant_id, qty) VALUES
(1, 1, 1, 2), (1, 2, 12, 1), 
(2, 5, 28, 1), (2, 8, 43, 1),
(3, 4, 18, 1), 
(4, 3, 14, 3),
(5, 10, 58, 1), (5, 4, 22, 1);