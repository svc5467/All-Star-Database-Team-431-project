USE database_allstars_script_testing;

#Zipcodes
CREATE TABLE Zipcodes
(
	state VARCHAR(50) NOT NULL,
	zipcode VARCHAR(20) NOT NULL,
	PRIMARY KEY (zipcode )
);

#Address
CREATE TABLE Addresses
(
	address_id INTEGER NOT NULL AUTO_INCREMENT,
	street VARCHAR(255) NOT NULL,
	city VARCHAR(255) NOT NULL,
	zipcode VARCHAR(20) NOT NULL,
	PRIMARY KEY (Address_ID),
	FOREIGN KEY (zipcode) REFERENCES Zipcodes(zipcode)
 	ON DELETE NO ACTION
);

CREATE TABLE Sellers
(
 seller_id varchar(100), # this will be a long hash string (uid)
 address_id Integer NOT NULL,
 seller_type varchar(20) NOT NULL, # this is either ‘Supplier’ or ‘User’
 revenue real NOT NULL,
 login_name varchar(100),
 pass VARCHAR(255) NOT NULL,
 UNIQUE(login_name),
 PRIMARY KEY(seller_id),
 FOREIGN KEY (address_id) REFERENCES Addresses(address_id)
 	ON DELETE NO ACTION
);

CREATE Table Users
(
  user_id VARCHAR(100) NOT NULL, 
  email VARCHAR(255) NOT NULL,
  balance varchar(20), 
  birthday DATETIME NOT NULL,
  annual_income real,
  gender char(1),
  last_name VARCHAR(100),
  first_name VARCHAR(100),
  PRIMARY KEY (User_id),
  UNIQUE (last_name, first_name),
  FOREIGN KEY (User_id) REFERENCES Sellers(seller_id)
	ON DELETE CASCADE
 );
 
 CREATE TABLE Suppliers
(
	supplier_id VARCHAR(100) NOT NULL,
	phone VARCHAR(20) NOT NULL,
	name VARCHAR(255) NOT NULL,
	PRIMARY KEY ( supplier_id ),
	FOREIGN KEY (supplier_id) REFERENCES Sellers(seller_id)
		ON DELETE CASCADE
);

#Sale, will not delete on user/item deletion as to maintain sale records.
# unique item/user/datetime combinations!
CREATE TABLE Items
(
  item_id INTEGER NOT NULL AUTO_INCREMENT,
  seller_id VARCHAR(255) NOT NULL,
  category_id INTEGER NOT NULL,
  title VARCHAR(75) NOT NULL,
  buy_it_now_price REAL NOT NULL,
  minimum_price REAL NOT NULL,
  current_stock INTEGER NOT NULL,
  description VARCHAR(255) NOT NULL,  
  auction_length REAL NOT NULL,
  date_posted DATETIME NOT NULL,
  PRIMARY KEY(item_id),
  FOREIGN KEY (seller_id) REFERENCES Sellers(seller_id )
	ON DELETE CASCADE # if seller is deleted and there's no bids, delete
 );
 
#Bid. Users can only have 1 active per item!
CREATE TABLE Bids 
(
  user_id VARCHAR(100) NOT NULL,
  sales_item_id INTEGER NOT NULL,
  time_of_bid DATETIME NOT NULL,
  amount INTEGER NOT NULL,
  description VARCHAR(255),
  PRIMARY KEY (user_id, sales_item_id),
  FOREIGN KEY (user_id) REFERENCES Users(user_id)
	ON DELETE NO ACTION,
  FOREIGN KEY (sales_item_id) REFERENCES Items(item_id )
	ON DELETE NO ACTION
);

# Categories can be pointed at by many sales items, and can be pointed at by many other categories (explained in contains)
CREATE TABLE Categories(
  category_id INTEGER NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description VARCHAR(255),
  PRIMARY KEY (category_id )
);

CREATE TABLE Contains(
  parent_category_id INTEGER NOT NULL,
  child_category_id INTEGER NOT NULL,
  PRIMARY KEY (parent_category_id , child_category_id ),
  UNIQUE(child_category_id), #note a category can only be a child once
  FOREIGN KEY (parent_category_id) REFERENCES Categories(category_id)
	ON DELETE NO ACTION, #when a parent category is deleted this would create a dangling reference and the child category wouldn’t belong anywhere so throw a no action error
  FOREIGN KEY (child_category_id) REFERENCES Categories(category_id )
	ON DELETE CASCADE # when a child category is deleted and it is not a parent of another category then it’s okay to delete this entry (note the sales items will prevent this from being deleted if it has items)
);

CREATE TABLE CreditCards(
  user_id VARCHAR(100) NOT NULL,
  card_number VARCHAR(100) NOT NULL,
  experation_date DATETIME NOT NULL,
  security_code VARCHAR(50) NOT NULL,
  card_type VARCHAR(100) NOT NULL,
  PRIMARY KEY (user_id , card_number ), # multiple users could share a credit card so the user id must play a part in the primary_key
  FOREIGN KEY (user_id) REFERENCES Users(user_id)
	ON DELETE CASCADE # if the user is deleted we don’t need their card anymore
);

CREATE TABLE GiftCards
(
 code VARCHAR(255) NOT NULL,
 amount REAL NOT NULL,
 PRIMARY KEY (Code)
);

CREATE TABLE SellerRatings
(
  user_id VARCHAR(100) NOT NULL,
  seller_id VARCHAR(100) NOT NULL, 
  date DATETIME NOT NULL, # will be auto generated no reason not to make it required
  number_of_stars real,
  description varchar(255),
  PRIMARY KEY(User_id, Seller_id),
  FOREIGN KEY (user_id) REFERENCES Users(user_id),
  FOREIGN KEY (seller_id) REFERENCES Sellers(seller_id)
	ON DELETE CASCADE ON UPDATE NO ACTION
);

#Sale, will not delete on user/item deletion as to maintain sale records.
# unique item/user/datetime combinations!
CREATE TABLE Sales
(
  user_id VARCHAR(100) NOT NULL,
  item_id INTEGER NOT NULL,
  sale_date DATETIME NOT NULL,
  buy_price REAL NOT NULL,
  quantity INTEGER NOT NULL,
  PRIMARY KEY(user_id, item_id, sale_date),
  FOREIGN KEY (user_id) REFERENCES Users(user_id)
	ON DELETE NO ACTION,
  FOREIGN KEY (item_id) REFERENCES Items(item_id)
	ON DELETE NO ACTION
);


CREATE TABLE ShoppingCarts
(
 user_id VARCHAR(100) NOT NULL,
 item_id INTEGER NOT NULL,
 PRIMARY KEY(user_id, item_id),
 FOREIGN KEY (user_id) REFERENCES Users(user_id)
	ON DELETE CASCADE, # if the user is removed, no need to keep their shopping cart
FOREIGN KEY (item_id) REFERENCES Items(item_id)
	ON DELETE CASCADE # if item_id is removed cascade to remove this item from their basket
);


CREATE TABLE Sessions
(
	seller_id varchar(100) NOT NULL,
    start_time DATETIME NOT NULL,
    last_request DATETIME NOT NULL,
	PRIMARY KEY(seller_id),
    FOREIGN KEY(seller_id) References Sellers(seller_id)
		ON DELETE NO ACTION # cant delete a seller whil while they are loged in
);

