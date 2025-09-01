DROP TABLE IF EXISTS properties;
CREATE TABLE properties (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  address VARCHAR(255),
  price DECIMAL(12,2),   --  numeric column
  bedrooms INT,
  bathrooms INT,
  floors INT,
  sqft INT,
  type ENUM('sale','rent'),
  image VARCHAR(255)
);

DROP TABLE IF EXISTS admins;
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert a default admin
INSERT INTO admins (username, password) VALUES ('admin', '1234');


DROP TABLE IF EXISTS agents;
CREATE TABLE agents (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  role VARCHAR(100) NOT NULL,
  phone VARCHAR(20),
  email VARCHAR(100),
  image VARCHAR(255)
);



DROP TABLE IF EXISTS contact;
CREATE TABLE contact (
  id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(50),
  last_name VARCHAR(50),
  email VARCHAR(100),
  phone VARCHAR(10) NOT NULL,
  interest ENUM('buy','rent','other'),
  message TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

--  Insert sample data (without $ signs)
INSERT INTO properties (title,address,price,bedrooms,bathrooms,floors,sqft,type,image) VALUES
('17081 Perry Street','San Francisco, CA, USA',850000,4,3,2,1234,'sale','images/house1.jpeg'),
('52591 Union Boulevard','San Francisco, CA, USA',580000,4,2,3,1234,'sale','images/house2.jpeg'),
('33234 Washington Avenue','San Francisco, CA, USA',770000,4,2,2,1234,'sale','images/house3.jpeg'),
('15066 Banks Street','San Francisco, CA, USA',700000,4,2,3,1234,'sale','images/house4.jpeg'),
('Sumit Mansion','New Delhi, ND, India',1000000,6,3,4,12240,'sale','images/house4.jpeg'),
('11251 Terry Street','San Francisco, CA, USA',1500,4,2,3,1234,'rent','images/house1.jpeg'),
('22043 Columbus Avenue','San Francisco, CA, USA',1200,4,2,3,1234,'rent','images/house2.jpeg'),
('15878 Mulberry Street','San Francisco, CA, USA',1800,4,2,2,1234,'rent','images/house3.jpeg'),
('16698 Spring Street','San Francisco, CA, USA',2200,4,2,3,1234,'rent','images/house4.jpeg'),
('Zohori Mansion','New Delhi, ND, India',30000,6,3,4,12140,'rent','images/house4.jpeg');
