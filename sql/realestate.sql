DROP TABLE IF EXISTS properties;
CREATE TABLE properties (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  address VARCHAR(255),
  price DECIMAL(12,2),   -- ✅ numeric column
  beds INT,
  baths INT,
  levels INT,
  sqft INT,
  type ENUM('sale','rent'),
  image VARCHAR(255)
);

DROP TABLE IF EXISTS contact;
CREATE TABLE contact (
  id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(50),
  last_name VARCHAR(50),
  email VARCHAR(100),
  interest ENUM('buy','rent','other'),
  message TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ✅ Insert sample data (without $ signs)
INSERT INTO properties (title,address,price,beds,baths,levels,sqft,type,image) VALUES
('17081 Perry Street','San Francisco, CA, USA',850000,4,3,2,1234,'sale','images/house1.jpeg'),
('52591 Union Boulevard','San Francisco, CA, USA',580000,4,2,3,1234,'sale','images/house2.jpeg'),
('33234 Washington Avenue','San Francisco, CA, USA',770000,4,2,2,1234,'sale','images/house3.jpeg'),
('15066 Banks Street','San Francisco, CA, USA',700000,4,2,3,1234,'sale','images/house4.jpeg'),
('Sumit Mansion','New Delhi, ND, India',1000000,6,3,4,123400,'sale','images/house4.jpeg'),
('11251 Terry Street','San Francisco, CA, USA',1500,4,2,3,1234,'rent','images/house1.jpeg'),
('22043 Columbus Avenue','San Francisco, CA, USA',1200,4,2,3,1234,'rent','images/house2.jpeg'),
('15878 Mulberry Street','San Francisco, CA, USA',1800,4,2,2,1234,'rent','images/house3.jpeg'),
('16698 Spring Street','San Francisco, CA, USA',2200,4,2,3,1234,'rent','images/house4.jpeg'),
('Zohori Mansion','New Delhi, ND, India',30000,6,3,4,123400,'rent','images/house4.jpeg');
