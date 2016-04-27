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