SELECT *
FROM (	
	SELECT *
	FROM (
		SELECT *  
		FROM Sellers  
		WHERE login_name='Irene' LIMIT 1
	) AS sellers_query
	INNER JOIN Users users
	ON users.user_id = sellers_query.seller_id
) AS sellers_users_together
INNER JOIN Addresses ads
ON ads.address_id = sellers_users_together.address_id


SELECT MAX(bids.amount)
FROM ( 
	SELECT * 
	FROM Items  
	WHERE item_id=8 LIMIT 1 
	) as i 
INNER JOIN Bids As bids 
ON bids.sales_item_id = i.item_id 
GROUP BY bids.sales_item_id LIMIT 1;

SELECT MAX(bids.amount) 
FROM ( 
	SELECT * 
	FROM Items
	WHERE item_id=47 LIMIT 1 
	) as i 
INNER JOIN Bids As bids 
ON bids.sales_item_id = i.item_id 
GROUP BY bids.sales_item_id LIMIT 1


INSERT into Bids(user_id,sales_item_id,time_of_bid,amount) VALUES('4ad8749a-3984-46fe-aae3-f73e741e6d60',47,'2016-04-27 11:02:56',502)
INSERT into Bids(user_id,sales_item_id,time_of_bid,amount) VALUES('4ad8749a-3984-46fe-aae3-f73e741e6d60',47,'2016-04-27 10:46:21',500);
INSERT into Bids(user_id,sales_item_id,time_of_bid,amount) VALUES('4ad8749a-3984-46fe-aae3-f73e741e6d60',47,'2016-04-27 11:02:56',502)

Select * from Bids where user_id ='4ad8749a-3984-46fe-aae3-f73e741e6d60' AND sales_item_id=47

Update Bids set '4ad8749a-3984-46fe-aae3-f73e741e6d60', sales_item_id=47,time_of_bid='2016-04-27 18:48:59', amount=502  where user_id ='4ad8749a-3984-46fe-aae3-f73e741e6d60' AND sales_item_id=47

SELECT seller_type as 'Account Type', revenue as Revenue, login_name as 'User Name', email as Email, balance as Balance, birthday as Birthday, annual_income as 'Annual Income', gender as Gender, last_name as 'Last Name', first_name as 'First Name', street as Street, city as City, zipcode as Zipcode Select * FROM ( Select * From (SELECT * FROM ( SELECT * FROM Sellers WHERE login_name='Irene' LIMIT 1 ) AS sellers_query INNER JOIN Users users ON users.user_id = sellers_query.seller_id ) as sellers_users_together INNER JOIN suppliers supON sup.supplier_id = sellers_users_together.seller_id ) AS sellers_users_suppliers_together INNER JOIN Addresses ads ON ads.address_id = sellers_users_suppliers_together.address_id)

SELECT seller_type as 'Account Type', revenue as Revenue, login_name as 'User Name', email as Email, balance as Balance, birthday as Birthday, annual_income as 'Annual Income', gender as Gender, last_name as 'Last Name', first_name as 'First Name', street as Street, city as City, zipcode as Zipcode
FROM (
		Select * 
		FROM ( 
			Select * 
			From ( 
				SELECT * 
				FROM ( 
					SELECT * 
					FROM Sellers 
					WHERE login_name='Irene' LIMIT 1 
				) AS sellers_query 
				INNER JOIN Users users 
				ON users.user_id = sellers_query.seller_id 
			) as sellers_users_together 
			OUTER JOIN suppliers sup 
			ON sup.supplier_id = sellers_users_together.seller_id 
		) AS sellers_users_suppliers_together 
		INNER JOIN Addresses ads
		ON ads.address_id = sellers_users_suppliers_together.address_id
);

select * from Sales s where user_id='19b75bde-49d0-41c4-b60a-2349ee3f5671' inner join items i where s.item_id = i.item_id
select * from (select * from Sales where user_id='4ad8749a-3984-46fe-aae3-f73e741e6d60' ) as s inner join Items i where s.item_id = i.item_id
	
#// get user info from people who have credit cards
# Irene|ut has a credit card
select login_name, pass from (select * from CreditCards inner join Sellers where seller_id=user_id) as q inner join Users as u where u.user_id=q.seller_id;

#// get a suppliers pass and login_name
# returns Sean|platea
select login_name, pass from (select * from suppliers limit 1) INNER JOIN Sellers on supplier_id=seller_id;